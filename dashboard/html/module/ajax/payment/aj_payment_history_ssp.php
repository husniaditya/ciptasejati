<?php
/**
 * Payment History - Server-Side Processing for DataTables
 * Returns payment history data for DataTables with server-side pagination
 */

require_once("../../connection/conn.php");
require_once("../../backend/payment/t_payment.php");

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-expire pending transactions that have passed their expiry time
try {
    $paymentHandler = new PaymentTransaction();
    $paymentHandler->markExpiredTransactions();
} catch (Exception $e) {
    // Log error but continue - don't break the SSP response
    error_log('Failed to mark expired transactions: ' . $e->getMessage());
}

$USER_KEY = $_SESSION["LOGINKEY_CS"] ?? '';
$USER_ID = $_SESSION["LOGINIDUS_CS"] ?? '';
$USER_CABANG = $_SESSION["LOGINCAB_CS"] ?? '';

// Safe HTML escape that tolerates nulls
if (!function_exists('h')) {
    function h($v) {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$start = max(0, $start);
if ($length < 0) { $length = 1000; }
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
$orderId = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';

// Column mapping (index 0 is No, non-orderable)
$columns = [
    't.created_at',       // 1 - Tanggal
    't.transaction_id',   // 2 - Transaction ID
    't.order_id',         // 3 - Order ID
    'm.payment_name',     // 4 - Metode
    't.amount',           // 5 - Jumlah
    't.expired_at',       // 6 - Tanggal Kadaluarsa
    't.status'            // 7 - Status
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 1 || $orderColIdx > count($columns)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx - 1] . ' ' . $orderDir;

$fromTable = " FROM t_payment t INNER JOIN m_payment m ON t.payment_id = m.id ";

$where = [];

// Filter by current user's cabang (branch) - more reliable than user_id
if ($USER_CABANG !== '') {
    $where[] = "t.cabang_key = '" . str_replace("'", "''", $USER_CABANG) . "'";
}

// Search filter
if ($searchValue !== '') {
    $sv = str_replace("'", "''", $searchValue);
    $where[] = "(t.transaction_id LIKE CONCAT('%','$sv','%')
              OR t.order_id LIKE CONCAT('%','$sv','%')
              OR m.payment_name LIKE CONCAT('%','$sv','%')
              OR m.payment_code LIKE CONCAT('%','$sv','%')
              OR t.status LIKE CONCAT('%','$sv','%')
              OR t.amount LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// Total records (without search filter, but with cabang filter)
$baseWhere = '';
$baseWhereConditions = [];
if ($USER_CABANG !== '') {
    $baseWhereConditions[] = "t.cabang_key = '" . str_replace("'", "''", $USER_CABANG) . "'";
}
if (!empty($baseWhereConditions)) {
    $baseWhere = " WHERE " . implode(' AND ', $baseWhereConditions);
}
$sqlTotal = "SELECT COUNT(*) AS cnt $fromTable $baseWhere";
$totalRes = GetQuery($sqlTotal);
$totalRow = $totalRes ? $totalRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsTotal = isset($totalRow['cnt']) ? (int)$totalRow['cnt'] : 0;

// Filtered records count
$sqlFiltered = "SELECT COUNT(*) AS cnt $fromTable $whereSql";
$fRes = GetQuery($sqlFiltered);
$fRow = $fRes ? $fRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsFiltered = isset($fRow['cnt']) ? (int)$fRow['cnt'] : 0;

// Main query with pagination
$select = "SELECT 
                t.transaction_id,
                t.order_id,
                t.amount,
                t.fee_amount,
                t.total_amount,
                m.payment_code,
                m.payment_name,
                m.payment_category,
                t.status,
                t.created_at,
                t.updated_at,
                t.expired_at
            $fromTable $whereSql ORDER BY $orderBy LIMIT $start, $length";

// DEBUG: Uncomment to see query and session values
// header('Content-Type: application/json');
// echo json_encode([
//     'debug' => true,
//     'USER_ID' => $USER_ID,
//     'USER_CABANG' => $USER_CABANG,
//     'whereSql' => $whereSql,
//     'sqlTotal' => $sqlTotal,
//     'select' => $select
// ]);
// exit;

try {
    $rowsRes = GetQuery($select);
} catch (Throwable $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Query error: ' . $e->getMessage()
    ]);
    exit;
}

// Status badge mapping - Using Bootstrap 3 label classes
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="label label-warning">Menunggu</span>',
        'success' => '<span class="label label-success">Berhasil</span>',
        'settlement' => '<span class="label label-success">Berhasil</span>',
        'failed' => '<span class="label label-danger">Gagal</span>',
        'expired' => '<span class="label label-default">Kadaluarsa</span>',
        'cancel' => '<span class="label label-default">Dibatalkan</span>'
    ];
    return $badges[$status] ?? '<span class="label label-default">' . h($status) . '</span>';
}

// Format currency
function formatCurrency($amount) {
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

$data = [];
$rowNum = $start + 1;

while ($r = $rowsRes->fetch(PDO::FETCH_ASSOC)) {
    // Format date
    $formattedDate = date('d/m/Y H:i', strtotime($r['created_at']));
    
    // Payment method name from master table
    $methodName = h($r['payment_name']);
    
    // Format amount - use total_amount (includes fee) instead of base amount
    $formattedAmount = formatCurrency($r['total_amount'] ?? $r['amount']);
    
    // Format expiry date
    $formattedExpiry = $r['expired_at'] ? date('d/m/Y H:i', strtotime($r['expired_at'])) : '-';
    
    // Status column: show countdown when pending, otherwise show status badge
    $statusHtml = '';
    if ($r['status'] === 'pending' && $r['expired_at']) {
        // Pending: show countdown timer
        $expiredAtTimestamp = strtotime($r['expired_at']);
        $statusHtml = '<div class="text-center">';
        $statusHtml .= '<span class="countdown-timer" data-expired="' . date('Y-m-d H:i:s', $expiredAtTimestamp) . '"></span>';
        $statusHtml .= '</div>';
    } else {
        // Not pending: show status badge
        $statusHtml = '<div class="text-center">' . getStatusBadge($r['status']) . '</div>';
    }
    
    // Action button - using data attribute to avoid onclick escaping issues
    $transId = h($r['transaction_id']);
    $actionHtml = '<button type="button" class="btn btn-xs btn-info btn-view-payment-detail" data-transaction-id="' . $transId . '">'
                . '<i class="fa fa-eye"></i> Detail'
                . '</button>';

    $row = [
        $rowNum++,                          // No
        $formattedDate,                     // Tanggal
        h($r['transaction_id']),            // Transaction ID
        $methodName,                        // Metode
        $formattedAmount,                   // Jumlah
        $formattedExpiry,                   // Tanggal Kadaluarsa
        $statusHtml,                        // Status (countdown if pending, badge otherwise)
        $actionHtml                         // Aksi
    ];
    $data[] = $row;
}

header('Content-Type: application/json');
// Discourage caching of SSP responses
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
]);
exit;
?>
