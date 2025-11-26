<?php
require_once("../../../../../module/connection/conn.php");

if (!function_exists('h')) {
  function h($v){ return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('encodeIdToBase64')) {
  function encodeIdToBase64($val){ return base64_encode((string)$val); }
}

header('Content-Type: application/json');

$USER_AKSES  = $_SESSION['LOGINAKS_CS'] ?? '';
$USER_CABANG = $_SESSION['LOGINCAB_CS'] ?? '';
$USER_DAERAH = $_SESSION['LOGINDAR_CS'] ?? '';
$USER_ID     = $_SESSION['LOGINIDUS_CS'] ?? '';

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? max(0,(int)$_POST['start']) : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($length < 0) { $length = 1000; }
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
$requestId = isset($_POST['requestId']) ? (string)$_POST['requestId'] : null;

// Filters
$DAERAH_KEY   = $_POST['DAERAH_KEY'] ?? '';
$CABANG_KEY   = $_POST['CABANG_KEY'] ?? '';
$PPD_LOKASI   = $_POST['PPD_LOKASI'] ?? '';
$PPD_ID       = $_POST['PPD_ID'] ?? '';
$ANGGOTA_ID   = $_POST['ANGGOTA_ID'] ?? '';
$ANGGOTA_NAMA = $_POST['ANGGOTA_NAMA'] ?? '';
$TINGKATAN_ID = $_POST['TINGKATAN_ID'] ?? '';
$PPD_JENIS    = $_POST['PPD_JENIS'] ?? '';
$PPD_TANGGAL  = $_POST['PPD_TANGGAL'] ?? '';

// Columns mapping (skip 0 action)
$columns = [
  'p.PPD_ID',            // 0 (action)
  'p.PPD_ID',            // 1 No Dokumen
  'a.ANGGOTA_ID',        // 2 ID Anggota
  'a.ANGGOTA_NAMA',      // 3 Nama Anggota
  'd.DAERAH_DESKRIPSI',  // 4 Daerah
  'c.CABANG_DESKRIPSI',  // 5 Cabang
  'a.ANGGOTA_RANTING',   // 6 Ranting
  'p.PPD_JENIS',         // 7 Jenis
  't.TINGKATAN_NAMA',    // 8 Tingkatan
  't2.TINGKATAN_NAMA',   // 9 Tingkatan PPD
  'c2.CABANG_DESKRIPSI', // 10 Cabang PPD
  'p.PPD_TANGGAL',       // 11 Tanggal
  'p.PPD_DESKRIPSI',     // 12 Deskripsi
  'u.UKT_ID',            // 13 Dokumen UKT
  'u.UKT_TANGGAL',       // 14 Tgl UKT
  'p.PPD_FILE',          // 15 No Sertifikat (file)
  'a2.ANGGOTA_NAMA',     // 16 Input Oleh
  'p.INPUT_DATE'         // 17 Input Tanggal
];
$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 1 || $orderColIdx >= count($columns)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx] . ' ' . $orderDir;

$fromJoin = " FROM t_ppd p
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID AND a2.ANGGOTA_STATUS = 0 AND a2.DELETION_STATUS = 0
LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
LEFT JOIN t_ukt u ON u.TINGKATAN_ID = p.TINGKATAN_ID_BARU AND u.ANGGOTA_ID = p.ANGGOTA_ID AND u.DELETION_STATUS = 0 AND u.UKT_APP_KOOR = 1 ";

$where = ["p.DELETION_STATUS = 0"]; // base

// Three-tier role scoping
if ($USER_AKSES === 'Administrator') {
  // Administrator: no additional scope, can see all
} else if ($USER_AKSES === 'Pengurus Daerah') {
  // Pengurus Daerah: restricted to their daerah
  $where[] = "d.DAERAH_KEY = '" . str_replace("'","''", $USER_DAERAH) . "'";
} else {
  // Koordinator/Pengurus/User: restricted to their cabang or specific user
  if ($USER_AKSES === 'Koordinator' || $USER_AKSES === 'Pengurus') {
    $where[] = "p.CABANG_KEY = '" . str_replace("'","''", $USER_CABANG) . "'";
  } else {
    $where[] = "p.ANGGOTA_ID = '" . str_replace("'","''", $USER_ID) . "'";
  }
}

// Filter by daerah/cabang: Admin and Pengurus Daerah can filter
if ($USER_AKSES === 'Administrator' || $USER_AKSES === 'Pengurus Daerah') {
  if ($DAERAH_KEY !== '') { $where[] = "d.DAERAH_KEY LIKE CONCAT('%','".str_replace("'","''",$DAERAH_KEY)."','%')"; }
  if ($CABANG_KEY !== '') { $where[] = "p.CABANG_KEY LIKE CONCAT('%','".str_replace("'","''",$CABANG_KEY)."','%')"; }
}
// Common filters
if ($PPD_LOKASI !== '')   { $where[] = "p.PPD_LOKASI LIKE CONCAT('%','".str_replace("'","''",$PPD_LOKASI)."','%')"; }
if ($PPD_ID !== '')       { $where[] = "p.PPD_ID LIKE CONCAT('%','".str_replace("'","''",$PPD_ID)."','%')"; }
if ($ANGGOTA_ID !== '')   { $where[] = "a.ANGGOTA_ID LIKE CONCAT('%','".str_replace("'","''",$ANGGOTA_ID)."','%')"; }
if ($ANGGOTA_NAMA !== '') { $where[] = "a.ANGGOTA_NAMA LIKE CONCAT('%','".str_replace("'","''",$ANGGOTA_NAMA)."','%')"; }
if ($TINGKATAN_ID !== '') { $where[] = "t2.TINGKATAN_ID LIKE CONCAT('%','".str_replace("'","''",$TINGKATAN_ID)."','%')"; }
if ($PPD_JENIS !== '')    { $where[] = "p.PPD_JENIS LIKE CONCAT('%','".str_replace("'","''",$PPD_JENIS)."','%')"; }
if ($PPD_TANGGAL !== '')  { $where[] = "p.PPD_TANGGAL LIKE CONCAT('%','".str_replace("'","''",$PPD_TANGGAL)."','%')"; }

// Global search
if ($searchValue !== '') {
  $sv = str_replace("'","''", $searchValue);
  $where[] = "(p.PPD_ID LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_ID LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_NAMA LIKE CONCAT('%','$sv','%') OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$sv','%') OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$sv','%') OR c2.CABANG_DESKRIPSI LIKE CONCAT('%','$sv','%') OR p.PPD_DESKRIPSI LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// Counts
$whereTotal = ["p.DELETION_STATUS = 0"];
if ($USER_AKSES === 'Administrator') {
  // No additional restriction
} else if ($USER_AKSES === 'Pengurus Daerah') {
  $whereTotal[] = "d.DAERAH_KEY = '" . str_replace("'","''", $USER_DAERAH) . "'";
} else {
  if ($USER_AKSES === 'Koordinator' || $USER_AKSES === 'Pengurus') {
    $whereTotal[] = "p.CABANG_KEY = '" . str_replace("'","''", $USER_CABANG) . "'";
  } else {
    $whereTotal[] = "p.ANGGOTA_ID = '" . str_replace("'","''", $USER_ID) . "'";
  }
}
$sqlTotal = "SELECT COUNT(*) AS cnt $fromJoin " . (empty($whereTotal) ? '' : (' WHERE ' . implode(' AND ', $whereTotal)));
$totalRes = GetQuery($sqlTotal);
$recordsTotal = (int)($totalRes ? ($totalRes->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0) : 0);

$sqlFiltered = "SELECT COUNT(*) AS cnt $fromJoin $whereSql";
$fRes = GetQuery($sqlFiltered);
$recordsFiltered = (int)($fRes ? ($fRes->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0) : 0);

// Data
$select = "SELECT p.*, d.DAERAH_DESKRIPSI, c.CABANG_DESKRIPSI, a.ANGGOTA_RANTING, a.ANGGOTA_ID, a.ANGGOTA_NAMA,
 t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN,
 t2.TINGKATAN_NAMA AS PPD_TINGKATAN, t2.TINGKATAN_SEBUTAN AS PPD_SEBUTAN,
 c2.CABANG_DESKRIPSI AS PPD_CABANG,
 a2.ANGGOTA_NAMA AS INPUT_BY,
 DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') AS FINPUT_DATE,
 DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') AS FPPD_TANGGAL,
 u.UKT_ID, DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') AS UKT_TANGGAL,
 CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' ELSE 'Ulang' END AS PPD_JENIS_DES,
 CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'fa-solid fa-spinner fa-spin' WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'fa-solid fa-check' ELSE 'fa-solid fa-xmark' END AS PELATIH_CLASS,
 CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'fa-solid fa-spinner fa-spin' WHEN p.PPD_APPROVE_GURU = 1 THEN 'fa-solid fa-check' ELSE 'fa-solid fa-xmark' END AS GURU_CLASS,
 CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'badge badge-inverse' WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'badge badge-success' ELSE 'badge badge-danger' END AS PELATIH_BADGE,
 CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'badge badge-inverse' WHEN p.PPD_APPROVE_GURU = 1 THEN 'badge badge-success' ELSE 'badge badge-danger' END AS GURU_BADGE,
 SUBSTRING_INDEX(p.PPD_FILE,'/',-1) AS PPD_FILE_NAME";

$sqlData = $select . $fromJoin . $whereSql . " GROUP BY p.PPD_ID ORDER BY $orderBy LIMIT $start, $length";

try {
  $rowsRes = GetQuery($sqlData);
} catch (Throwable $e) {
  echo json_encode(['draw'=>$draw,'requestId'=>$requestId,'recordsTotal'=>0,'recordsFiltered'=>0,'data'=>[], 'error'=>'Query error: '.$e->getMessage()]);
  exit;
}

$data = [];
while ($r = $rowsRes->fetch(PDO::FETCH_ASSOC)) {
  // Action HTML (mirror transactional PPD actions)
  ob_start();
  ?>
  <form method="post" class="form">
    <div class="btn-group" style="margin-bottom:5px;">
      <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><a data-toggle="modal" href="#ViewPPD" class="open-ViewPPD" style="color:#222222;" data-id="<?= h($r['PPD_ID']); ?>" data-cabang="<?= h($r['CABANG_KEY']); ?>" ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
        <li class="divider"></li>
        <li><a href="assets/print/transaksi/aktivitas/ppd/print_ppdreportanggota.php?id=<?= h(encodeIdToBase64($r['PPD_ID'])); ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
      </ul>
    </div>
  </form>
  <?php
  $actionHtml = ob_get_clean();

  $noDokumenHtml = h($r['PPD_ID']) . '<br>'
    . '<span class="' . h($r['PELATIH_BADGE']) . '"><i class="' . h($r['PELATIH_CLASS']) . '"></i> Koordinator </span><br>'
    . '<span class="' . h($r['GURU_BADGE']) . '"><i class="' . h($r['GURU_CLASS']) . '"></i> Guru Besar </span>';

  $uktLink = $r['UKT_ID'] ? ('<a data-toggle="modal" href="#ViewUKT" class="open-ViewUKT" data-id="' . h($r['UKT_ID']) . '"> ' . h($r['UKT_ID']) . '</a>') : '';

  $sertifikatHtml = '';
  if ((int)$r['PPD_APPROVE_GURU'] === 1 && !empty($r['PPD_FILE'])) {
      $sertifikatHtml = '<div><a href="' . h($r['PPD_FILE']) . '" target="_blank"><i class="fa-regular fa-file-lines"></i> ' . h($r['PPD_FILE_NAME']) . '</a></div>';
  }

  $row = [
    $actionHtml,
    $noDokumenHtml,
    h($r['ANGGOTA_ID']),
    h($r['ANGGOTA_NAMA']),
    h($r['DAERAH_DESKRIPSI']),
    h($r['CABANG_DESKRIPSI']),
    h($r['ANGGOTA_RANTING']),
    h($r['PPD_JENIS_DES']),
    h($r['TINGKATAN_NAMA']) . ' - ' . h($r['TINGKATAN_SEBUTAN']),
    h($r['PPD_TINGKATAN']) . ' - ' . h($r['PPD_SEBUTAN']),
    h($r['PPD_CABANG']),
    h($r['FPPD_TANGGAL']),
    h($r['PPD_DESKRIPSI']),
    $uktLink,
    h($r['UKT_TANGGAL']),
    $sertifikatHtml,
    h($r['INPUT_BY']),
    h($r['FINPUT_DATE'])
  ];
  $data[] = $row;
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
