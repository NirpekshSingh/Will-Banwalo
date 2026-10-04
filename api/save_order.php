<?php
// =============================================
// api/save_order.php
// Handles saving each step's data.
// POST with JSON body: { step, order_id?, ...fields }
// =============================================
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Method not allowed.', 405);

$data = getPost();
$step = (int)($data['step'] ?? 0);

if ($step < 1 || $step > 7) jsonError('Invalid step.');

$db = getDB();

// ─────────────────────────────────────────────
// STEP 1 — Create order with will type
// ─────────────────────────────────────────────
if ($step === 1) {
    $willType = sanitize($data['will_type'] ?? '');
    if (!$willType) jsonError('Will type is required.');

    $ref = generateOrderRef();
    // Ensure uniqueness
    while (true) {
        $chk = $db->prepare('SELECT id FROM orders WHERE order_ref = ?');
        $chk->execute([$ref]);
        if (!$chk->fetch()) break;
        $ref = generateOrderRef();
    }

    $stmt = $db->prepare('INSERT INTO orders (order_ref, will_type, plan, plan_amount, gst_amount, total_amount, ip_address)
                           VALUES (?, ?, "standard", 4499.00, 809.82, 5308.82, ?)');
    $stmt->execute([$ref, $willType, $_SERVER['REMOTE_ADDR'] ?? null]);
    $orderId = (int)$db->lastInsertId();

    jsonSuccess(['order_id' => $orderId, 'order_ref' => $ref]);
}

// All other steps require order_id
$orderId = (int)($data['order_id'] ?? 0);
if (!$orderId) jsonError('order_id is required.');

// Verify order exists
$orderRow = $db->prepare('SELECT id FROM orders WHERE id = ?');
$orderRow->execute([$orderId]);
if (!$orderRow->fetch()) jsonError('Order not found.', 404);

// ─────────────────────────────────────────────
// STEP 2 — Personal details
// ─────────────────────────────────────────────
if ($step === 2) {
    $required = ['full_name', 'dob', 'mobile'];
    foreach ($required as $f) {
        if (empty($data[$f])) jsonError("Field '{$f}' is required.");
    }

    $mobile = preg_replace('/[^0-9]/', '', $data['mobile']);
    if (strlen($mobile) < 10) jsonError('Invalid mobile number.');

    // Upsert
    $exists = $db->prepare('SELECT id FROM personal_details WHERE order_id = ?');
    $exists->execute([$orderId]);

    if ($exists->fetch()) {
        $stmt = $db->prepare('UPDATE personal_details SET
            full_name=?, dob=?, gender=?, religion=?, mobile=?, email=?,
            aadhaar=?, pan=?, address=?, city=?, state=?, pin=?,
            spouse_name=?, spouse_dob=?, spouse_aadhaar=?,
            medical_conditions=?, primary_caretaker=?,
            business_name=?, business_reg=?,
            executor_name=?, executor_phone=?
            WHERE order_id=?');
    } else {
        $stmt = $db->prepare('INSERT INTO personal_details
            (full_name, dob, gender, religion, mobile, email,
             aadhaar, pan, address, city, state, pin,
             spouse_name, spouse_dob, spouse_aadhaar,
             medical_conditions, primary_caretaker,
             business_name, business_reg,
             executor_name, executor_phone, order_id)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    }

    $stmt->execute([
        sanitize($data['full_name']),
        $data['dob'],
        sanitize($data['gender'] ?? ''),
        sanitize($data['religion'] ?? ''),
        $mobile,
        sanitize($data['email'] ?? ''),
        preg_replace('/[^0-9]/', '', $data['aadhaar'] ?? ''),
        strtoupper(sanitize($data['pan'] ?? '')),
        sanitize($data['address'] ?? ''),
        sanitize($data['city'] ?? ''),
        sanitize($data['state'] ?? ''),
        sanitize($data['pin'] ?? ''),
        sanitize($data['spouse_name'] ?? ''),
        $data['spouse_dob'] ?? null,
        sanitize($data['spouse_aadhaar'] ?? ''),
        sanitize($data['medical_conditions'] ?? ''),
        sanitize($data['primary_caretaker'] ?? ''),
        sanitize($data['business_name'] ?? ''),
        sanitize($data['business_reg'] ?? ''),
        sanitize($data['executor_name'] ?? ''),
        sanitize($data['executor_phone'] ?? ''),
        $orderId,
    ]);

    jsonSuccess(['message' => 'Personal details saved.']);
}

// ─────────────────────────────────────────────
// STEP 3 — Beneficiaries
// ─────────────────────────────────────────────
if ($step === 3) {
    $beneficiaries = $data['beneficiaries'] ?? [];
    if (!is_array($beneficiaries) || empty($beneficiaries)) {
        jsonError('At least one beneficiary is required.');
    }

    // Validate total shares
    $total = array_sum(array_column($beneficiaries, 'share_pct'));
    if ($total > 100) jsonError('Total beneficiary share cannot exceed 100%.');

    // Replace all beneficiaries for this order
    $db->prepare('DELETE FROM beneficiaries WHERE order_id = ?')->execute([$orderId]);

    $stmt = $db->prepare('INSERT INTO beneficiaries (order_id, full_name, relationship, dob, share_pct, notes)
                           VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($beneficiaries as $b) {
        if (empty($b['full_name'])) continue;
        $stmt->execute([
            $orderId,
            sanitize($b['full_name']),
            sanitize($b['relationship'] ?? 'Other'),
            $b['dob'] ?? null,
            (float)($b['share_pct'] ?? 0),
            sanitize($b['notes'] ?? ''),
        ]);
    }

    jsonSuccess(['message' => 'Beneficiaries saved.']);
}

// ─────────────────────────────────────────────
// STEP 4 — Assets
// ─────────────────────────────────────────────
if ($step === 4) {
    $assets = $data['assets'] ?? [];
    if (!is_array($assets)) jsonError('Invalid assets data.');

    $db->prepare('DELETE FROM assets WHERE order_id = ?')->execute([$orderId]);

    $stmt = $db->prepare('INSERT INTO assets (order_id, asset_type, description, estimated_value, details)
                           VALUES (?, ?, ?, ?, ?)');
    foreach ($assets as $a) {
        if (empty($a['description'])) continue;
        $stmt->execute([
            $orderId,
            sanitize($a['asset_type'] ?? 'other'),
            sanitize($a['description']),
            isset($a['value']) ? (float)$a['value'] : null,
            isset($a['details']) ? json_encode($a['details']) : null,
        ]);
    }

    jsonSuccess(['message' => 'Assets saved.']);
}

// ─────────────────────────────────────────────
// STEP 5 — Special Instructions
// ─────────────────────────────────────────────
if ($step === 5) {
    $exists = $db->prepare('SELECT id FROM special_instructions WHERE order_id = ?');
    $exists->execute([$orderId]);

    if ($exists->fetch()) {
        $stmt = $db->prepare('UPDATE special_instructions SET
            funeral_preference=?, funeral_notes=?, organ_donation=?,
            charity_notes=?, other_instructions=? WHERE order_id=?');
    } else {
        $stmt = $db->prepare('INSERT INTO special_instructions
            (funeral_preference, funeral_notes, organ_donation, charity_notes, other_instructions, order_id)
            VALUES (?,?,?,?,?,?)');
    }

    $stmt->execute([
        sanitize($data['funeral_preference'] ?? ''),
        sanitize($data['funeral_notes'] ?? ''),
        (int)(bool)($data['organ_donation'] ?? false),
        sanitize($data['charity_notes'] ?? ''),
        sanitize($data['other_instructions'] ?? ''),
        $orderId,
    ]);

    jsonSuccess(['message' => 'Special instructions saved.']);
}

// ─────────────────────────────────────────────
// STEP 6 — File upload (multipart, not JSON)
// ─────────────────────────────────────────────
if ($step === 6) {
    // This step is handled by api/upload_document.php (multipart POST)
    jsonError('Use /api/upload_document.php for file uploads.');
}

// ─────────────────────────────────────────────
// STEP 7 — Finalise plan selection before payment
// ─────────────────────────────────────────────
if ($step === 7) {
    $plan = strtolower(sanitize($data['plan'] ?? 'standard'));
    if (!in_array($plan, ['basic', 'standard', 'premium'])) {
        jsonError('Invalid plan.');
    }
    $amount = planPrice($plan);
    $gst    = round($amount * 0.18, 2);
    $total  = $amount + $gst;

    $db->prepare('UPDATE orders SET plan=?, plan_amount=?, gst_amount=?, total_amount=? WHERE id=?')
       ->execute([$plan, $amount, $gst, $total, $orderId]);

    jsonSuccess(['plan' => $plan, 'amount' => $amount, 'gst' => $gst, 'total' => $total]);
}
