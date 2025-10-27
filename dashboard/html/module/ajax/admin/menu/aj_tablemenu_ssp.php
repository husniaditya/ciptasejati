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

// Column mapping for ordering (skip Action column index 0)
$columns = [
	'u.MENU_ID',       // 1
	'u.MENU_NAMA',     // 2
	'm.USER_AKSES',    // 3
	'm.VIEW',          // 4
	'm.ADD',           // 5
	'm.EDIT',          // 6
	'm.DELETE',        // 7
	'm.APPROVE',       // 8
	'm.PRINT',         // 9
	'a.ANGGOTA_NAMA',  // 10 (INPUT_BY)
	'm.INPUT_DATE'     // 11
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 1 || $orderColIdx > count($columns)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx - 1] . ' ' . $orderDir; // adjust since mapping starts at first data col

// Base FROM/JOIN
$fromJoin = " FROM m_menuakses m\nLEFT JOIN m_menu u ON m.MENU_ID = u.MENU_ID\nLEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID ";

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
	// Action dropdown (Edit only)
	ob_start();
	?>
	<form id="eventoption-form-<?= htmlspecialchars($r['MENU_KEY']) ?>" method="post" class="form">
		<div class="btn-group" style="margin-bottom:5px;">
			<button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu">
				<li><a data-toggle="modal" href="#EditMenu" class="open-EditMenu" data-id="<?= htmlspecialchars($r['MENU_KEY']) ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Akses</a></li>
			</ul>
		</div>
	</form>
	<?php
	$actionHtml = ob_get_clean();

	// Helper to render read-only switch based on 'Y' or 'N'
	$switch = function($val) {
		$checked = ($val === 'Y') ? 'checked' : '';
		return '<label class="switch switch-primary">'
			 . '<input type="checkbox" ' . $checked . ' disabled>'
			 . '<span class="switch" disabled></span>'
			 . '</label>';
	};

	$row = [
		$actionHtml,
		htmlspecialchars($r['MENU_ID'] ?? ''),
		htmlspecialchars($r['MENU_NAMA'] ?? ''),
		htmlspecialchars($r['USER_AKSES'] ?? ''),
		$switch($r['VIEW'] ?? 'N'),
		$switch($r['ADD'] ?? 'N'),
		$switch($r['EDIT'] ?? 'N'),
		$switch($r['DELETE'] ?? 'N'),
		$switch($r['APPROVE'] ?? 'N'),
		$switch($r['PRINT'] ?? 'N'),
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
