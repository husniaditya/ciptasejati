<?php
require_once("../../../../module/connection/conn.php");

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

// Column mapping (index 0 is Action, non-orderable)
$columns = [
    'c.CABANG_ID',          // 1
    'd.DAERAH_DESKRIPSI',   // 2
    'c.CABANG_DESKRIPSI',   // 3
    'c.CABANG_SEKRETARIAT', // 4
    'c.CABANG_PENGURUS',    // 5
    'c.CABANG_LAT',         // 6
    'c.CABANG_LONG',        // 7
    'c.CABANG_MAP'          // 8 (map iframe)
];

$orderColIdx = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
$orderDir = isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc' ? 'DESC' : 'ASC';
if ($orderColIdx < 1 || $orderColIdx >= (count($columns)+1)) { $orderColIdx = 1; }
$orderBy = $columns[$orderColIdx-1] . ' ' . $orderDir;

$fromJoin = " FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY ";

$where = [];
// Optional: add status filters if your schema has them, e.g., deletion_status = 0
// $where[] = "c.DELETION_STATUS = 0";

if ($searchValue !== '') {
    $sv = str_replace("'","''", $searchValue);
    $where[] = "(c.CABANG_ID LIKE CONCAT('%','$sv','%')
              OR c.CABANG_DESKRIPSI LIKE CONCAT('%','$sv','%')
              OR c.CABANG_SEKRETARIAT LIKE CONCAT('%','$sv','%')
              OR c.CABANG_PENGURUS LIKE CONCAT('%','$sv','%')
              OR d.DAERAH_DESKRIPSI LIKE CONCAT('%','$sv','%')
              OR c.CABANG_LAT LIKE CONCAT('%','$sv','%')
              OR c.CABANG_LONG LIKE CONCAT('%','$sv','%'))";
}
$whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

// Totals
$sqlTotal = "SELECT COUNT(*) AS cnt $fromJoin"; // adjust if you add base filters
$totalRes = GetQuery($sqlTotal);
$totalRow = $totalRes ? $totalRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsTotal = isset($totalRow['cnt']) ? (int)$totalRow['cnt'] : 0;

$sqlFiltered = "SELECT COUNT(*) AS cnt $fromJoin $whereSql";
$fRes = GetQuery($sqlFiltered);
$fRow = $fRes ? $fRes->fetch(PDO::FETCH_ASSOC) : null;
$recordsFiltered = isset($fRow['cnt']) ? (int)$fRow['cnt'] : 0;

$select = "SELECT c.*, d.DAERAH_DESKRIPSI $fromJoin $whereSql ORDER BY $orderBy LIMIT $start, $length";
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
    // Build Action HTML (match previous non-SSP behavior)
    ob_start();
    $canView = ($_SESSION["VIEW_LokasiCabang"] ?? "N") === "Y";
    $canEdit = ($_SESSION["EDIT_LokasiCabang"] ?? "N") === "Y";
    $canDelete = ($_SESSION["DELETE_LokasiCabang"] ?? "N") === "Y";
    ?>
    <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
        <div class="btn-group" style="margin-bottom:5px;">
            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
                <?php if ($canView) { ?>
                <li><a data-toggle="modal" href="#ViewCabang" class="open-ViewCabang" style="color:#222222;"
                    data-key="<?= h($r['CABANG_KEY']); ?>"
                    data-daerahid="<?= h($r['DAERAH_KEY']); ?>"
                    data-daerahdes="<?= h($r['DAERAH_DESKRIPSI']); ?>"
                    data-cabangid="<?= h($r['CABANG_ID']); ?>"
                    data-shortid="<?= h($r['SHORT_ID'] ?? $r['CABANG_ID']); ?>"
                    data-desk="<?= h($r['CABANG_DESKRIPSI']); ?>"
                    data-pengurus="<?= h($r['CABANG_PENGURUS']); ?>"
                    data-sekre="<?= h($r['CABANG_SEKRETARIAT']); ?>"
                    data-map="<?= h($r['CABANG_MAP']); ?>"
                    data-lat="<?= h($r['CABANG_LAT']); ?>"
                    data-long="<?= h($r['CABANG_LONG']); ?>"
                ><span class="ico-check"></span> Lihat</a></li>
                <?php } ?>
                <?php if ($canEdit) { ?>
                <li><a data-toggle="modal" href="#EditCabang" class="open-EditCabang" style="color:cornflowerblue;"
                    data-key="<?= h($r['CABANG_KEY']); ?>"
                    data-daerahid="<?= h($r['DAERAH_KEY']); ?>"
                    data-daerahdes="<?= h($r['DAERAH_DESKRIPSI']); ?>"
                    data-cabangid="<?= h($r['CABANG_ID']); ?>"
                    data-shortid="<?= h($r['SHORT_ID'] ?? $r['CABANG_ID']); ?>"
                    data-desk="<?= h($r['CABANG_DESKRIPSI']); ?>"
                    data-pengurus="<?= h($r['CABANG_PENGURUS']); ?>"
                    data-sekre="<?= h($r['CABANG_SEKRETARIAT']); ?>"
                    data-map="<?= h($r['CABANG_MAP']); ?>"
                    data-lat="<?= h($r['CABANG_LAT']); ?>"
                    data-long="<?= h($r['CABANG_LONG']); ?>"
                ><span class="ico-edit"></span> Ubah</a></li>
                <?php } ?>
                <?php if ($canDelete) { ?>
                <li class="divider"></li>
                <li><a href="#" onclick="deleteCabang('<?= h($r['CABANG_KEY']); ?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                <?php } ?>
            </ul>
        </div>
    </form>
    <?php
    $actionHtml = ob_get_clean();

    // Map iframe cell
    $mapHtml = '<iframe src="'.h($r['CABANG_MAP']).'" width="250" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

    $row = [
        $actionHtml,
        h($r['CABANG_ID']),
        h($r['DAERAH_DESKRIPSI']),
        h($r['CABANG_DESKRIPSI']),
        h($r['CABANG_SEKRETARIAT']),
        h($r['CABANG_PENGURUS']),
        h($r['CABANG_LAT']),
        h($r['CABANG_LONG']),
        $mapHtml
    ];
    $data[] = $row;
}

header('Content-Type: application/json');
// Strongly discourage caching of SSP responses
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