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
$MENU_ID    = $_POST['MENU_ID']    ?? '';
$GRUP_ID    = $_POST['GRUP_ID']    ?? '';
$MENU_NAMA  = $_POST['MENU_NAMA']  ?? '';
$USER_AKSES = $_POST['USER_AKSES'] ?? '';
$LEN_GRUP_ID = strlen($GRUP_ID);

// Column mapping for ordering
$columns = [
	'u.MENU_ID',       // 0
	'u.MENU_NAMA',     // 1
	'm.USER_AKSES',    // 2
	'm.VIEW',          // 3
	'm.ADD',           // 4
	'm.EDIT',          // 5
	'm.DELETE',        // 6
	'm.APPROVE',       // 7
	'm.PRINT',         // 8
	'a.ANGGOTA_NAMA',  // 9 (INPUT_BY)
	'm.INPUT_DATE'     // 10
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 0 || $orderColIdx >= count($columns)) { $orderColIdx = 0; }
$orderBy = $columns[$orderColIdx] . ' ' . $orderDir;

// Base FROM/JOIN
$fromJoin = " FROM m_menuakses m\nLEFT JOIN m_menu u ON m.MENU_ID = u.MENU_ID\nLEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID AND a.DELETION_STATUS = 0 AND a.ANGGOTA_STATUS = 0";

// WHERE clause
$where = [];
if ($MENU_ID !== '')    { $where[] = "u.MENU_ID LIKE CONCAT('%','" . str_replace("'","''",$MENU_ID) . "','%')"; }
if ($MENU_NAMA !== '')  { $where[] = "u.MENU_NAMA LIKE CONCAT('%','" . str_replace("'","''",$MENU_NAMA) . "','%')"; }
if ($USER_AKSES !== '') { $where[] = "m.USER_AKSES LIKE CONCAT('%','" . str_replace("'","''",$USER_AKSES) . "','%')"; }
if ($GRUP_ID !== '')    { $where[] = "LEFT(u.MENU_ID,$LEN_GRUP_ID) = '" . str_replace("'","''",$GRUP_ID) . "'"; }

// Global search
if ($searchValue !== '') {
	$sv = str_replace("'","''",$searchValue);
	$where[] = "(u.MENU_ID LIKE CONCAT('%','$sv','%') OR u.MENU_NAMA LIKE CONCAT('%','$sv','%') OR m.USER_AKSES LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// Totals
$sqlTotal = "SELECT COUNT(*) AS cnt $fromJoin"; // all rows
$totalRes = GetQuery($sqlTotal);
$totalRow = $totalRes ? $totalRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsTotal = isset($totalRow['cnt']) ? (int)$totalRow['cnt'] : 0;

$sqlFiltered = "SELECT COUNT(*) AS cnt $fromJoin $whereSql";
$fRes = GetQuery($sqlFiltered);
$fRow = $fRes ? $fRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsFiltered = isset($fRow['cnt']) ? (int)$fRow['cnt'] : 0;

// Data fetch
$select = "SELECT m.*, u.MENU_NAMA, u.MENU_ID, a.ANGGOTA_NAMA AS INPUT_BY, DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') AS INPUT_DATE $fromJoin $whereSql ORDER BY $orderBy LIMIT $start, $length";

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

$data = [];
while ($r = $rowsRes->fetch(PDO::FETCH_ASSOC)) {
	// Helper to render clickable switch based on 'Y' or 'N'
	$switch = function($val, $field, $menuKey) {
		$checked = ($val === 'Y') ? 'checked' : '';
		return '<label class="switch switch-primary">'
			 . '<input type="checkbox" class="quick-toggle" ' . $checked . ' '
			 . 'data-menu-key="' . htmlspecialchars($menuKey) . '" '
			 . 'data-field="' . $field . '">'
			 . '<span class="switch"></span>'
			 . '</label>';
	};

	$row = [
		htmlspecialchars($r['MENU_ID'] ?? ''),
		htmlspecialchars($r['MENU_NAMA'] ?? ''),
		htmlspecialchars($r['USER_AKSES'] ?? ''),
		$switch($r['VIEW'] ?? 'N', 'VIEW', $r['MENU_KEY']),
		$switch($r['ADD'] ?? 'N', 'ADD', $r['MENU_KEY']),
		$switch($r['EDIT'] ?? 'N', 'EDIT', $r['MENU_KEY']),
		$switch($r['DELETE'] ?? 'N', 'DELETE', $r['MENU_KEY']),
		$switch($r['APPROVE'] ?? 'N', 'APPROVE', $r['MENU_KEY']),
		$switch($r['PRINT'] ?? 'N', 'PRINT', $r['MENU_KEY']),
		htmlspecialchars($r['INPUT_BY'] ?? ''),
		htmlspecialchars($r['INPUT_DATE'] ?? '')
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
