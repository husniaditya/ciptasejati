<?php
require_once("../../../../../module/connection/conn.php");

header('Content-Type: application/json');

$USER_CABANG = $_SESSION['LOGINCAB_CS'] ?? '';
$USER_AKSES  = $_SESSION['LOGINAKS_CS'] ?? '';
$USER_DAERAH = $_SESSION['LOGINDAR_CS'] ?? '';

function esc_html($v){
    $v = $v === null ? '' : (string)$v;
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$requestId = isset($_POST['requestId']) ? (string)$_POST['requestId'] : null;
$start  = isset($_POST['start']) ? max(0, (int)$_POST['start']) : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($length <= 0) { $length = 10; }

$orderColIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir      = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';

// Map DataTables column index to SQL columns
$orderableCols = [
    0 => null, // action (not orderable)
    1 => 'a.ANGGOTA_ID',
    2 => 'a.ANGGOTA_NAMA',
    3 => 'a.ANGGOTA_KELAMIN',
    4 => 't.TINGKATAN_NAMA',
    5 => 't.TINGKATAN_SEBUTAN',
    6 => 't.TINGKATAN_GELAR',
    7 => 'a.ANGGOTA_RANTING',
    8 => 'c.CABANG_DESKRIPSI',
    9 => 'd.DAERAH_DESKRIPSI',
    10 => 'a.ANGGOTA_JOIN',
    11 => 'a.ANGGOTA_STATUS',
    12 => 'a.ANGGOTA_RESIGN',
];
$orderBy = $orderableCols[$orderColIndex] ?? 'a.ANGGOTA_ID';

// Filters
$DAERAH_KEY       = trim($_POST['DAERAH_KEY'] ?? '');
$CABANG_KEY       = trim($_POST['CABANG_KEY'] ?? '');
$TINGKATAN_ID     = trim($_POST['TINGKATAN_ID'] ?? '');
$ANGGOTA_ID       = trim($_POST['ANGGOTA_ID'] ?? '');
$ANGGOTA_RANTING  = trim($_POST['ANGGOTA_RANTING'] ?? '');
$ANGGOTA_NAMA     = trim($_POST['ANGGOTA_NAMA'] ?? '');
$ANGGOTA_AKSES    = trim($_POST['ANGGOTA_AKSES'] ?? '');
$ANGGOTA_STATUS   = trim($_POST['ANGGOTA_STATUS'] ?? '');
$globalSearch     = trim($_POST['search']['value'] ?? '');

// Base FROM clause
$from = " FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID ";

// WHERE conditions
$where = [];
// Scope by access level
if ($USER_AKSES === 'Administrator') {
    // Admin can filter by any daerah/cabang
    if ($DAERAH_KEY !== '')  { $where[] = "c.DAERAH_KEY LIKE CONCAT('%','" . str_replace("'", "''", $DAERAH_KEY) . "','%')"; }
    if ($CABANG_KEY !== '')  { $where[] = "a.CABANG_KEY LIKE CONCAT('%','" . str_replace("'", "''", $CABANG_KEY) . "','%')"; }
} else if ($USER_AKSES === 'Pengurus Daerah') {
    // Pengurus Daerah can only see their daerah but can filter by cabang within it
    $where[] = "c.DAERAH_KEY = '" . str_replace("'", "''", $USER_DAERAH) . "'";
    if ($CABANG_KEY !== '')  { $where[] = "a.CABANG_KEY LIKE CONCAT('%','" . str_replace("'", "''", $CABANG_KEY) . "','%')"; }
} else {
    // Other users (Koordinator, Pengurus, User) can only see their cabang
    $where[] = "a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "'";
    $where[] = "a.ANGGOTA_AKSES <> 'Administrator'";
}
if ($TINGKATAN_ID !== '')   { $where[] = "a.TINGKATAN_ID LIKE CONCAT('%','" . str_replace("'", "''", $TINGKATAN_ID) . "','%')"; }
if ($ANGGOTA_RANTING !== ''){ $where[] = "a.ANGGOTA_RANTING LIKE CONCAT('%','" . str_replace("'", "''", $ANGGOTA_RANTING) . "','%')"; }
if ($ANGGOTA_NAMA !== '')   { $where[] = "a.ANGGOTA_NAMA LIKE CONCAT('%','" . str_replace("'", "''", $ANGGOTA_NAMA) . "','%')"; }
if ($ANGGOTA_AKSES !== '')  { $where[] = "a.ANGGOTA_AKSES LIKE CONCAT('%','" . str_replace("'", "''", $ANGGOTA_AKSES) . "','%')"; }
if ($ANGGOTA_STATUS !== '') { $where[] = "a.ANGGOTA_STATUS LIKE CONCAT('%','" . str_replace("'", "''", $ANGGOTA_STATUS) . "','%')"; }
if ($ANGGOTA_ID !== '')     { $where[] = "a.ANGGOTA_ID LIKE CONCAT('%','" . str_replace("'", "''", $ANGGOTA_ID) . "','%')"; }

// Global search across a few columns
if ($globalSearch !== '') {
    $s = str_replace("'", "''", $globalSearch);
    $where[] = "(a.ANGGOTA_ID LIKE CONCAT('%','$s','%')
              OR a.ANGGOTA_NAMA LIKE CONCAT('%','$s','%')
              OR a.ANGGOTA_RANTING LIKE CONCAT('%','$s','%')
              OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$s','%')
              OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$s','%')
              OR t.TINGKATAN_NAMA LIKE CONCAT('%','$s','%')
              OR t.TINGKATAN_SEBUTAN LIKE CONCAT('%','$s','%')
              OR t.TINGKATAN_GELAR LIKE CONCAT('%','$s','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// recordsTotal
$sqlCountTotal = "SELECT COUNT(*) cnt $from";
if ($USER_AKSES === 'Pengurus Daerah') {
    $sqlCountTotal .= " WHERE c.DAERAH_KEY = '" . str_replace("'", "''", $USER_DAERAH) . "'";
} else if ($USER_AKSES !== 'Administrator') {
    $sqlCountTotal .= " WHERE a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "' AND a.ANGGOTA_AKSES <> 'Administrator'";
}
$resTotal = GetQuery($sqlCountTotal);
$rowT = $resTotal->fetch(PDO::FETCH_ASSOC);
$recordsTotal = (int)($rowT['cnt'] ?? 0);

// recordsFiltered
$sqlCountFiltered = "SELECT COUNT(*) cnt $from $whereSql";
$resFiltered = GetQuery($sqlCountFiltered);
$rowF = $resFiltered->fetch(PDO::FETCH_ASSOC);
$recordsFiltered = (int)($rowF['cnt'] ?? 0);

// Data query with order + limit
$selectCols = "a.ANGGOTA_KEY, a.ANGGOTA_ID, a.ANGGOTA_NAMA, a.ANGGOTA_KELAMIN, a.ANGGOTA_RANTING, a.ANGGOTA_KTP, a.ANGGOTA_ALAMAT, a.ANGGOTA_PEKERJAAN, a.ANGGOTA_AGAMA, a.ANGGOTA_TEMPAT_LAHIR, a.ANGGOTA_TANGGAL_LAHIR, a.ANGGOTA_HP, a.ANGGOTA_EMAIL, a.ANGGOTA_PIC, a.ANGGOTA_JOIN, a.ANGGOTA_RESIGN, a.ANGGOTA_AKSES, a.ANGGOTA_STATUS, c.CABANG_KEY, c.CABANG_DESKRIPSI, d.DAERAH_KEY, d.DAERAH_DESKRIPSI, t.TINGKATAN_ID, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN, t.TINGKATAN_GELAR, RIGHT(a.ANGGOTA_ID,3) AS SHORT_ID";

$sqlData = "SELECT $selectCols $from $whereSql ORDER BY $orderBy $orderDir LIMIT $start, $length";
$resData = GetQuery($sqlData);

$data = [];
while ($r = $resData->fetch(PDO::FETCH_ASSOC)) {
    $ANGGOTA_KEY = $r['ANGGOTA_KEY'];
    $ANGGOTA_ID  = $r['ANGGOTA_ID'];
    $SHORT_ID    = $r['SHORT_ID'];
    $DAERAH_KEY  = $r['DAERAH_KEY'];
    $DAERAH_DES  = $r['DAERAH_DESKRIPSI'];
    $CABANG_KEY  = $r['CABANG_KEY'];
    $CABANG_DES  = $r['CABANG_DESKRIPSI'];
    $TINGKATAN_ID= $r['TINGKATAN_ID'];
    $TINGKATAN_NM= $r['TINGKATAN_NAMA'];
    $TINGKATAN_SB= $r['TINGKATAN_SEBUTAN'];
    $TINGKATAN_GL= $r['TINGKATAN_GELAR'];
    $STATUS      = $r['ANGGOTA_STATUS'];
    $STATUS_DES  = ($STATUS == '0' ? 'Aktif' : ($STATUS == '1' ? 'Non Aktif' : 'Mutasi'));

    // Format dates for display (avoid deprecated strftime)
    $fmtDate = function($v){
        if (!$v) return '';
        $ts = strtotime($v);
        if (!$ts) return esc_html($v);
        return date('d F Y', $ts);
    };

    $tglJoin   = $fmtDate($r['ANGGOTA_JOIN'] ?? '');
    $tglResign = $fmtDate($r['ANGGOTA_RESIGN'] ?? '');

    // Action HTML (View)
    $actionHtml = '<div class="btn-group" style="margin-bottom:5px;">'
        .'<button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>'
        .'<ul class="dropdown-menu" role="menu">'
        .'<li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:#222222;"'
        .' data-key="'.esc_html($ANGGOTA_KEY).'"'
        .' data-id="'.esc_html($ANGGOTA_ID).'"'
        .' data-shortid="'.esc_html($SHORT_ID).'"'
        .' data-daerahkey="'.esc_html($DAERAH_KEY).'"'
        .' data-daerahdes="'.esc_html($DAERAH_DES).'"'
        .' data-cabangkey="'.esc_html($CABANG_KEY).'"'
        .' data-cabangdes="'.esc_html($CABANG_DES).'"'
        .' data-tingkatanid="'.esc_html($TINGKATAN_ID).'"'
        .' data-tingkatannama="'.esc_html($TINGKATAN_NM).'"'
        .' data-ktp="'.esc_html($r['ANGGOTA_KTP']).'"'
        .' data-nama="'.esc_html($r['ANGGOTA_NAMA']).'"'
        .' data-alamat="'.esc_html($r['ANGGOTA_ALAMAT']).'"'
        .' data-pekerjaan="'.esc_html($r['ANGGOTA_PEKERJAAN']).'"'
        .' data-agama="'.esc_html($r['ANGGOTA_AGAMA']).'"'
        .' data-kelamin="'.esc_html($r['ANGGOTA_KELAMIN']).'"'
        .' data-tempatlahir="'.esc_html($r['ANGGOTA_TEMPAT_LAHIR']).'"'
        .' data-tanggallahir="'.esc_html($r['ANGGOTA_TANGGAL_LAHIR']).'"'
        .' data-hp="'.esc_html($r['ANGGOTA_HP']).'"'
        .' data-email="'.esc_html($r['ANGGOTA_EMAIL']).'"'
        .' data-pic="'.esc_html($r['ANGGOTA_PIC']).'"'
        .' data-join="'.esc_html($r['ANGGOTA_JOIN']).'"'
        .' data-resign="'.esc_html($r['ANGGOTA_RESIGN']).'"'
        .' data-akses="'.esc_html($r['ANGGOTA_AKSES']).'"'
        .' data-status="'.esc_html($STATUS).'"'
        .' data-statusdes="'.esc_html($STATUS_DES).'"'
        .' data-ranting="'.esc_html($r['ANGGOTA_RANTING']).'"'
        .'><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>'
        .'</ul>'
        .'</div>';

    $row = [
        $actionHtml,
        esc_html($r['ANGGOTA_ID']),
        esc_html($r['ANGGOTA_NAMA']),
        esc_html($r['ANGGOTA_KELAMIN']),
        esc_html($TINGKATAN_NM),
        esc_html($TINGKATAN_SB),
        esc_html($TINGKATAN_GL),
        esc_html($r['ANGGOTA_RANTING']),
        esc_html($CABANG_DES),
        esc_html($DAERAH_DES),
        esc_html($tglJoin),
        esc_html($STATUS_DES),
        esc_html($tglResign),
    ];
    $data[] = $row;
}

echo json_encode([
    'draw' => $draw,
    // Echo back requestId if provided (DataTables 2.x request tracking)
    'requestId' => $requestId,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data,
]);
exit;
?>
