<?php
/**
 * Payment Gateway AJAX Handler
 * Handles all payment-related AJAX requests
 */

require_once ("../../connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$USER_KEY = $_SESSION["LOGINKEY_CS"];
$USER_ID = $_SESSION["LOGINIDUS_CS"];

// Set JSON response header
header('Content-Type: application/json');

// Get action from request
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Response array
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // Use global database connection from conn.php
    global $db1;

    switch ($action) {
        case 'create_transaction':
            $response = createTransaction($db1);
            break;

        case 'check_status':
            $response = checkTransactionStatus($db1);
            break;

        case 'get_history':
            $response = getPaymentHistory($db1);
            break;

        case 'get_detail':
            $response = getTransactionDetail($db1);
            break;

        case 'callback':
            $response = handleCallback($db1);
            break;

        case 'mark_expired':
            $response = markSingleTransactionExpired($db1);
            break;

        default:
            $response['message'] = 'Invalid action';
            break;
    }

} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;

/**
 * Create new payment transaction
 */
function createTransaction($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    // Validate required fields
    $orderId = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $paymentCategory = isset($_POST['payment_category']) ? trim($_POST['payment_category']) : '';

    if (empty($orderId) || $amount <= 0 || empty($paymentMethod)) {
        $response['message'] = 'Data tidak lengkap';
        return $response;
    }

    // Generate unique transaction ID
    $transactionId = 'TRX' . date('YmdHis') . rand(1000, 9999);

    // Prepare payment data based on category
    $paymentData = [];

    switch ($paymentCategory) {
        case 'transfer_bank':
            $paymentData = generateBankTransferData($paymentMethod, $amount);
            break;

        case 'virtual_account':
            $paymentData = generateVirtualAccountData($paymentMethod, $amount, $orderId, $userId ?? '');
            break;

        case 'ewallet':
            $paymentData = generateEwalletData($paymentMethod, $amount, $orderId);
            break;

        case 'qris':
            $paymentData = generateQrisData($amount, $orderId);
            break;
    }

    // Get payment_id from m_payment table
    $sqlPayment = "SELECT id, fee_type, fee_value, expiry_hours FROM m_payment WHERE payment_code = :payment_code AND is_active = 1";
    $stmtPayment = $db->prepare($sqlPayment);
    $stmtPayment->bindParam(':payment_code', $paymentMethod);
    $stmtPayment->execute();
    $paymentInfo = $stmtPayment->fetch(PDO::FETCH_ASSOC);

    if (!$paymentInfo) {
        $response['message'] = 'Metode pembayaran tidak ditemukan atau tidak aktif';
        return $response;
    }

    $paymentId = $paymentInfo['id'];
    
    // Calculate fee
    $feeAmount = 0;
    if ($paymentInfo['fee_type'] === 'fixed') {
        $feeAmount = floatval($paymentInfo['fee_value']);
    } else if ($paymentInfo['fee_type'] === 'percentage') {
        $feeAmount = $amount * floatval($paymentInfo['fee_value']) / 100;
    }
    $totalAmount = $amount + $feeAmount;

    // Calculate expiry time from master table - use MySQL directly to avoid timezone issues
    $expiryHours = intval($paymentInfo['expiry_hours'] ?? 24);
    $stmtExpiry = $db->query("SELECT NOW() as created_at, DATE_ADD(NOW(), INTERVAL {$expiryHours} HOUR) as expired_at");
    $times = $stmtExpiry->fetch(PDO::FETCH_ASSOC);
    $createdAt = $times['created_at'];
    $expiredAt = $times['expired_at'];

    // Prepare JSON data
    $paymentDataJson = json_encode($paymentData);

    // Insert transaction to database
    $sql = "INSERT INTO t_payment (
                transaction_id,
                order_id,
                user_id,
                cabang_key,
                payment_id,
                amount,
                fee_amount,
                total_amount,
                status,
                payment_data,
                expired_at,
                ip_address,
                user_agent,
                created_at,
                created_by
            ) VALUES (
                :transaction_id,
                :order_id,
                :user_id,
                :cabang_key,
                :payment_id,
                :amount,
                :fee_amount,
                :total_amount,
                'pending',
                :payment_data,
                :expired_at,
                :ip_address,
                :user_agent,
                :created_at,
                :created_by
            )";

    $userId = $_SESSION["LOGINIDUS_CS"] ?? null;
    $cabangKey = $_SESSION["LOGINCAB_CS"] ?? null;
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':transaction_id', $transactionId);
    $stmt->bindParam(':order_id', $orderId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':cabang_key', $cabangKey);
    $stmt->bindParam(':payment_id', $paymentId);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':fee_amount', $feeAmount);
    $stmt->bindParam(':total_amount', $totalAmount);
    $stmt->bindParam(':payment_data', $paymentDataJson);
    $stmt->bindParam(':expired_at', $expiredAt);
    $stmt->bindParam(':ip_address', $ipAddress);
    $stmt->bindParam(':user_agent', $userAgent);
    $stmt->bindParam(':created_at', $createdAt);
    $stmt->bindParam(':created_by', $userId);

    if ($stmt->execute()) {
        // Merge response data
        $responseData = array_merge([
            'transaction_id' => $transactionId,
            'order_id' => $orderId,
            'amount' => $amount,
            'fee_amount' => $feeAmount,
            'total_amount' => $totalAmount,
            'expired_at' => date('d/m/Y H:i', strtotime($expiredAt))
        ], $paymentData);

        $response['success'] = true;
        $response['message'] = 'Transaksi berhasil dibuat';
        $response['data'] = $responseData;
    } else {
        $response['message'] = 'Gagal membuat transaksi';
    }

    return $response;
}

/**
 * Generate bank transfer data
 */
function generateBankTransferData($method, $amount) {
    $bankAccounts = [
        'bank_bca' => [
            'bank_name' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT Cipta Sejati'
        ],
        'bank_bni' => [
            'bank_name' => 'Bank BNI',
            'account_number' => '0987654321',
            'account_name' => 'PT Cipta Sejati'
        ],
        'bank_bri' => [
            'bank_name' => 'Bank BRI',
            'account_number' => '1122334455',
            'account_name' => 'PT Cipta Sejati'
        ],
        'bank_mandiri' => [
            'bank_name' => 'Bank Mandiri',
            'account_number' => '5544332211',
            'account_name' => 'PT Cipta Sejati'
        ]
    ];

    return isset($bankAccounts[$method]) ? $bankAccounts[$method] : [];
}

/**
 * Generate virtual account data
 * Custom VA format: [Bank Prefix][Company Code][User ID Hash][Unique Number]
 */
function generateVirtualAccountData($method, $amount, $orderId = '', $userId = '') {
    // Bank prefixes (company codes registered with each bank)
    $bankConfig = [
        'va_bca' => [
            'prefix' => '70012',      // BCA company code
            'bank_name' => 'Bank BCA',
            'bank_code' => '014'
        ],
        'va_bni' => [
            'prefix' => '8808',       // BNI company code
            'bank_name' => 'Bank BNI',
            'bank_code' => '009'
        ],
        'va_bri' => [
            'prefix' => '26215',      // BRI company code
            'bank_name' => 'Bank BRI',
            'bank_code' => '002'
        ],
        'va_mandiri' => [
            'prefix' => '89508',      // Mandiri company code
            'bank_name' => 'Bank Mandiri',
            'bank_code' => '008'
        ],
        'va_permata' => [
            'prefix' => '8214',       // Permata company code
            'bank_name' => 'Bank Permata',
            'bank_code' => '013'
        ]
    ];

    $config = isset($bankConfig[$method]) ? $bankConfig[$method] : [
        'prefix' => '99999',
        'bank_name' => 'Unknown Bank',
        'bank_code' => '000'
    ];

    // Generate unique VA number
    // Format: [Prefix][YYMMDD][Random 4 digits] = Total ~16 digits
    $dateCode = date('ymd');
    $uniqueCode = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Option 1: Simple sequential-like VA
    $vaNumber = $config['prefix'] . $dateCode . $uniqueCode;
    
    // Option 2: Include user identifier (last 4 chars of user_id hash)
    // $userHash = substr(md5($userId), 0, 4);
    // $vaNumber = $config['prefix'] . $userHash . $dateCode . $uniqueCode;
    
    // Option 3: Include order reference
    // $orderHash = substr(preg_replace('/[^0-9]/', '', $orderId), -4);
    // $vaNumber = $config['prefix'] . $orderHash . $uniqueCode;

    return [
        'va_number' => $vaNumber,
        'bank_name' => $config['bank_name'],
        'bank_code' => $config['bank_code'],
        'account_name' => 'PT Cipta Sejati',
        'how_to_pay' => getVAPaymentInstructions($method, $vaNumber)
    ];
}

/**
 * Get VA payment instructions by bank
 */
function getVAPaymentInstructions($method, $vaNumber) {
    $instructions = [
        'va_bca' => [
            'atm' => [
                'Masukkan kartu ATM dan PIN BCA',
                'Pilih menu "Transaksi Lainnya"',
                'Pilih "Transfer" > "Ke Rek BCA Virtual Account"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi detail pembayaran dan selesaikan transaksi'
            ],
            'mobile' => [
                'Login ke BCA Mobile',
                'Pilih "m-Transfer" > "BCA Virtual Account"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan masukkan PIN'
            ],
            'ibanking' => [
                'Login ke KlikBCA',
                'Pilih "Transfer Dana" > "Transfer ke BCA Virtual Account"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dengan KeyBCA'
            ]
        ],
        'va_bni' => [
            'atm' => [
                'Masukkan kartu ATM dan PIN BNI',
                'Pilih menu "Menu Lainnya"',
                'Pilih "Transfer" > "Virtual Account Billing"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan selesaikan transaksi'
            ],
            'mobile' => [
                'Login ke BNI Mobile Banking',
                'Pilih "Transfer" > "Virtual Account Billing"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan masukkan password'
            ]
        ],
        'va_bri' => [
            'atm' => [
                'Masukkan kartu ATM dan PIN BRI',
                'Pilih "Transaksi Lain" > "Pembayaran"',
                'Pilih "Lainnya" > "BRIVA"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan selesaikan transaksi'
            ],
            'mobile' => [
                'Login ke BRImo',
                'Pilih "BRIVA"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan masukkan PIN'
            ]
        ],
        'va_mandiri' => [
            'atm' => [
                'Masukkan kartu ATM dan PIN Mandiri',
                'Pilih "Bayar/Beli"',
                'Pilih "Lainnya" > "Multi Payment"',
                'Masukkan kode perusahaan dan nomor VA: ' . $vaNumber,
                'Konfirmasi dan selesaikan transaksi'
            ],
            'mobile' => [
                'Login ke Livin\' by Mandiri',
                'Pilih "Bayar" > "Multipayment"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan masukkan PIN'
            ]
        ],
        'va_permata' => [
            'atm' => [
                'Masukkan kartu ATM dan PIN Permata',
                'Pilih "Transaksi Lainnya"',
                'Pilih "Pembayaran" > "Virtual Account"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan selesaikan transaksi'
            ],
            'mobile' => [
                'Login ke PermataMobile X',
                'Pilih "Pembayaran" > "Virtual Account"',
                'Masukkan nomor VA: ' . $vaNumber,
                'Konfirmasi dan masukkan PIN'
            ]
        ]
    ];

    return isset($instructions[$method]) ? $instructions[$method] : [];
}

/**
 * Generate e-wallet data
 */
function generateEwalletData($method, $amount, $orderId) {
    // In production, this would call the actual e-wallet API
    // For demo purposes, we return mock data
    
    $baseUrl = 'https://payment.example.com/';
    
    return [
        'redirect_url' => $baseUrl . 'ewallet/' . $method . '/' . $orderId,
        'deeplink_url' => $method . '://pay?order_id=' . $orderId . '&amount=' . $amount
    ];
}

/**
 * Generate QRIS data
 */
function generateQrisData($amount, $orderId) {
    // In production, this would call the actual QRIS API
    // For demo purposes, we return a placeholder QR code
    
    // Using a placeholder QR code generator
    $qrContent = 'QRIS-' . $orderId . '-' . $amount;
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrContent);
    
    return [
        'qr_code_url' => $qrCodeUrl,
        'qr_content' => $qrContent
    ];
}

/**
 * Check transaction status
 */
function checkTransactionStatus($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    $transactionId = isset($_POST['transaction_id']) ? trim($_POST['transaction_id']) : '';

    if (empty($transactionId)) {
        $response['message'] = 'Transaction ID tidak valid';
        return $response;
    }

    $sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category 
            FROM t_payment t 
            INNER JOIN m_payment m ON t.payment_id = m.id 
            WHERE t.transaction_id = :transaction_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':transaction_id', $transactionId);
    $stmt->execute();

    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        // Check if transaction has expired
        if ($transaction['status'] === 'pending' && strtotime($transaction['expired_at']) < time()) {
            // Update status to expired
            $updateSql = "UPDATE t_payment SET status = 'expired', updated_at = NOW() WHERE transaction_id = :transaction_id";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->bindParam(':transaction_id', $transactionId);
            $updateStmt->execute();
            
            $transaction['status'] = 'expired';
        }

        $response['success'] = true;
        $response['data'] = [
            'transaction_id' => $transaction['transaction_id'],
            'order_id' => $transaction['order_id'],
            'amount' => $transaction['amount'],
            'fee_amount' => $transaction['fee_amount'],
            'total_amount' => $transaction['total_amount'],
            'status' => $transaction['status'],
            'message' => getStatusMessage($transaction['status'])
        ];
    } else {
        $response['message'] = 'Transaksi tidak ditemukan';
    }

    return $response;
}

/**
 * Get status message
 */
function getStatusMessage($status) {
    $messages = [
        'pending' => 'Menunggu pembayaran',
        'success' => 'Pembayaran berhasil',
        'settlement' => 'Pembayaran berhasil',
        'capture' => 'Pembayaran berhasil',
        'failed' => 'Pembayaran gagal',
        'expired' => 'Pembayaran kadaluarsa',
        'cancel' => 'Pembayaran dibatalkan',
        'deny' => 'Pembayaran ditolak'
    ];

    return isset($messages[$status]) ? $messages[$status] : 'Status tidak diketahui';
}

/**
 * Get payment history
 */
function getPaymentHistory($db) {
    $response = ['success' => false, 'message' => '', 'data' => []];

    $orderId = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';

    $sql = "SELECT 
                t.transaction_id,
                t.order_id,
                t.amount,
                m.payment_code,
                m.payment_name,
                m.payment_category,
                t.status,
                t.created_at,
                t.updated_at
            FROM t_payment t
            INNER JOIN m_payment m ON t.payment_id = m.id";

    // If order_id is provided, filter by it
    if (!empty($orderId)) {
        $sql .= " WHERE t.order_id = :order_id";
    }

    $sql .= " ORDER BY t.created_at DESC LIMIT 50";

    $stmt = $db->prepare($sql);
    
    if (!empty($orderId)) {
        $stmt->bindParam(':order_id', $orderId);
    }
    
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data
    foreach ($transactions as &$transaction) {
        $transaction['created_at'] = date('d/m/Y H:i', strtotime($transaction['created_at']));
        $transaction['payment_method'] = $transaction['payment_name'];
    }

    $response['success'] = true;
    $response['data'] = $transactions;

    return $response;
}

/**
 * Format payment method name
 */
function formatPaymentMethod($method) {
    $methods = [
        'bank_bca' => 'Transfer BCA',
        'bank_bni' => 'Transfer BNI',
        'bank_bri' => 'Transfer BRI',
        'bank_mandiri' => 'Transfer Mandiri',
        'va_bca' => 'VA BCA',
        'va_bni' => 'VA BNI',
        'va_bri' => 'VA BRI',
        'va_mandiri' => 'VA Mandiri',
        'va_permata' => 'VA Permata',
        'gopay' => 'GoPay',
        'ovo' => 'OVO',
        'dana' => 'DANA',
        'shopeepay' => 'ShopeePay',
        'linkaja' => 'LinkAja',
        'qris' => 'QRIS'
    ];

    return isset($methods[$method]) ? $methods[$method] : $method;
}

/**
 * Get transaction detail
 */
function getTransactionDetail($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    $transactionId = isset($_POST['transaction_id']) ? trim($_POST['transaction_id']) : '';

    if (empty($transactionId)) {
        $response['message'] = 'Transaction ID tidak valid';
        return $response;
    }

    $sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category 
            FROM t_payment t 
            INNER JOIN m_payment m ON t.payment_id = m.id 
            WHERE t.transaction_id = :transaction_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':transaction_id', $transactionId);
    $stmt->execute();

    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        $response['success'] = true;
        $response['data'] = [
            'transaction_id' => $transaction['transaction_id'],
            'order_id' => $transaction['order_id'],
            'amount' => $transaction['amount'],
            'fee_amount' => $transaction['fee_amount'],
            'total_amount' => $transaction['total_amount'],
            'payment_method' => $transaction['payment_name'],
            'payment_category' => ucwords(str_replace('_', ' ', $transaction['payment_category'])),
            'status' => $transaction['status'],
            'payment_data' => json_decode($transaction['payment_data'], true),
            'expired_at' => $transaction['expired_at'] ? date('d/m/Y H:i:s', strtotime($transaction['expired_at'])) : null,
            'paid_at' => $transaction['paid_at'] ? date('d/m/Y H:i:s', strtotime($transaction['paid_at'])) : null,
            'created_at' => date('d/m/Y H:i:s', strtotime($transaction['created_at'])),
            'updated_at' => date('d/m/Y H:i:s', strtotime($transaction['updated_at']))
        ];
    } else {
        $response['message'] = 'Transaksi tidak ditemukan';
    }

    return $response;
}

/**
 * Handle payment callback from payment gateway
 * This function would be called by the payment gateway when payment status changes
 */
function handleCallback($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    // Get callback data (usually from JSON body for webhooks)
    $callbackData = json_decode(file_get_contents('php://input'), true);

    if (empty($callbackData)) {
        // Try to get from POST
        $callbackData = $_POST;
    }

    $transactionId = isset($callbackData['transaction_id']) ? $callbackData['transaction_id'] : '';
    $status = isset($callbackData['status']) ? $callbackData['status'] : '';

    if (empty($transactionId) || empty($status)) {
        $response['message'] = 'Invalid callback data';
        return $response;
    }

    // Update transaction status
    $callbackDataJson = json_encode($callbackData);
    $sql = "UPDATE t_payment 
            SET status = :status, 
                callback_data = :callback_data,
                paid_at = CASE WHEN :status2 IN ('success', 'settlement', 'capture') THEN NOW() ELSE paid_at END,
                updated_at = NOW() 
            WHERE transaction_id = :transaction_id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':status2', $status);
    $stmt->bindParam(':callback_data', $callbackDataJson);
    $stmt->bindParam(':transaction_id', $transactionId);

    if ($stmt->execute()) {
        // If payment successful, update the related order
        if (in_array($status, ['success', 'settlement', 'capture'])) {
            updateOrderPaymentStatus($db, $transactionId);
        }

        $response['success'] = true;
        $response['message'] = 'Callback processed successfully';
    } else {
        $response['message'] = 'Failed to process callback';
    }

    return $response;
}

/**
 * Update order payment status after successful payment
 */
function updateOrderPaymentStatus($db, $transactionId) {
    // Get order_id from transaction
    $sql = "SELECT order_id, total_amount FROM t_payment WHERE transaction_id = :transaction_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':transaction_id', $transactionId);
    $stmt->execute();
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        // Update your order table here
        // This is an example - adjust according to your order table structure
        $updateSql = "UPDATE orders 
                      SET payment_status = 'paid', 
                          paid_amount = :amount,
                          paid_at = NOW(),
                          updated_at = NOW() 
                      WHERE order_id = :order_id";
        
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->bindParam(':amount', $transaction['total_amount']);
        $updateStmt->bindParam(':order_id', $transaction['order_id']);
        $updateStmt->execute();
    }
}

/**
 * Mark a single transaction as expired
 * Called from JavaScript when countdown reaches zero
 */
function markSingleTransactionExpired($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    $transactionId = isset($_POST['transaction_id']) ? trim($_POST['transaction_id']) : '';

    if (empty($transactionId)) {
        $response['message'] = 'Transaction ID is required';
        return $response;
    }

    // Only update if status is still pending and expired_at has passed
    $sql = "UPDATE t_payment 
            SET status = 'expired',
                updated_at = NOW()
            WHERE transaction_id = :transaction_id 
            AND status = 'pending'
            AND expired_at < NOW()";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':transaction_id', $transactionId);

    if ($stmt->execute()) {
        $affectedRows = $stmt->rowCount();
        
        if ($affectedRows > 0) {
            $response['success'] = true;
            $response['message'] = 'Transaction marked as expired';
            $response['data'] = ['transaction_id' => $transactionId, 'status' => 'expired'];
        } else {
            // Transaction might already be expired, successful, or not yet expired
            $response['success'] = true;
            $response['message'] = 'No update needed';
        }
    } else {
        $response['message'] = 'Failed to update transaction status';
    }

    return $response;
}
?>