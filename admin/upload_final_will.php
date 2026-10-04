<?php
// admin/upload_final_will.php
// Admin uploads the completed/signed Will PDF for an order.
// POST: multipart — order_id, file (PDF only)
// Protected: admin session required.

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'error'=>'Unauthorised']); exit;
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json; charset=utf-8');

$orderId = (int)($_POST['order_id'] ?? 0);
if (!$orderId) { echo json_encode(['success'=>false,'error'=>'order_id required']); exit; }

// Verify order exists
$db   = getDB();
$stmt = $db->prepare('SELECT id, order_ref FROM orders WHERE id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();
if (!$order) { echo json_encode(['success'=>false,'error'=>'Order not found']); exit; }

if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success'=>false,'error'=>'No file uploaded (error code: '.($_FILES['file']['error']??'none').')']); exit;
}

$file    = $_FILES['file'];
$maxBytes = 20 * 1024 * 1024; // 20 MB for will PDFs

if ($file['size'] > $maxBytes) {
    echo json_encode(['success'=>false,'error'=>'File too large. Maximum 20 MB.']); exit;
}

// Only allow PDF for final will
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);
if ($mime !== 'application/pdf') {
    echo json_encode(['success'=>false,'error'=>'Only PDF files are accepted for the final will.']); exit;
}

// Store in uploads/{order_id}/
$destDir = rtrim(UPLOAD_DIR, '/') . '/' . $orderId . '/';
if (!is_dir($destDir)) mkdir($destDir, 0750, true);

// Generate token for customer download link (64-char hex)
$token      = bin2hex(random_bytes(32));
$storedName = 'final_will_' . $order['order_ref'] . '_' . date('Ymd') . '.pdf';
$destPath   = $destDir . $storedName;

// Remove any previous final will for this order
$prev = $db->prepare('SELECT stored_name FROM documents WHERE order_id=? AND doc_type="final_will"');
$prev->execute([$orderId]);
foreach ($prev->fetchAll() as $p) {
    $old = $destDir . basename($p['stored_name']);
    if (file_exists($old)) unlink($old);
}
$db->prepare('DELETE FROM documents WHERE order_id=? AND doc_type="final_will"')->execute([$orderId]);

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    echo json_encode(['success'=>false,'error'=>'Failed to save file. Check permissions on uploads/ directory.']); exit;
}

// Save record with token
$db->prepare('INSERT INTO documents (order_id, doc_type, original_name, stored_name, mime_type, file_size, doc_token)
              VALUES (?, "final_will", ?, ?, ?, ?, ?)')
   ->execute([$orderId, $file['name'], $storedName, $mime, $file['size'], $token]);

// Log
$db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, "final_will_uploaded", ?)')
   ->execute([$orderId, json_encode(['by'=>$_SESSION['admin_user']??'admin','file'=>$storedName])]);

// Auto-update order status to completed
$db->prepare('UPDATE orders SET status="completed" WHERE id=? AND status IN ("submitted","under_review")')
   ->execute([$orderId]);

$downloadUrl = rtrim(APP_URL, '/') . '/admin/serve_file.php?token=' . $token;

echo json_encode([
    'success'      => true,
    'message'      => 'Final will uploaded successfully.',
    'token'        => $token,
    'download_url' => $downloadUrl,
    'stored_name'  => $storedName,
]);
