<?php
// =============================================
// api/upload_document.php
// Handles multipart file uploads for Step 6.
// POST: order_id, doc_type, file (file field)
// =============================================
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Method not allowed.', 405);

$orderId = (int)($_POST['order_id'] ?? 0);
$docType = sanitize($_POST['doc_type'] ?? '');

if (!$orderId) jsonError('order_id is required.');
if (!$docType) jsonError('doc_type is required.');

// Verify order
$db = getDB();
$row = $db->prepare('SELECT id FROM orders WHERE id = ?');
$row->execute([$orderId]);
if (!$row->fetch()) jsonError('Order not found.', 404);

// Validate upload
if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    jsonError('No file uploaded or upload error: ' . ($_FILES['file']['error'] ?? 'none'));
}

$file     = $_FILES['file'];
$maxBytes = MAX_FILE_MB * 1024 * 1024;

if ($file['size'] > $maxBytes) {
    jsonError('File too large. Maximum size is ' . MAX_FILE_MB . ' MB.');
}

$allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);

if (!in_array($mime, $allowed_mime)) {
    jsonError('Invalid file type. Allowed: JPG, PNG, PDF.');
}

// Sanitise filename and store with UUID
$ext         = pathinfo($file['name'], PATHINFO_EXTENSION);
$storedName  = sprintf('%s_%s_%s.%s', $orderId, $docType, bin2hex(random_bytes(8)), strtolower($ext));
$destDir     = rtrim(UPLOAD_DIR, '/') . '/' . $orderId . '/';

if (!is_dir($destDir)) {
    mkdir($destDir, 0750, true);
}

$destPath = $destDir . $storedName;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    jsonError('Failed to save file. Check upload directory permissions.', 500);
}

// Save record
$stmt = $db->prepare('INSERT INTO documents (order_id, doc_type, original_name, stored_name, mime_type, file_size)
                       VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([
    $orderId,
    $docType,
    sanitize($file['name']),
    $storedName,
    $mime,
    $file['size'],
]);

jsonSuccess(['message' => 'Document uploaded.', 'stored_name' => $storedName]);
