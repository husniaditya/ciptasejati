<?php
/**
 * Public Midtrans notification endpoint.
 * Configure this URL in the Midtrans dashboard, for example:
 * https://your-domain.example/dashboard/html/module/ajax/payment/midtrans_notification.php
 */
ob_start();
require_once __DIR__ . '/../../connection/conn.php';
ob_end_clean();
require_once __DIR__ . '/../../backend/payment/t_payment.php';
require_once __DIR__ . '/../../backend/payment/midtrans.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$notification = json_decode(file_get_contents('php://input'), true);
if (!is_array($notification) || empty($notification['order_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid notification payload']);
    exit;
}

if (!MidtransClient::verifySignature($notification)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid signature']);
    exit;
}

try {
    global $db1;

    $stmt = $db1->prepare(
        'SELECT total_amount FROM t_payment WHERE order_id = :order_id LIMIT 1'
    );
    $stmt->execute([':order_id' => $notification['order_id']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        exit;
    }

    if (abs((float)$transaction['total_amount'] - (float)($notification['gross_amount'] ?? 0)) > 0.01) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Amount mismatch']);
        exit;
    }

    $result = (new PaymentTransaction())->processGatewayStatus($notification);
    if (!$result['success']) {
        http_response_code(422);
    }

    echo json_encode($result);
} catch (Throwable $e) {
    error_log('Midtrans notification error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Notification processing failed']);
}
