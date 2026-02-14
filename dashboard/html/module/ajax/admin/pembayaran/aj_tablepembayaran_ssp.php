<?php
require_once("../../../../module/connection/conn.php");

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$start = max(0, $start);
if ($length < 0) { $length = 1000; }
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Filters from UI
$PAYMENT_CODE     = $_POST['PAYMENT_CODE'] ?? '';
$PAYMENT_NAME     = $_POST['PAYMENT_NAME'] ?? '';
$PAYMENT_CATEGORY = $_POST['PAYMENT_CATEGORY'] ?? '';
$IS_ACTIVE        = $_POST['IS_ACTIVE'] ?? '';

// Column mapping for ordering
$columns = [
    'p.id',               // 0
    'p.payment_code',     // 1
    'p.payment_name',     // 2
    'p.payment_category', // 3
    'p.fee_type',         // 4
    'p.account_number',   // 5
    'p.account_name',     // 6
    'p.is_active',        // 7
    'p.sort_order',       // 8
    'a.ANGGOTA_NAMA',     // 9 (created_by)
    'p.created_at'        // 10
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 8;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 0 || $orderColIdx >= count($columns)) { $orderColIdx = 8; }
$orderBy = $columns[$orderColIdx] . ' ' . $orderDir;

// Base FROM/JOIN
$fromJoin = " FROM m_payment p
LEFT JOIN m_anggota a ON p.created_by = a.ANGGOTA_ID AND a.DELETION_STATUS = 0 AND a.ANGGOTA_STATUS = 0";

// WHERE clause
$where = [];
if ($PAYMENT_CODE !== '')     { $where[] = "p.payment_code LIKE CONCAT('%','" . str_replace("'","''",$PAYMENT_CODE) . "','%')"; }
if ($PAYMENT_NAME !== '')     { $where[] = "p.payment_name LIKE CONCAT('%','" . str_replace("'","''",$PAYMENT_NAME) . "','%')"; }
if ($PAYMENT_CATEGORY !== '') { $where[] = "p.payment_category = '" . str_replace("'","''",$PAYMENT_CATEGORY) . "'"; }
if ($IS_ACTIVE !== '')        { $where[] = "p.is_active = " . (int)$IS_ACTIVE; }

// Global search
if ($searchValue !== '') {
    $sv = str_replace("'","''",$searchValue);
    $where[] = "(p.payment_code LIKE CONCAT('%','$sv','%') OR p.payment_name LIKE CONCAT('%','$sv','%') OR p.payment_category LIKE CONCAT('%','$sv','%') OR p.account_number LIKE CONCAT('%','$sv','%') OR p.account_name LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// Totals
$sqlTotal = "SELECT COUNT(*) AS cnt $fromJoin";
$totalRes = GetQuery($sqlTotal);
$totalRow = $totalRes ? $totalRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsTotal = isset($totalRow['cnt']) ? (int)$totalRow['cnt'] : 0;

$sqlFiltered = "SELECT COUNT(*) AS cnt $fromJoin $whereSql";
$fRes = GetQuery($sqlFiltered);
$fRow = $fRes ? $fRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsFiltered = isset($fRow['cnt']) ? (int)$fRow['cnt'] : 0;

// Data fetch
$select = "SELECT p.*, 
    a.ANGGOTA_NAMA AS CREATED_BY_NAME, 
    DATE_FORMAT(p.created_at, '%d %M %Y %H:%i') AS CREATED_DATE 
    $fromJoin $whereSql 
    ORDER BY $orderBy 
    LIMIT $start, $length";

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

// Category labels
$categoryLabels = [
    'transfer_bank' => 'Transfer Bank',
    'virtual_account' => 'Virtual Account',
    'ewallet' => 'E-Wallet',
    'qris' => 'QRIS',
    'credit_card' => 'Credit Card',
    'other' => 'Lainnya'
];

// Category badge colors
$categoryColors = [
    'transfer_bank' => 'primary',      // Blue - traditional banking
    'virtual_account' => 'info',       // Light blue - digital banking
    'ewallet' => 'success',            // Green - modern/digital
    'qris' => 'warning',               // Orange/Yellow - universal QR
    'credit_card' => 'danger',         // Red - credit/debt
    'other' => 'default'               // Gray - miscellaneous
];

$data = [];
while ($r = $rowsRes->fetch(PDO::FETCH_ASSOC)) {
    // Format fee display
    if ($r['fee_type'] === 'fixed') {
        $feeDisplay = 'Rp ' . number_format($r['fee_value'], 0, ',', '.');
    } else {
        $feeDisplay = number_format($r['fee_value'], 2) . '%';
    }
    
    // Category display with color
    $categoryDisplay = $categoryLabels[$r['payment_category']] ?? $r['payment_category'];
    $categoryColor = $categoryColors[$r['payment_category']] ?? 'default';
    
    // Status badge
    $statusBadge = ($r['is_active'] == 1) 
        ? '<span class="label label-success">Aktif</span>' 
        : '<span class="label label-danger">Tidak Aktif</span>';
    
    // Action buttons
    $actionBtn = '<div class="btn-group">'
        . '<button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>'
        . '<ul class="dropdown-menu" role="menu">'
        . '<li><a data-toggle="modal" href="#EditPayment" class="open-EditPayment" style="color:cornflowerblue;" data-id="' . $r['id'] . '"><span class="ico-edit"></span> Ubah</a></li>'
        . '<li class="divider"></li>'
        . '<li><a href="#" onclick="deletePayment(' . $r['id'] . ')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>'
        . '</ul>'
        . '</div>';

    // Name with logo
    $logoHtml = '';
    if (!empty($r['logo_url'])) {
        $logoHtml = '<img src="' . htmlspecialchars($r['logo_url']) . '" alt="' . htmlspecialchars($r['payment_name']) . '" style="height: 20px; margin-right: 8px;" onerror="this.style.display=\'none\'">';
    }
    $nameDisplay = $logoHtml . htmlspecialchars($r['payment_name']);

    $row = [
        $actionBtn,
        '<code>' . htmlspecialchars($r['payment_code'] ?? '') . '</code>',
        $nameDisplay,
        '<span class="label label-' . $categoryColor . '">' . htmlspecialchars($categoryDisplay) . '</span>',
        $feeDisplay,
        htmlspecialchars($r['account_number'] ?? '-'),
        htmlspecialchars($r['account_name'] ?? '-'),
        $statusBadge,
        htmlspecialchars($r['sort_order'] ?? '0'),
        htmlspecialchars($r['CREATED_BY_NAME'] ?? ''),
        htmlspecialchars($r['CREATED_DATE'] ?? '')
    ];
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
]);
exit;
?>
