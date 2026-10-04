<?php
/**
 * track_order.php  — Will Banwalo public API
 * GET ?ref=LW-2025-XXXX-BNW
 *
 * Returns JSON:
 * {
 *   success, order, personal_details, documents,
 *   beneficiaries, dispatch, log
 * }
 *
 * Security:
 *  - Sensitive fields (Aadhaar, PAN, Razorpay keys, IP) are masked / stripped.
 *  - stored_name (server disk path) is NEVER returned.
 *  - doc_token is returned ONLY for final_will rows (so the customer download link works).
 *  - Admin notes are extracted from payment_log where event = 'admin_note'.
 *  - All other log events are returned for the activity timeline.
 */

// ── Config ──────────────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'willbanwalo');
define('DB_USER',    'root');       // ← change
define('DB_PASS',    '');           // ← change
define('DB_CHARSET', 'utf8mb4');

// ── Headers ─────────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

// Only GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// ── Input validation ─────────────────────────────────────────────────────
$ref = trim($_GET['ref'] ?? '');
if ($ref === '') {
    echo json_encode(['success' => false, 'message' => 'Order reference is required.']);
    exit;
}
// Accept only alphanumeric + hyphen, 4–30 chars
if (!preg_match('/^[A-Z0-9\-]{4,30}$/i', $ref)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order reference format.']);
    exit;
}

// ── DB connection ────────────────────────────────────────────────────────
try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection error. Please try again later.']);
    exit;
}

// ── 1. Fetch order ───────────────────────────────────────────────────────
$stmt = $pdo->prepare(
    'SELECT id, order_ref, will_type, plan, plan_amount, gst_amount, total_amount,
            payment_status, status, created_at, updated_at
     FROM orders
     WHERE order_ref = ?
     LIMIT 1'
);
$stmt->execute([strtoupper($ref)]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode([
        'success' => false,
        'message' => 'No order found for that reference. Please double-check your email for the correct reference number.',
    ]);
    exit;
}

$orderId = (int) $order['id'];

// ── 2. Personal details ──────────────────────────────────────────────────
$stmt = $pdo->prepare(
    'SELECT full_name, dob, gender, religion, mobile, email,
            aadhaar, pan,
            address, city, state, pin,
            spouse_name, spouse_dob,
            executor_name, executor_phone
     FROM personal_details
     WHERE order_id = ?
     LIMIT 1'
);
$stmt->execute([$orderId]);
$personal = $stmt->fetch() ?: null;

// Mask sensitive identity fields
if ($personal) {
    // Aadhaar: show last 4 digits only
    if (!empty($personal['aadhaar'])) {
        $personal['aadhaar'] = 'XXXX XXXX ' . substr($personal['aadhaar'], -4);
    }
    // PAN: show first 3 + last 2 chars
    if (!empty($personal['pan'])) {
        $personal['pan'] = substr($personal['pan'], 0, 3) . '*****' . substr($personal['pan'], -2);
    }
    // Mask spouse Aadhaar if present
    if (!empty($personal['spouse_aadhaar'])) {
        $personal['spouse_aadhaar'] = 'XXXX XXXX ' . substr($personal['spouse_aadhaar'], -4);
    }
    // Do not expose raw religion or medical info — keep religion, drop medical_conditions (not selected)
}

// ── 3. Documents ─────────────────────────────────────────────────────────
// Return doc_token ONLY for final_will so the customer download link works.
// NEVER return stored_name (physical disk path).
$stmt = $pdo->prepare(
    "SELECT id,
            doc_type,
            original_name,
            mime_type,
            file_size,
            CASE WHEN doc_type = 'final_will' THEN doc_token ELSE NULL END AS doc_token,
            created_at
     FROM documents
     WHERE order_id = ?
     ORDER BY id ASC"
);
$stmt->execute([$orderId]);
$documents = $stmt->fetchAll();

// ── 4. Beneficiaries ────────────────────────────────────────────────────
$stmt = $pdo->prepare(
    'SELECT full_name, relationship, dob, share_pct, notes
     FROM beneficiaries
     WHERE order_id = ?
     ORDER BY id ASC'
);
$stmt->execute([$orderId]);
$beneficiaries = $stmt->fetchAll();

// ── 5. Assets ────────────────────────────────────────────────────────────
$stmt = $pdo->prepare(
    'SELECT asset_type, description, estimated_value
     FROM assets
     WHERE order_id = ?
     ORDER BY id ASC'
);
$stmt->execute([$orderId]);
$assets = $stmt->fetchAll();

// ── 6. Special Instructions ──────────────────────────────────────────────
$stmt = $pdo->prepare(
    'SELECT id, funeral_preference, funeral_notes, organ_donation,
            charity_notes, other_instructions
     FROM special_instructions
     WHERE order_id = ?
     LIMIT 1'
);
$stmt->execute([$orderId]);
$special = $stmt->fetch() ?: null;

// ── 7. Dispatch (standard & premium only) ────────────────────────────────
$dispatch = null;
if (in_array($order['plan'], ['standard', 'premium'], true)) {
    $stmt = $pdo->prepare(
        'SELECT tracking_number, dispatch_datetime, admin_notes,
                dispatched, dispatched_at
         FROM dispatch
         WHERE order_id = ?
         LIMIT 1'
    );
    $stmt->execute([$orderId]);
    $dispatch = $stmt->fetch() ?: null;
}

// ── 8. Activity log (payment_log) ────────────────────────────────────────
// Returned to the frontend for:
//   - Activity timeline  → all events
//   - Admin notes panel  → rows where event = 'admin_note'
//
// We EXCLUDE internal Razorpay raw webhook payloads from the JSON payload
// column to avoid exposing sensitive data, but keep the event label visible.
$stmt = $pdo->prepare(
    "SELECT event,
            CASE
                WHEN event = 'admin_note'    THEN payload          -- expose note text
                WHEN event = 'status_changed' THEN payload         -- expose from/to
                ELSE NULL                                           -- hide raw gateway data
            END AS payload,
            created_at
     FROM payment_log
     WHERE order_id = ?
     ORDER BY id ASC"
);
$stmt->execute([$orderId]);
$log = $stmt->fetchAll();

// ── 7. Response ──────────────────────────────────────────────────────────
echo json_encode([
    'success'              => true,
    'order'                => $order,
    'personal_details'     => $personal,
    'documents'            => $documents,
    'beneficiaries'        => $beneficiaries,
    'assets'               => $assets,
    'special_instructions' => $special,
    'dispatch'             => $dispatch,
    'log'                  => $log,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
