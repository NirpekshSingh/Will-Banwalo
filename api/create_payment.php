<?php
// =============================================
// api/create_payment.php
// Creates a Razorpay order and returns the
// order_id + key_id to the frontend.
// POST JSON: { order_id }
// =============================================
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Method not allowed.', 405);

$data    = getPost();
$orderId = (int)($data['order_id'] ?? 0);
if (!$orderId) jsonError('order_id is required.');

$db = getDB();
$stmt = $db->prepare('SELECT id, order_ref, total_amount, payment_status FROM orders WHERE id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) jsonError('Order not found.', 404);
if ($order['payment_status'] === 'paid') jsonError('This order has already been paid.');

// Amount in paise (Razorpay uses smallest currency unit)
$amountPaise = (int)round($order['total_amount'] * 100);

// Call Razorpay API
$payload = json_encode([
    'amount'          => $amountPaise,
    'currency'        => 'INR',
    'receipt'         => $order['order_ref'],
    'notes'           => [
        'order_id'  => $orderId,
        'order_ref' => $order['order_ref'],
        'source'    => 'Will Banwalo',
    ],
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_USERPWD        => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr) jsonError('Payment gateway connection failed: ' . $curlErr, 502);
if ($httpCode !== 200) {
    $rzp = json_decode($response, true);
    jsonError('Razorpay error: ' . ($rzp['error']['description'] ?? 'Unknown error'), 502);
}

$rzpOrder = json_decode($response, true);

// Store razorpay_order_id
$db->prepare('UPDATE orders SET razorpay_order_id = ? WHERE id = ?')
   ->execute([$rzpOrder['id'], $orderId]);

// Log
$db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, "rzp_order_created", ?)')
   ->execute([$orderId, json_encode($rzpOrder)]);

jsonSuccess([
    'razorpay_order_id' => $rzpOrder['id'],
    'amount'            => $amountPaise,
    'currency'          => 'INR',
    'key_id'            => RAZORPAY_KEY_ID,
    'order_ref'         => $order['order_ref'],
    'prefill'           => getPrefill($db, $orderId),
]);

// ── Prefill helper ────────────────────────────
function getPrefill(PDO $db, int $orderId): array {
    $s = $db->prepare('SELECT full_name, mobile, email FROM personal_details WHERE order_id = ?');
    $s->execute([$orderId]);
    $p = $s->fetch();
    return $p ? [
        'name'    => $p['full_name'],
        'contact' => $p['mobile'],
        'email'   => $p['email'] ?? '',
    ] : [];
}
