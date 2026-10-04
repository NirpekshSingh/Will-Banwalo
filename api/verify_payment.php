<?php
// =============================================
// api/verify_payment.php
// Verifies Razorpay signature after payment.
// POST JSON:
//   { order_id, razorpay_order_id,
//     razorpay_payment_id, razorpay_signature }
// =============================================
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Method not allowed.', 405);

$data = getPost();

$orderId          = (int)($data['order_id'] ?? 0);
$rzpOrderId       = sanitize($data['razorpay_order_id']  ?? '');
$rzpPaymentId     = sanitize($data['razorpay_payment_id'] ?? '');
$rzpSignature     = sanitize($data['razorpay_signature']  ?? '');

if (!$orderId || !$rzpOrderId || !$rzpPaymentId || !$rzpSignature) {
    jsonError('Missing required payment fields.');
}

// ── Verify HMAC-SHA256 signature ──────────────
$expectedSignature = hash_hmac(
    'sha256',
    $rzpOrderId . '|' . $rzpPaymentId,
    RAZORPAY_KEY_SECRET
);

if (!hash_equals($expectedSignature, $rzpSignature)) {
    $db = getDB();
    $db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, "signature_mismatch", ?)')
       ->execute([$orderId, json_encode($data)]);
    jsonError('Payment verification failed. Signature mismatch.', 400);
}

// ── Update order ──────────────────────────────
$db = getDB();

$stmt = $db->prepare('SELECT id, order_ref, payment_status FROM orders WHERE id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) jsonError('Order not found.', 404);
if ($order['payment_status'] === 'paid') {
    // Idempotent — already marked paid
    jsonSuccess(['order_ref' => $order['order_ref'], 'message' => 'Already paid.']);
}

$db->prepare('UPDATE orders SET
    razorpay_payment_id = ?,
    razorpay_signature  = ?,
    payment_status      = "paid",
    status              = "submitted"
    WHERE id = ?')
   ->execute([$rzpPaymentId, $rzpSignature, $orderId]);

// Log success
$db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, "payment_verified", ?)')
   ->execute([$orderId, json_encode([
       'razorpay_order_id'   => $rzpOrderId,
       'razorpay_payment_id' => $rzpPaymentId,
   ])]);

// ── Send confirmation email ───────────────────
// Non-fatal: a failed email must never block the payment response.
try {
    require_once __DIR__ . '/../send_confirmation_email.php';
    $emailSent = send_will_confirmation_email($db, $orderId);

    $db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, "confirmation_email", ?)')
       ->execute([$orderId, json_encode(['sent' => $emailSent])]);

    if (!$emailSent) {
        error_log('WillBanwalo: confirmation email failed for order_id=' . $orderId);
    }
} catch (Throwable $e) {
    error_log('WillBanwalo: email exception for order_id=' . $orderId . ' — ' . $e->getMessage());
}

jsonSuccess([
    'order_ref' => $order['order_ref'],
    'message'   => 'Payment successful. Will submitted.',
]);
