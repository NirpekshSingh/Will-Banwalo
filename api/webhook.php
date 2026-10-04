<?php
// =============================================
// api/webhook.php
// Razorpay webhook — server-side confirmation.
// Configure in Razorpay Dashboard →
//   Webhooks → https://yourdomain.com/api/webhook.php
// Active events: payment.captured, payment.failed
// =============================================
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Webhook secret (set in Razorpay Dashboard)
define('WEBHOOK_SECRET', 'your_webhook_secret');   // ← change

header('Content-Type: application/json');

$rawBody   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

// ── Verify webhook signature ──────────────────
$expected = hash_hmac('sha256', $rawBody, WEBHOOK_SECRET);
if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

$event = json_decode($rawBody, true);
if (!$event) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$db          = getDB();
$eventName   = $event['event'] ?? '';
$payment     = $event['payload']['payment']['entity'] ?? [];
$rzpPayId    = $payment['id']       ?? '';
$rzpOrderId  = $payment['order_id'] ?? '';

// Find our order
if ($rzpOrderId) {
    $stmt = $db->prepare('SELECT id FROM orders WHERE razorpay_order_id = ?');
    $stmt->execute([$rzpOrderId]);
    $order = $stmt->fetch();
    $orderId = $order['id'] ?? null;

    if ($orderId) {
        if ($eventName === 'payment.captured') {
            $db->prepare('UPDATE orders SET payment_status="paid", status="submitted",
                           razorpay_payment_id=? WHERE id=? AND payment_status!="paid"')
               ->execute([$rzpPayId, $orderId]);
        }
        if ($eventName === 'payment.failed') {
            $db->prepare('UPDATE orders SET payment_status="failed" WHERE id=? AND payment_status="pending"')
               ->execute([$orderId]);
        }

        // Log event
        $db->prepare('INSERT INTO payment_log (order_id, event, payload) VALUES (?, ?, ?)')
           ->execute([$orderId, $eventName, $rawBody]);
    }
}

http_response_code(200);
echo json_encode(['status' => 'ok']);
