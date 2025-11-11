<?php
require_once("../../../../../module/connection/conn.php");

header('Content-Type: application/json');

$USER_CABANG = $_SESSION['LOGINCAB_CS'] ?? '';
$USER_AKSES  = $_SESSION['LOGINAKS_CS'] ?? '';
$USER_KEY    = $_SESSION['LOGINKEY_CS'] ?? '';

function esc_html($v){
    $v = $v === null ? '' : (string)$v;
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

$draw      = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$requestId = isset($_POST['requestId']) ? (string)$_POST['requestId'] : null;
$start     = isset($_POST['start']) ? max(0, (int)$_POST['start']) : 0;
$length    = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($length <= 0) { $length = 10; }

$orderColIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir      = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';

// Column mapping for order
$orderable = [
  0 => null,
  1 => 'k.KAS_ID',
  2 => 'a.ANGGOTA_ID',
  3 => 'a.ANGGOTA_NAMA',
  4 => 't.TINGKATAN_NAMA',
  5 => 't.TINGKATAN_SEBUTAN',
  6 => 'k.KAS_JENIS',
  7 => 'k.KAS_TANGGAL',
  8 => 'k.KAS_DK',
  9 => 'k.KAS_DESKRIPSI',
 10 => 'k.KAS_JUMLAH',
 11 => null, // saldo computed
 12 => 'a.ANGGOTA_RANTING',
 13 => 'c.CABANG_DESKRIPSI',
 14 => 'd.DAERAH_DESKRIPSI',
 15 => 'a2.ANGGOTA_NAMA',
 16 => 'k.INPUT_DATE'
];
$orderBy = $orderable[$orderColIndex] ?? 'k.KAS_ID';

// Filters
$DAERAH_KEY   = trim($_POST['DAERAH_KEY'] ?? '');
$CABANG_KEY   = trim($_POST['CABANG_KEY'] ?? '');
$KAS_ID       = trim($_POST['KAS_ID'] ?? '');
$KAS_JENIS    = trim($_POST['KAS_JENIS'] ?? '');
$ANGGOTA_ID   = trim($_POST['ANGGOTA_ID'] ?? '');
$ANGGOTA_NAMA = trim($_POST['ANGGOTA_NAMA'] ?? '');
$TINGKATAN_ID = trim($_POST['TINGKATAN_ID'] ?? '');
$KAS_DK       = trim($_POST['KAS_DK'] ?? '');
$TGL_AWAL     = trim($_POST['TANGGAL_AWAL'] ?? '');
$TGL_AKHIR    = trim($_POST['TANGGAL_AKHIR'] ?? '');
$globalSearch = trim($_POST['search']['value'] ?? '');

$from = " FROM t_kas k
LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY AND a.DELETION_STATUS=0
LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID AND a2.DELETION_STATUS = 0 AND a2.ANGGOTA_STATUS = 0
LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID ";

$where = ["k.DELETION_STATUS = 0"]; // base condition

if ($USER_AKSES !== 'Administrator') {
  $where[] = "a.CABANG_KEY = '".str_replace("'","''",$USER_CABANG)."'";
  $where[] = "a.ANGGOTA_KEY = '".str_replace("'","''",$USER_KEY)."'";
}
if ($DAERAH_KEY !== '')   $where[] = "d.DAERAH_KEY LIKE CONCAT('%','".str_replace("'","''",$DAERAH_KEY)."','%')";
if ($CABANG_KEY !== '')   $where[] = "c.CABANG_KEY LIKE CONCAT('%','".str_replace("'","''",$CABANG_KEY)."','%')";
if ($KAS_ID !== '')       $where[] = "k.KAS_ID LIKE CONCAT('%','".str_replace("'","''",$KAS_ID)."','%')";
if ($KAS_JENIS !== '')    $where[] = "k.KAS_JENIS LIKE CONCAT('%','".str_replace("'","''",$KAS_JENIS)."','%')";
if ($ANGGOTA_ID !== '')   $where[] = "a.ANGGOTA_ID LIKE CONCAT('%','".str_replace("'","''",$ANGGOTA_ID)."','%')";
if ($ANGGOTA_NAMA !== '') $where[] = "a.ANGGOTA_NAMA LIKE CONCAT('%','".str_replace("'","''",$ANGGOTA_NAMA)."','%')";
if ($TINGKATAN_ID !== '') $where[] = "t.TINGKATAN_ID LIKE CONCAT('%','".str_replace("'","''",$TINGKATAN_ID)."','%')";
if ($KAS_DK !== '')       $where[] = "k.KAS_DK LIKE CONCAT('%','".str_replace("'","''",$KAS_DK)."','%')";
if ($TGL_AWAL !== '' && $TGL_AKHIR !== '') {
  $where[] = "(k.KAS_TANGGAL BETWEEN '".str_replace("'","''",$TGL_AWAL)."' AND '".str_replace("'","''",$TGL_AKHIR)."')";
} else if ($TGL_AWAL !== '') {
  $where[] = "(k.KAS_TANGGAL BETWEEN '".str_replace("'","''",$TGL_AWAL)."' AND '".str_replace("'","''",$TGL_AWAL)."')";
} else if ($TGL_AKHIR !== '') {
  $where[] = "(k.KAS_TANGGAL BETWEEN '".str_replace("'","''",$TGL_AKHIR)."' AND '".str_replace("'","''",$TGL_AKHIR)."')";
}

if ($globalSearch !== '') {
  $s = str_replace("'","''",$globalSearch);
  $where[] = "(k.KAS_ID LIKE CONCAT('%','$s','%') OR a.ANGGOTA_ID LIKE CONCAT('%','$s','%') OR a.ANGGOTA_NAMA LIKE CONCAT('%','$s','%') OR k.KAS_DESKRIPSI LIKE CONCAT('%','$s','%') OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$s','%') OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$s','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE '.implode(' AND ',$where));

// counts
$sqlTotal = "SELECT COUNT(*) cnt $from" . ($USER_AKSES !== 'Administrator' ? " WHERE k.DELETION_STATUS=0 AND a.CABANG_KEY = '".str_replace("'","''",$USER_CABANG)."' AND a.ANGGOTA_KEY = '".str_replace("'","''",$USER_KEY)."'" : " WHERE k.DELETION_STATUS=0");
$resT = GetQuery($sqlTotal); $recordsTotal = (int)($resT->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$sqlFilt = "SELECT COUNT(*) cnt $from $whereSql";
$resF = GetQuery($sqlFilt); $recordsFiltered = (int)($resF->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

// data
$select = "k.KAS_ID, k.KAS_JENIS, k.KAS_TANGGAL, k.KAS_DK, k.KAS_DESKRIPSI, k.KAS_JUMLAH, k.INPUT_DATE, a.ANGGOTA_KEY, a.ANGGOTA_ID, a.ANGGOTA_NAMA, a.ANGGOTA_RANTING, c.CABANG_KEY, c.CABANG_DESKRIPSI, d.DAERAH_DESKRIPSI, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN, a2.ANGGOTA_NAMA AS INPUT_BY, k.CABANG_KEY";
$sqlData = "SELECT $select $from $whereSql ORDER BY $orderBy $orderDir LIMIT $start, $length";
$res = GetQuery($sqlData);

// helper to format numbers with parentheses for negatives
$fmtNum = function($n){
  if ($n === null || $n === '') return '';
  $n = (float)$n;
  $abs = number_format(abs($n), 0, '.', ',');
  return $n < 0 ? '(' . $abs . ')' : $abs;
};

// helper to format date
$fmtDate = function($v){
  if (!$v) return '';
  $ts = strtotime($v); if (!$ts) return esc_html($v);
  return date('d F Y', $ts);
};

$data = [];
while ($r = $res->fetch(PDO::FETCH_ASSOC)) {
  // compute saldo up to KAS_ID per anggota and jenis
  $KAS_ID = $r['KAS_ID'];
  $ANGGOTA_KEY = $r['ANGGOTA_KEY'];
  $KAS_JENIS = $r['KAS_JENIS'];
  $saldoRow = GetQuery("SELECT SUM(KAS_JUMLAH) AS SALDO FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_KEY = '".str_replace("'","''",$ANGGOTA_KEY)."' AND KAS_ID <= '".str_replace("'","''",$KAS_ID)."' AND KAS_JENIS = '".str_replace("'","''",$KAS_JENIS)."'");
  $saldo = ($saldoRow->fetch(PDO::FETCH_ASSOC)['SALDO'] ?? 0);

  $kasJumlah = $fmtNum($r['KAS_JUMLAH']);
  $kasSaldo  = $fmtNum($saldo);
  $kasColor  = ($r['KAS_DK'] === 'D') ? 'color: green;' : 'color: red;';
  $kasKategori = ($r['KAS_DK'] === 'D') ? 'Debit' : 'Kredit';

  $actionHtml = '<div class="btn-group" style="margin-bottom:5px;">'
    .'<button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>'
    .'<ul class="dropdown-menu" role="menu">'
    .'<li><a data-toggle="modal" href="#ViewKasAnggota" class="open-ViewKasAnggota" style="color:#222222;"'
    .' data-id="'.esc_html($r['KAS_ID']).'"'
    .' data-anggota="'.esc_html($r['ANGGOTA_KEY']).'"'
    .' data-jenis="'.esc_html($r['KAS_JENIS']).'"'
    .' data-cabang="'.esc_html($r['CABANG_KEY']).'"'
    .'><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>'
    .'<li class="divider"></li>'
    .'<li><a href="assets/print/transaksi/kas/print_kas.php?id='.esc_html(encodeIdToBase64($r['KAS_ID'])).'" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>'
    .'</ul>'
    .'</div>';

  $data[] = [
    $actionHtml,
    esc_html($r['KAS_ID']),
    esc_html($r['ANGGOTA_ID']),
    esc_html($r['ANGGOTA_NAMA']),
    esc_html($r['TINGKATAN_NAMA']),
    esc_html($r['TINGKATAN_SEBUTAN']),
    esc_html($r['KAS_JENIS']),
    esc_html($fmtDate($r['KAS_TANGGAL'])),
    esc_html($kasKategori),
    esc_html($r['KAS_DESKRIPSI']),
    '<span style="'.esc_html($kasColor).'">'.esc_html($kasJumlah).'</span>',
    esc_html($kasSaldo),
    esc_html($r['ANGGOTA_RANTING']),
    esc_html($r['CABANG_DESKRIPSI']),
    esc_html($r['DAERAH_DESKRIPSI']),
    esc_html($r['INPUT_BY']),
    esc_html($fmtDate($r['INPUT_DATE'])),
  ];
}

echo json_encode([
  'draw' => $draw,
  'requestId' => $requestId,
  'recordsTotal' => $recordsTotal,
  'recordsFiltered' => $recordsFiltered,
  'data' => $data
]);
exit;
?>
