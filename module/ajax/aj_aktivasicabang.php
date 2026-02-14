<?php
/**
 * Aktivasi Cabang AJAX Handler
 * Handles branch activation requests
 */

// Start output buffering to prevent any output before JSON
ob_start();

require_once("../../dashboard/html/module/connection/conn.php");

// Clear any output that might have been generated
ob_end_clean();

// Set JSON response header
header('Content-Type: application/json');

// Security: Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Security: Basic rate limiting using session
// Note: session already started in conn.php
$rateLimitKey = 'aktivasi_rate_limit';
$maxRequests = 30; // Max requests per minute
$timeWindow = 60; // 1 minute

if (!isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = ['count' => 0, 'start_time' => time()];
}

$rateLimit = &$_SESSION[$rateLimitKey];
$currentTime = time();

// Reset counter if time window passed
if ($currentTime - $rateLimit['start_time'] > $timeWindow) {
    $rateLimit['count'] = 0;
    $rateLimit['start_time'] = $currentTime;
}

$rateLimit['count']++;

if ($rateLimit['count'] > $maxRequests) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.']);
    exit;
}

// Security: CSRF token validation (skip for public endpoints)
$action = isset($_POST['action']) ? $_POST['action'] : '';
$publicActions = ['get_cabang_list', 'get_payment_methods']; // Actions that don't require CSRF

if (!in_array($action, $publicActions)) {
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!isset($_SESSION['csrf_token']) || empty($csrfToken) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
        exit;
    }
}

// Response array
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    global $db1;
    
    // Check if database connection exists
    if (!$db1) {
        throw new Exception('Database connection not available');
    }

    switch ($action) {
        case 'get_cabang_list':
            $response = getCabangList($db1);
            break;

        case 'get_payment_methods':
            $response = getPaymentMethods($db1);
            break;

        case 'create_activation':
            $response = createActivation($db1);
            break;

        case 'check_payment_status':
            $response = checkPaymentStatus($db1);
            break;

        default:
            $response['message'] = 'Invalid action';
    }

} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log('Aktivasi Cabang Error: ' . $e->getMessage());
}

echo json_encode($response);
exit;

/**
 * Sanitize input string
 * @param string $input - Raw input
 * @return string - Sanitized input
 */
function sanitizeInput($input) {
    if (!is_string($input)) {
        return '';
    }
    // Trim whitespace
    $input = trim($input);
    // Remove null bytes
    $input = str_replace(chr(0), '', $input);
    // Strip tags
    $input = strip_tags($input);
    // Limit length to prevent buffer overflow attacks
    $input = substr($input, 0, 255);
    return $input;
}

/**
 * Get list of cabang for dropdown
 */
function getCabangList($db) {
    $response = ['success' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT CABANG_KEY as id, CABANG_DESKRIPSI as name 
                FROM m_cabang 
                WHERE DELETION_STATUS = '0' 
                ORDER BY CABANG_DESKRIPSI ASC";
        
        $stmt = $db->query($sql);
        $cabangList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response['success'] = true;
        $response['data'] = $cabangList;
    } catch (Exception $e) {
        $response['message'] = 'Failed to load cabang list: ' . $e->getMessage();
    }

    return $response;
}

/**
 * Get payment methods from database grouped by category
 */
function getPaymentMethods($db) {
    $response = ['success' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT 
                    id,
                    payment_code,
                    payment_name,
                    payment_category,
                    payment_description,
                    logo_url,
                    fee_type,
                    fee_value,
                    min_amount,
                    max_amount,
                    expiry_hours,
                    account_number,
                    account_name,
                    bank_code
                FROM m_payment 
                WHERE is_active = 1 
                ORDER BY sort_order ASC, payment_name ASC";
        
        $stmt = $db->query($sql);
        $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group by category
        $grouped = [
            'transfer_bank' => [
                'name' => 'Transfer Bank',
                'icon' => 'fa-university',
                'methods' => []
            ],
            'virtual_account' => [
                'name' => 'Virtual Account',
                'icon' => 'fa-barcode',
                'methods' => []
            ],
            'ewallet' => [
                'name' => 'E-Wallet',
                'icon' => 'fa-wallet',
                'methods' => []
            ],
            'qris' => [
                'name' => 'QRIS',
                'icon' => 'fa-qrcode',
                'methods' => []
            ]
        ];

        foreach ($methods as $method) {
            $category = $method['payment_category'];
            if (isset($grouped[$category])) {
                // Default logo path if not set
                if (empty($method['logo_url'])) {
                    $method['logo_url'] = 'dashboard/html/assets/payment/' . str_replace(['bank_', 'va_'], '', $method['payment_code']) . '.svg';
                }
                $grouped[$category]['methods'][] = $method;
            }
        }

        // Remove empty categories
        $grouped = array_filter($grouped, function($cat) {
            return !empty($cat['methods']);
        });

        $response['success'] = true;
        $response['data'] = $grouped;
    } catch (Exception $e) {
        $response['message'] = 'Failed to load payment methods: ' . $e->getMessage();
    }

    return $response;
}

/**
 * Create activation (trial or subscription)
 */
function createActivation($db) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    // Sanitize and validate inputs
    $cabangId = isset($_POST['cabang_id']) ? sanitizeInput($_POST['cabang_id']) : '';
    $packageType = isset($_POST['package_type']) ? sanitizeInput($_POST['package_type']) : '';
    $paymentMethod = isset($_POST['payment_method']) ? sanitizeInput($_POST['payment_method']) : '';
    $paymentCategory = isset($_POST['payment_category']) ? sanitizeInput($_POST['payment_category']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

    // Validate required fields
    if (empty($cabangId) || empty($packageType)) {
        $response['message'] = 'Data tidak lengkap';
        return $response;
    }

    // Validate package type (whitelist)
    $allowedPackageTypes = ['trial', 'subscription'];
    if (!in_array($packageType, $allowedPackageTypes)) {
        $response['message'] = 'Tipe paket tidak valid';
        return $response;
    }

    // Validate payment category if subscription (whitelist)
    if ($packageType === 'subscription') {
        $allowedCategories = ['transfer_bank', 'virtual_account', 'ewallet', 'qris'];
        if (!in_array($paymentCategory, $allowedCategories)) {
            $response['message'] = 'Kategori pembayaran tidak valid';
            return $response;
        }
        
        // Validate amount (must be positive and reasonable)
        if ($amount <= 0 || $amount > 100000000) {
            $response['message'] = 'Jumlah pembayaran tidak valid';
            return $response;
        }
    }

    // Validate cabang exists
    $stmtCabang = $db->prepare("SELECT CABANG_KEY, CABANG_DESKRIPSI FROM m_cabang WHERE CABANG_KEY = :cabang_id AND DELETION_STATUS = '0'");
    $stmtCabang->bindParam(':cabang_id', $cabangId);
    $stmtCabang->execute();
    $cabang = $stmtCabang->fetch(PDO::FETCH_ASSOC);

    if (!$cabang) {
        $response['message'] = 'Cabang tidak ditemukan';
        return $response;
    }

    if ($packageType === 'trial') {
        // Process trial activation
        $response = processTrialActivation($db, $cabangId, $cabang['CABANG_DESKRIPSI']);
    } else {
        // Process subscription with payment
        if (empty($paymentMethod) || empty($paymentCategory)) {
            $response['message'] = 'Silakan pilih metode pembayaran';
            return $response;
        }
        $response = processSubscriptionActivation($db, $cabangId, $cabang['CABANG_DESKRIPSI'], $paymentMethod, $paymentCategory, $amount);
    }

    return $response;
}

/**
 * Process trial activation
 */
function processTrialActivation($db, $cabangId, $cabangNama) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    try {
        // Set trial period (14 days)
        $trialDays = 14;
        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime("+{$trialDays} days"));

        // Check if activation record exists
        $checkSql = "SELECT id FROM t_cabang_activation WHERE cabang_key = :cabang_id";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->bindParam(':cabang_id', $cabangId);
        $checkStmt->execute();
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing record
            $sql = "UPDATE t_cabang_activation 
                    SET activation_type = 'trial',
                        start_date = :start_date,
                        end_date = :end_date,
                        status = 'active',
                        updated_at = NOW()
                    WHERE cabang_key = :cabang_id";
        } else {
            // Insert new record
            $sql = "INSERT INTO t_cabang_activation 
                    (cabang_key, cabang_nama, activation_type, start_date, end_date, status, created_at)
                    VALUES (:cabang_id, :cabang_nama, 'trial', :start_date, :end_date, 'active', NOW())";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':cabang_id', $cabangId);
        if (!$existing) {
            $stmt->bindParam(':cabang_nama', $cabangNama);
        }
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Trial berhasil diaktifkan';
            $response['data'] = [
                'cabang_id' => $cabangId,
                'cabang_nama' => $cabangNama,
                'activation_type' => 'trial',
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
        } else {
            $response['message'] = 'Gagal mengaktifkan trial';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

/**
 * Process subscription activation with payment
 */
function processSubscriptionActivation($db, $cabangId, $cabangNama, $paymentMethod, $paymentCategory, $amount) {
    $response = ['success' => false, 'message' => '', 'data' => null];

    try {
        // Generate transaction ID
        $transactionId = 'AKT' . date('YmdHis') . rand(1000, 9999);
        // Shorten order_id to fit VARCHAR(50): ACT-{cabang_id truncated}-{timestamp}
        $cabangIdShort = substr($cabangId, 0, 20); // Max 20 chars for cabang
        $orderId = 'ACT-' . $cabangIdShort . '-' . date('ymdHis'); // Total max ~38 chars

        // Get payment info from master table
        $sqlPayment = "SELECT id, fee_type, fee_value, expiry_hours, payment_name, account_number, account_name, bank_code 
                       FROM m_payment 
                       WHERE payment_code = :payment_code AND is_active = 1";
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

        // Calculate expiry time
        $expiryHours = intval($paymentInfo['expiry_hours'] ?? 24);
        $createdAt = date('Y-m-d H:i:s');
        $expiredAt = date('Y-m-d H:i:s', strtotime("+{$expiryHours} hours"));

        // Generate payment data based on category (pass payment info from DB)
        $paymentData = generatePaymentData($paymentMethod, $paymentCategory, $amount, $orderId, $paymentInfo);

        // Insert transaction
        $sql = "INSERT INTO t_payment (
                    transaction_id,
                    order_id,
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
                    created_at
                ) VALUES (
                    :transaction_id,
                    :order_id,
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
                    :created_at
                )";

        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $paymentDataJson = json_encode($paymentData);

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':cabang_key', $cabangId);
        $stmt->bindParam(':payment_id', $paymentId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':fee_amount', $feeAmount);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':payment_data', $paymentDataJson);
        $stmt->bindParam(':expired_at', $expiredAt);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':user_agent', $userAgent);
        $stmt->bindParam(':created_at', $createdAt);

        if ($stmt->execute()) {
            $paymentTransactionId = $db->lastInsertId();
            
            // Log the transaction creation
            logPaymentAction($db, $paymentTransactionId, $transactionId, $cabangId, 'create', null, 'pending', [
                'cabang_nama' => $cabangNama,
                'payment_method' => $paymentMethod,
                'payment_category' => $paymentCategory,
                'amount' => $amount,
                'fee_amount' => $feeAmount,
                'total_amount' => $totalAmount
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Transaksi berhasil dibuat';
            $response['data'] = array_merge([
                'transaction_id' => $transactionId,
                'order_id' => $orderId,
                'cabang_id' => $cabangId,
                'cabang_nama' => $cabangNama,
                'amount' => $amount,
                'fee_amount' => $feeAmount,
                'total_amount' => $totalAmount,
                'payment_category' => $paymentCategory,
                'payment_method' => $paymentMethod,
                'expired_at' => date('d/m/Y H:i', strtotime($expiredAt))
            ], $paymentData);
        } else {
            $response['message'] = 'Gagal membuat transaksi';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

/**
 * Log payment action to t_payment_logs
 */
function logPaymentAction($db, $paymentTransactionId, $transactionId, $cabangKey, $action, $oldStatus, $newStatus, $requestData = null, $responseData = null) {
    try {
        $sql = "INSERT INTO t_payment_logs (
                    payment_transaction_id,
                    transaction_id,
                    cabang_key,
                    action,
                    old_status,
                    new_status,
                    request_data,
                    response_data,
                    ip_address,
                    user_agent,
                    created_at
                ) VALUES (
                    :payment_transaction_id,
                    :transaction_id,
                    :cabang_key,
                    :action,
                    :old_status,
                    :new_status,
                    :request_data,
                    :response_data,
                    :ip_address,
                    :user_agent,
                    NOW()
                )";
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $requestDataJson = $requestData ? json_encode($requestData) : null;
        $responseDataJson = $responseData ? json_encode($responseData) : null;
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':payment_transaction_id', $paymentTransactionId);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':cabang_key', $cabangKey);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':old_status', $oldStatus);
        $stmt->bindParam(':new_status', $newStatus);
        $stmt->bindParam(':request_data', $requestDataJson);
        $stmt->bindParam(':response_data', $responseDataJson);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':user_agent', $userAgent);
        
        $stmt->execute();
    } catch (Exception $e) {
        // Log error but don't fail the main transaction
        error_log('Failed to log payment action: ' . $e->getMessage());
    }
}

/**
 * Generate payment data based on method
 */
function generatePaymentData($method, $category, $amount, $orderId, $paymentInfo) {
    $data = [];

    switch ($category) {
        case 'transfer_bank':
            $data = [
                'bank_name' => $paymentInfo['payment_name'],
                'account_number' => $paymentInfo['account_number'],
                'account_name' => $paymentInfo['account_name']
            ];
            break;

        case 'virtual_account':
            // Generate VA number using bank_code prefix
            $prefix = $paymentInfo['bank_code'] ?? '99999';
            $vaNumber = $prefix . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $data = [
                'va_number' => $vaNumber,
                'bank_name' => $paymentInfo['payment_name']
            ];
            break;

        case 'qris':
            $qrContent = 'QRIS-ACT-' . $orderId . '-' . $amount;
            $data = [
                'qr_code_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrContent),
                'qr_content' => $qrContent
            ];
            break;

        case 'ewallet':
            $data = [
                'ewallet_name' => $paymentInfo['payment_name'],
                'redirect_url' => 'https://payment.example.com/ewallet/' . $method . '/' . $orderId,
                'deeplink_url' => $method . '://pay?order_id=' . $orderId . '&amount=' . $amount
            ];
            break;
    }

    return $data;
}

/**
 * Check payment status
 */
function checkPaymentStatus($db) {
    $response = ['success' => false, 'message' => '', 'data' => []];

    try {
        $orderId = isset($_POST['order_id']) ? sanitizeInput($_POST['order_id']) : '';

        // Validate order ID format (should start with ACT-)
        if (empty($orderId) || !preg_match('/^ACT-[a-zA-Z0-9_-]+-[0-9]+$/', $orderId)) {
            $response['message'] = 'Order ID tidak valid';
            return $response;
        }

        // Get payment from database
        $sql = "SELECT 
                    p.id,
                    p.order_id,
                    p.cabang_key,
                    p.amount,
                    p.fee_amount,
                    p.total_amount,
                    p.status,
                    p.paid_at,
                    p.expired_at,
                    p.created_at,
                    p.payment_data,
                    c.CABANG_DESKRIPSI as cabang_name,
                    pm.payment_name,
                    pm.payment_code,
                    pm.payment_category
                FROM t_payment p
                LEFT JOIN m_cabang c ON p.cabang_key = c.CABANG_KEY
                LEFT JOIN m_payment pm ON p.payment_id = pm.id
                WHERE p.order_id = :order_id
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            $response['message'] = 'Transaksi tidak ditemukan';
            return $response;
        }

        // Determine status text
        $statusText = '';
        $status = strtolower($payment['status']);
        
        switch ($status) {
            case 'pending':
                $statusText = 'Menunggu Pembayaran';
                break;
            case 'paid':
            case 'success':
            case 'settlement':
                $statusText = 'Pembayaran Berhasil';
                break;
            case 'failed':
                $statusText = 'Pembayaran Gagal';
                break;
            case 'expired':
                $statusText = 'Kedaluwarsa';
                break;
            case 'cancelled':
            case 'cancel':
                $statusText = 'Dibatalkan';
                break;
            case 'deny':
            case 'denied':
                $statusText = 'Ditolak';
                break;
            default:
                $statusText = ucfirst($status);
        }

        // Calculate validity period (1 year for subscription)
        // Since package_type is not stored in t_payment, we assume subscription for paid transactions
        $validUntil = null;
        if ($payment['paid_at']) {
            $paidDate = new DateTime($payment['paid_at']);
            $paidDate->add(new DateInterval('P1Y'));
            $validUntil = $paidDate->format('d F Y');
        }

        // Format dates
        $paidAt = $payment['paid_at'] ? date('d M Y H:i', strtotime($payment['paid_at'])) : null;
        $failedAt = in_array($status, ['failed', 'expired', 'cancelled', 'cancel', 'deny', 'denied']) 
                    ? date('d M Y H:i') : null;

        $response['success'] = true;
        $response['data'] = [
            'transaction_id' => $payment['order_id'],
            'order_id' => $payment['order_id'],
            'cabang_id' => $payment['cabang_key'],
            'cabang_name' => $payment['cabang_name'],
            'package_type' => 'Berlangganan 1 Tahun',
            'payment_method' => $payment['payment_name'],
            'payment_category' => $payment['payment_category'],
            'amount' => $payment['amount'],
            'fee_amount' => $payment['fee_amount'],
            'total_amount' => $payment['total_amount'],
            'status' => $status,
            'status_text' => $statusText,
            'paid_at' => $paidAt,
            'failed_at' => $failedAt,
            'valid_until' => $validUntil,
            'error_message' => in_array($status, ['failed', 'expired', 'cancelled', 'cancel', 'deny', 'denied']) 
                              ? 'Pembayaran tidak dapat diproses. Silakan coba kembali.' : null,
            'error_code' => null
        ];

        // Log the status check
        logPaymentAction($db, $payment['id'], $payment['order_id'], $payment['cabang_key'], 'status_checked', $status, $status, null, json_encode($response['data']));

    } catch (Exception $e) {
        $response['message'] = 'Gagal mengecek status: ' . $e->getMessage();
    }

    return $response;
}
?>
