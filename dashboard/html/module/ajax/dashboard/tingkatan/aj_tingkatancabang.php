<?php
require_once("../../../../module/connection/conn.php");

// Retrieve the drilldownId from the AJAX request
$drilldownId = isset($_POST['parentId']) ? $_POST['parentId'] : '';

$TINGKATAN_ID = $_POST['parentId'];

$getDaerah = GetQuery("SELECT c.*,t.TINGKATAN_ID,COUNT(a.TINGKATAN_ID) CTINGKATAN FROM m_tingkatan t
LEFT JOIN m_anggota a ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS null AND t.TINGKATAN_ID = '$TINGKATAN_ID'
GROUP BY t.TINGKATAN_ID
HAVING COUNT(a.ANGGOTA_KEY) > 0");

// Fetch data for the specific drilldownId from the database (replace this with your actual query)
$drilldownData = [];

while ($Daerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
    $drilldownData[] = array(
        'cabang' => $Daerah['CABANG_DESKRIPSI'],
        'cabang_anggota' => (int)$Daerah['CTINGKATAN']
    );
}

// Return drilldown data as JSON
header('Content-Type: application/json');
echo json_encode($drilldownData);
?>
