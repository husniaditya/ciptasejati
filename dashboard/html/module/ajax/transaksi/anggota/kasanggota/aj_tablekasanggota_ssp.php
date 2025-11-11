<?php
require_once("../../../../../module/connection/conn.php");

// Local helpers
if (!function_exists('encodeIdToBase64')) {
    function encodeIdToBase64($val) {
        return base64_encode((string)$val);
    }
}
if (!function_exists('h')) {
    function h($v) {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

$USER_CABANG = $_SESSION['LOGINCAB_CS'] ?? '';
$USER_AKSES  = $_SESSION['LOGINAKS_CS'] ?? '';

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$start = max(0, $start);
if ($length < 0) { $length = 1000; }
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Filters
$DAERAH_KEY    = $_POST['DAERAH_KEY'] ?? '';
$CABANG_KEY    = $_POST['CABANG_KEY'] ?? '';
$KAS_ID        = $_POST['KAS_ID'] ?? '';
$KAS_JENIS     = $_POST['KAS_JENIS'] ?? '';
$ANGGOTA_ID    = $_POST['ANGGOTA_ID'] ?? '';
$ANGGOTA_NAMA  = $_POST['ANGGOTA_NAMA'] ?? '';
$TINGKATAN_ID  = $_POST['TINGKATAN_ID'] ?? '';
$KAS_DK        = $_POST['KAS_DK'] ?? '';
$TANGGAL_AWAL  = $_POST['TANGGAL_AWAL'] ?? '';
$TANGGAL_AKHIR = $_POST['TANGGAL_AKHIR'] ?? '';

// Column mapping for ordering (skip 0 = Action)
$columns = [
    'k.KAS_ID',            // 0 (unused - action)
    'k.KAS_ID',            // 1 No Dokumen
    'a.ANGGOTA_ID',        // 2 ID Anggota
    'a.ANGGOTA_NAMA',      // 3 Nama
    't.TINGKATAN_NAMA',    // 4 Tingkatan
    't.TINGKATAN_SEBUTAN', // 5 Gelar
    'k.KAS_JENIS',         // 6 Jenis
    'k.KAS_TANGGAL',       // 7 Tanggal
    'k.KAS_DK',            // 8 Kategori
    'k.KAS_DESKRIPSI',     // 9 Deskripsi
    'k.KAS_JUMLAH',        // 10 Jumlah (numeric for ordering)
    'k.KAS_ID',            // 11 Saldo (proxy: order by doc no)
    'a.ANGGOTA_RANTING',   // 12 Ranting
    'c.CABANG_DESKRIPSI',  // 13 Cabang
    'd.DAERAH_DESKRIPSI',  // 14 Daerah
    'a2.ANGGOTA_NAMA',     // 15 Input Oleh
    'k.INPUT_DATE'         // 16 Input Tanggal
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 1 || $orderColIdx >= count($columns)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx] . ' ' . $orderDir;

// Base FROM/JOIN
$fromJoin = " FROM t_kas k
LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID AND a2.DELETION_STATUS = 0 AND a2.ANGGOTA_STATUS = 0
LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID ";

// Base WHERE
$where = ["k.DELETION_STATUS = 0", "a.DELETION_STATUS = 0"];
if ($USER_AKSES !== 'Administrator') {
    $where[] = "a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "'";
}

// Apply filters
if ($USER_AKSES === 'Administrator') {
    if ($DAERAH_KEY !== '') { $where[] = "d.DAERAH_KEY LIKE CONCAT('%','" . str_replace("'","''",$DAERAH_KEY) . "','%')"; }
    if ($CABANG_KEY !== '') { $where[] = "c.CABANG_KEY LIKE CONCAT('%','" . str_replace("'","''",$CABANG_KEY) . "','%')"; }
}
if ($KAS_ID !== '')       { $where[] = "k.KAS_ID LIKE CONCAT('%','" . str_replace("'","''",$KAS_ID) . "','%')"; }
if ($KAS_JENIS !== '')    { $where[] = "k.KAS_JENIS LIKE CONCAT('%','" . str_replace("'","''",$KAS_JENIS) . "','%')"; }
if ($ANGGOTA_ID !== '')   { $where[] = "a.ANGGOTA_ID LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_ID) . "','%')"; }
if ($ANGGOTA_NAMA !== '') { $where[] = "a.ANGGOTA_NAMA LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_NAMA) . "','%')"; }
if ($TINGKATAN_ID !== '') { $where[] = "t.TINGKATAN_ID LIKE CONCAT('%','" . str_replace("'","''",$TINGKATAN_ID) . "','%')"; }
if ($KAS_DK !== '')       { $where[] = "k.KAS_DK LIKE CONCAT('%','" . str_replace("'","''",$KAS_DK) . "','%')"; }

// Date range
if ($TANGGAL_AWAL !== '' && $TANGGAL_AKHIR !== '') {
    $where[] = "(k.KAS_TANGGAL BETWEEN '" . str_replace("'","''",$TANGGAL_AWAL) . "' AND '" . str_replace("'","''",$TANGGAL_AKHIR) . "')";
} elseif ($TANGGAL_AWAL !== '' && $TANGGAL_AKHIR === '') {
    $where[] = "(k.KAS_TANGGAL BETWEEN '" . str_replace("'","''",$TANGGAL_AWAL) . "' AND '" . str_replace("'","''",$TANGGAL_AWAL) . "')";
} elseif ($TANGGAL_AKHIR !== '' && $TANGGAL_AWAL === '') {
    $where[] = "(k.KAS_TANGGAL BETWEEN '" . str_replace("'","''",$TANGGAL_AKHIR) . "' AND '" . str_replace("'","''",$TANGGAL_AKHIR) . "')";
}

// Global search
if ($searchValue !== '') {
    $sv = str_replace("'","''",$searchValue);
    $where[] = "(k.KAS_ID LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_ID LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_NAMA LIKE CONCAT('%','$sv','%') OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$sv','%') OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$sv','%') OR k.KAS_DESKRIPSI LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// recordsTotal
$whereTotal = ["k.DELETION_STATUS = 0", "a.DELETION_STATUS = 0"];
if ($USER_AKSES !== 'Administrator') {
    $whereTotal[] = "a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "'";
}
$whereTotalSql = empty($whereTotal) ? '' : (' WHERE ' . implode(' AND ', $whereTotal));
$sqlTotal = "SELECT COUNT(*) AS cnt $fromJoin $whereTotalSql";
$totalRes = GetQuery($sqlTotal);
$totalRow = $totalRes ? $totalRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsTotal = isset($totalRow['cnt']) ? (int)$totalRow['cnt'] : 0;

// recordsFiltered
$sqlFiltered = "SELECT COUNT(*) AS cnt $fromJoin $whereSql";
$fRes = GetQuery($sqlFiltered);
$fRow = $fRes ? $fRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsFiltered = isset($fRow['cnt']) ? (int)$fRow['cnt'] : 0;

// Data fetch
$select = "SELECT k.*, d.DAERAH_DESKRIPSI, c.CABANG_DESKRIPSI, a.ANGGOTA_RANTING, a.ANGGOTA_ID, a.ANGGOTA_NAMA,
    t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN, a2.ANGGOTA_NAMA AS INPUT_BY,
    DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') AS FKAS_TANGGAL,
    DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') AS FINPUT_DATE,
    CASE WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')') ELSE FORMAT(k.KAS_JUMLAH, 0) END AS FKAS_JUMLAH,
    CASE WHEN k.KAS_DK = 'D' THEN 'Debit' ELSE 'Kredit' END AS KAS_DK_DES,
    CASE WHEN k.KAS_DK = 'D' THEN 'color: green;' ELSE 'color: red;' END AS KAS_COLOR,
    (SELECT CASE WHEN SUM(tt.KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(tt.KAS_JUMLAH)), 0), ')') ELSE FORMAT(SUM(tt.KAS_JUMLAH), 0) END
     FROM t_kas tt
     WHERE tt.DELETION_STATUS = 0 AND tt.ANGGOTA_KEY = k.ANGGOTA_KEY AND tt.KAS_ID <= k.KAS_ID AND tt.KAS_JENIS = k.KAS_JENIS) AS FKAS_SALDO";

$sqlData = $select . $fromJoin . $whereSql . " ORDER BY $orderBy LIMIT $start, $length";

try {
    $rowsRes = GetQuery($sqlData);
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
    // Action HTML with session permission checks
    ob_start();
    ?>
    <form method="post" class="form">
        <div class="btn-group" style="margin-bottom:5px;">
            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
                <?php if (($_SESSION['VIEW_KasAnggota'] ?? 'N') === 'Y') { ?>
                <li><a data-toggle="modal" href="#ViewKasAnggota" class="open-ViewKasAnggota" style="color:#222222;"
                    data-id="<?= h($r['KAS_ID']); ?>"
                    data-anggota="<?= h($r['ANGGOTA_KEY']); ?>"
                    data-jenis="<?= h($r['KAS_JENIS']); ?>"
                    data-cabang="<?= h($r['CABANG_KEY']); ?>"
                ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                <?php } ?>
                <?php if (($_SESSION['EDIT_KasAnggota'] ?? 'N') === 'Y') { ?>
                <li><a data-toggle="modal" href="#EditKasAnggota" class="open-EditKasAnggota" style="color:#00a5d2;"
                    data-id="<?= h($r['KAS_ID']); ?>"
                    data-anggota="<?= h($r['ANGGOTA_KEY']); ?>"
                    data-jenis="<?= h($r['KAS_JENIS']); ?>"
                    data-cabang="<?= h($r['CABANG_KEY']); ?>"
                ><span class="ico-edit"></span> Ubah</a></li>
                <?php } ?>
                <?php if (($_SESSION['PRINT_KasAnggota'] ?? 'N') === 'Y') { ?>
                <li class="divider"></li>
                <li><a href="assets/print/transaksi/kas/print_kas.php?id=<?= h(encodeIdToBase64($r['KAS_ID'])); ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                <?php } ?>
                <?php if (($_SESSION['DELETE_KasAnggota'] ?? 'N') === 'Y') { ?>
                <li class="divider"></li>
                <li><a href="#" onclick="eventkas('<?= h($r['KAS_ID']); ?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                <?php } ?>
            </ul>
        </div>
    </form>
    <?php
    $actionHtml = ob_get_clean();

    $row = [
        $actionHtml,
        h($r['KAS_ID']),
        h($r['ANGGOTA_ID']),
        h($r['ANGGOTA_NAMA']),
        h($r['TINGKATAN_NAMA']),
        h($r['TINGKATAN_SEBUTAN']),
        h($r['KAS_JENIS']),
        h($r['FKAS_TANGGAL']),
        h($r['KAS_DK_DES']),
        h($r['KAS_DESKRIPSI']),
        '<span style="' . h($r['KAS_COLOR']) . '">' . h($r['FKAS_JUMLAH']) . '</span>',
        h($r['FKAS_SALDO']),
        h($r['ANGGOTA_RANTING']),
        h($r['CABANG_DESKRIPSI']),
        h($r['DAERAH_DESKRIPSI']),
        h($r['INPUT_BY']),
        h($r['FINPUT_DATE'])
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
