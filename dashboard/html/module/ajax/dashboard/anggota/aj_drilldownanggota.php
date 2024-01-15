<?php
require_once("../../../../module/connection/conn.php");
// aj_drilldownanggota.php

// Retrieve the drilldownId from the AJAX request
$drilldownId = isset($_POST['drilldownId']) ? $_POST['drilldownId'] : '';
$DAERAH_KEY = $_POST['drilldownId'];

$getCabang = GetQuery("SELECT c.*,COUNT(a.ANGGOTA_KEY) CANGGOTA FROM m_cabang c
LEFT JOIN m_anggota a ON c.CABANG_KEY = a.CABANG_KEY
WHERE c.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS NULL AND c.DAERAH_KEY = '$DAERAH_KEY'
GROUP BY c.CABANG_KEY
HAVING COUNT(a.ANGGOTA_KEY) > 0"); 

// Fetch data for the specific drilldownId from the database (replace this with your actual query)
$drilldownData = [];

while ($Cabang = $getCabang->fetch(PDO::FETCH_ASSOC)) {
    $drilldownData[] = array(
        'cabang' => $Cabang['CABANG_DESKRIPSI'],
        'cabang_anggota' => (int)$Cabang['CANGGOTA']
    );
}

// Return drilldown data as JSON
header('Content-Type: application/json');
echo json_encode($drilldownData);
?>
