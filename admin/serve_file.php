<?php
// admin/serve_file.php
// Securely serves uploaded documents to authenticated admins.
// Also serves final_will files to customers (by signed token).
// Usage: admin/serve_file.php?doc_id=XX         (admin, session-protected)
//        admin/serve_file.php?token=XXXX         (customer download link)

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$db = getDB();

// ── Token-based access (customer download of final will) ──────
if (!empty($_GET['token'])) {
    $token = preg_replace('/[^a-f0-9]/', '', $_GET['token']);
    if (strlen($token) !== 64) { http_response_code(403); exit('Invalid token.'); }

    $stmt = $db->prepare('SELECT d.stored_name, d.original_name, d.mime_type, d.order_id
        FROM documents d
        JOIN orders o ON o.id = d.order_id
        WHERE d.doc_token = ? AND d.doc_type = "final_will" AND o.payment_status = "paid"');
    $stmt->execute([$token]);
    $doc = $stmt->fetch();
    if (!$doc) { http_response_code(404); exit('File not found or link expired.'); }
    serveFile($doc);
    exit;
}

// ── Admin session access ──────────────────────────────────────
if (empty($_SESSION['admin_id'])) {
    http_response_code(401); exit('Unauthorised.');
}

$docId = (int)($_GET['doc_id'] ?? 0);
if (!$docId) { http_response_code(400); exit('doc_id required.'); }

$stmt = $db->prepare('SELECT stored_name, original_name, mime_type, order_id, doc_type FROM documents WHERE id = ?');
$stmt->execute([$docId]);
$doc = $stmt->fetch();
if (!$doc) { http_response_code(404); exit('Document not found.'); }

serveFile($doc);

// ─────────────────────────────────────────────
function serveFile(array $doc): void {
    $orderId    = $doc['order_id'];
    $stored     = preg_replace('/[^a-zA-Z0-9_.\-]/', '', basename($doc['stored_name']));
    $path       = rtrim(UPLOAD_DIR, '/') . '/' . $orderId . '/' . $stored;

    if (!file_exists($path)) {
        http_response_code(404);
        exit('File not found on disk: ' . htmlspecialchars($stored));
    }

    $mime = $doc['mime_type'] ?: mime_content_type($path) ?: 'application/octet-stream';

    // Inline for images and PDFs, download for others
    $inline   = in_array($mime, ['image/jpeg','image/png','image/gif','image/webp','application/pdf']);
    $filename = basename($doc['original_name'] ?: $stored);

    header('Content-Type: ' . $mime);
    header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . addslashes($filename) . '"');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: private, no-store');
    header('X-Content-Type-Options: nosniff');

    readfile($path);
    exit;
}
