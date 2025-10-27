<?php
require_once("../../../../../module/connection/conn.php");

// Local helper to match previous usage in views (avoid fatal if not globally defined)
if (!function_exists('encodeIdToBase64')) {
    function encodeIdToBase64($val) {
        // Simple Base64 encoding; keep consistent with prior usage
        return base64_encode((string)$val);
    }
}
// Safe HTML escape that tolerates nulls
if (!function_exists('h')) {
    function h($v) {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

$USER_CABANG = $_SESSION['LOGINCAB_CS'] ?? '';
$USER_AKSES = $_SESSION['LOGINAKS_CS'] ?? '';

// DataTables params
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$start = max(0, $start);
// Handle DataTables length = -1 (show all) safely with an upper cap
if ($length < 0) { $length = 1000; }
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Filters
$DAERAH_KEY = $_POST['DAERAH_KEY'] ?? '';
$CABANG_KEY = $_POST['CABANG_KEY'] ?? '';
$TINGKATAN_ID = $_POST['TINGKATAN_ID'] ?? '';
$ANGGOTA_ID = $_POST['ANGGOTA_ID'] ?? '';
$ANGGOTA_RANTING = $_POST['ANGGOTA_RANTING'] ?? '';
$ANGGOTA_NAMA = $_POST['ANGGOTA_NAMA'] ?? '';
$ANGGOTA_AKSES = $_POST['ANGGOTA_AKSES'] ?? '';
$ANGGOTA_STATUS = $_POST['ANGGOTA_STATUS'] ?? '';

// Column mapping for ordering (index from DataTables to SQL column)
$columns = [
    'a.ANGGOTA_ID',            // 0: we'll treat first data col as ID (Action column won't be ordered)
    'a.ANGGOTA_ID',            // 1
    'a.ANGGOTA_NAMA',          // 2
    'a.ANGGOTA_TANGGAL_LAHIR', // 3 (with tempat lahir shown together)
    'a.ANGGOTA_KELAMIN',       // 4
    't.TINGKATAN_NAMA',        // 5
    't.TINGKATAN_SEBUTAN',     // 6
    't.TINGKATAN_GELAR',       // 7
    'a.ANGGOTA_KTP',           // 8
    'a.ANGGOTA_HP',            // 9
    'a.ANGGOTA_EMAIL',         // 10
    'a.ANGGOTA_RANTING',       // 11
    'c.CABANG_DESKRIPSI',      // 12
    'd.DAERAH_DESKRIPSI',      // 13
    'a.ANGGOTA_JOIN',          // 14
    'a.ANGGOTA_STATUS',        // 15
    'a.ANGGOTA_RESIGN'         // 16
];

// Ordering
$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
// Prevent ordering by action column (index 0) by falling back to ID
if ($orderColIdx < 1 || $orderColIdx >= count($columns)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx] . ' ' . $orderDir;

// Base FROM/JOIN
$fromJoin = " FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID ";

// Base WHERE
$where = [];
if ($USER_AKSES !== 'Administrator') {
    $where[] = "a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "'";
    $where[] = "a.ANGGOTA_AKSES != 'Administrator'";
}
// Apply filters (LIKE for flexible matching)
if ($DAERAH_KEY !== '') { $where[] = "d.DAERAH_KEY LIKE CONCAT('%','" . str_replace("'","''",$DAERAH_KEY) . "','%')"; }
if ($CABANG_KEY !== '') { $where[] = "a.CABANG_KEY LIKE CONCAT('%','" . str_replace("'","''",$CABANG_KEY) . "','%')"; }
if ($TINGKATAN_ID !== '') { $where[] = "a.TINGKATAN_ID LIKE CONCAT('%','" . str_replace("'","''",$TINGKATAN_ID) . "','%')"; }
if ($ANGGOTA_ID !== '') { $where[] = "a.ANGGOTA_ID LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_ID) . "','%')"; }
if ($ANGGOTA_RANTING !== '') { $where[] = "a.ANGGOTA_RANTING LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_RANTING) . "','%')"; }
if ($ANGGOTA_NAMA !== '') { $where[] = "a.ANGGOTA_NAMA LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_NAMA) . "','%')"; }
if ($ANGGOTA_AKSES !== '') { $where[] = "a.ANGGOTA_AKSES LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_AKSES) . "','%')"; }
if ($ANGGOTA_STATUS !== '') { $where[] = "a.ANGGOTA_STATUS LIKE CONCAT('%','" . str_replace("'","''",$ANGGOTA_STATUS) . "','%')"; }

// Global search
if ($searchValue !== '') {
    $sv = str_replace("'","''",$searchValue);
    $where[] = "(a.ANGGOTA_ID LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_NAMA LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_RANTING LIKE CONCAT('%','$sv','%') OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$sv','%') OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$sv','%') OR a.ANGGOTA_HP LIKE CONCAT('%','$sv','%'))";
}

$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// recordsTotal (before filters, but respecting role scope)
$whereTotal = [];
if ($USER_AKSES !== 'Administrator') {
    $whereTotal[] = "a.CABANG_KEY = '" . str_replace("'", "''", $USER_CABANG) . "'";
    $whereTotal[] = "a.ANGGOTA_AKSES != 'Administrator'";
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
$select = "SELECT a.*, d.DAERAH_KEY, d.DAERAH_DESKRIPSI, c.CABANG_DESKRIPSI, t.TINGKATAN_NAMA, t.TINGKATAN_GELAR, t.TINGKATAN_SEBUTAN, t.TINGKATAN_LEVEL,
DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') AS TGL_LAHIR,
DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') AS TGL_JOIN,
DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') AS TGL_RESIGN,
RIGHT(a.ANGGOTA_ID,3) AS SHORT_ID,
CASE WHEN a.ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN a.ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END AS STATUS_DES";

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
    // Build Action HTML using same permissions/structure as existing aj_tableanggota.php
    ob_start();
    ?>
    <form method="post" class="form">
        <div class="btn-group" style="margin-bottom:5px;">
            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
                <?php if ($_SESSION['VIEW_DaftarAnggota'] == "Y") { ?>
                <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:#222222;"
                    data-key="<?= h($r['ANGGOTA_KEY']); ?>"
                    data-id="<?= h($r['ANGGOTA_ID']); ?>"
                    data-shortid="<?= h($r['SHORT_ID']); ?>"
                    data-daerahkey="<?= h($r['DAERAH_KEY']); ?>"
                    data-daerahdes="<?= h($r['DAERAH_DESKRIPSI']); ?>"
                    data-cabangkey="<?= h($r['CABANG_KEY']); ?>"
                    data-cabangdes="<?= h($r['CABANG_DESKRIPSI']); ?>"
                    data-tingkatanid="<?= h($r['TINGKATAN_ID']); ?>"
                    data-tingkatannama="<?= h($r['TINGKATAN_NAMA']); ?>"
                    data-ktp="<?= h($r['ANGGOTA_KTP']); ?>"
                    data-nama="<?= h($r['ANGGOTA_NAMA']); ?>"
                    data-alamat="<?= h($r['ANGGOTA_ALAMAT']); ?>"
                    data-pekerjaan="<?= h($r['ANGGOTA_PEKERJAAN']); ?>"
                    data-agama="<?= h($r['ANGGOTA_AGAMA']); ?>"
                    data-kelamin="<?= h($r['ANGGOTA_KELAMIN']); ?>"
                    data-tempatlahir="<?= h($r['ANGGOTA_TEMPAT_LAHIR']); ?>"
                    data-tanggallahir="<?= h($r['ANGGOTA_TANGGAL_LAHIR']); ?>"
                    data-hp="<?= h($r['ANGGOTA_HP']); ?>"
                    data-email="<?= h($r['ANGGOTA_EMAIL']); ?>"
                    data-pic="<?= h($r['ANGGOTA_PIC']); ?>"
                    data-join="<?= h($r['ANGGOTA_JOIN']); ?>"
                    data-resign="<?= h($r['ANGGOTA_RESIGN']); ?>"
                    data-akses="<?= h($r['ANGGOTA_AKSES']); ?>"
                    data-status="<?= h($r['ANGGOTA_STATUS']); ?>"
                    data-statusdes="<?= h($r['STATUS_DES']); ?>"
                    data-ranting="<?= h($r['ANGGOTA_RANTING']); ?>"
                ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                <?php } ?>
                <?php if ($_SESSION['EDIT_DaftarAnggota'] == "Y") { ?>
                <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;"
                    data-key="<?= h($r['ANGGOTA_KEY']); ?>"
                    data-id="<?= h($r['ANGGOTA_ID']); ?>"
                    data-shortid="<?= h($r['SHORT_ID']); ?>"
                    data-daerahkey="<?= h($r['DAERAH_KEY']); ?>"
                    data-daerahdes="<?= h($r['DAERAH_DESKRIPSI']); ?>"
                    data-cabangkey="<?= h($r['CABANG_KEY']); ?>"
                    data-cabangdes="<?= h($r['CABANG_DESKRIPSI']); ?>"
                    data-tingkatanid="<?= h($r['TINGKATAN_ID']); ?>"
                    data-tingkatannama="<?= h($r['TINGKATAN_NAMA']); ?>"
                    data-ktp="<?= h($r['ANGGOTA_KTP']); ?>"
                    data-nama="<?= h($r['ANGGOTA_NAMA']); ?>"
                    data-alamat="<?= h($r['ANGGOTA_ALAMAT']); ?>"
                    data-pekerjaan="<?= h($r['ANGGOTA_PEKERJAAN']); ?>"
                    data-agama="<?= h($r['ANGGOTA_AGAMA']); ?>"
                    data-kelamin="<?= h($r['ANGGOTA_KELAMIN']); ?>"
                    data-tempatlahir="<?= h($r['ANGGOTA_TEMPAT_LAHIR']); ?>"
                    data-tanggallahir="<?= h($r['ANGGOTA_TANGGAL_LAHIR']); ?>"
                    data-hp="<?= h($r['ANGGOTA_HP']); ?>"
                    data-email="<?= h($r['ANGGOTA_EMAIL']); ?>"
                    data-join="<?= h($r['ANGGOTA_JOIN']); ?>"
                    data-resign="<?= h($r['ANGGOTA_RESIGN']); ?>"
                    data-akses="<?= h($r['ANGGOTA_AKSES']); ?>"
                    data-status="<?= h($r['ANGGOTA_STATUS']); ?>"
                    data-statusdes="<?= h($r['STATUS_DES']); ?>"
                    data-ranting="<?= h($r['ANGGOTA_RANTING']); ?>"
                ><span class="ico-edit"></span> Ubah</a></li>
                <?php } ?>
                <?php if (!empty($r['TINGKATAN_LEVEL']) && (int)$r['TINGKATAN_LEVEL'] > 1) { ?>
                <li><a data-toggle="modal" href="#CardId" class="open-CardId" style="color:darkgoldenrod;"
                    data-key="<?= h(encodeIdToBase64($r['ANGGOTA_KEY'])); ?>"
                    data-id="<?= h($r['ANGGOTA_KEY']); ?>"
                    data-id2="<?= h($r['ANGGOTA_ID']); ?>"
                    data-kta="<?= h($r['ANGGOTA_KTA'] ?? ''); ?>"
                    data-nama="<?= h($r['ANGGOTA_NAMA']); ?>"
                ><i class="fa-regular fa-id-card"></i> Kartu ID Anggota</a></li>
                <?php } ?>
                <?php if ($_SESSION['DELETE_DaftarAnggota'] == "Y") { ?>
                <li class="divider"></li>
                <li><a href="#" onclick="deletedaftaranggota('<?= h($r['ANGGOTA_KEY']); ?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                <?php } ?>
            </ul>
        </div>
    </form>
    <?php
    $actionHtml = ob_get_clean();

    // Build data row array in the same order as table headers
    $row = [
        $actionHtml,
        h($r['ANGGOTA_ID']),
        h($r['ANGGOTA_NAMA']),
        h($r['ANGGOTA_TEMPAT_LAHIR']) . ' <br> ' . h($r['TGL_LAHIR']),
        h($r['ANGGOTA_KELAMIN']),
        h($r['TINGKATAN_NAMA']),
        h($r['TINGKATAN_SEBUTAN']),
        h($r['TINGKATAN_GELAR']),
        h($r['ANGGOTA_KTP']),
        h($r['ANGGOTA_HP']),
        h($r['ANGGOTA_EMAIL']),
        h($r['ANGGOTA_RANTING']),
        h($r['CABANG_DESKRIPSI']),
        h($r['DAERAH_DESKRIPSI']),
        h($r['TGL_JOIN']),
        h($r['STATUS_DES']),
        h($r['TGL_RESIGN'])
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
