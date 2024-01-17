<?php
require_once("../../../../module/connection/conn.php");
// aj_drilldownanggota.php

// Retrieve the drilldownId from the AJAX request
$drilldownId = isset($_POST['drilldownId']) ? $_POST['drilldownId'] : '';
$ID = $_POST['drilldownId'];

$array = explode(",", $ID);
$CABANG_KEY = isset($array[0]) ? $array[0] : '';
$CABANG_ID = isset($array[1]) ? $array[1] : '';

if ($CABANG_ID != '') {
    $CABANG_KEY = $CABANG_KEY;
    $getCabang = GetQuery("SELECT t.*,COUNT(a.ANGGOTA_KEY) CANGGOTA FROM m_tingkatan t
    LEFT JOIN m_anggota a ON t.TINGKATAN_ID = a.TINGKATAN_ID
    WHERE t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS NULL AND a.CABANG_KEY = '$CABANG_KEY'
    GROUP BY t.TINGKATAN_ID
    HAVING COUNT(a.ANGGOTA_KEY) > 0");
} else {
    $DAERAH_KEY = $ID;
    $getCabang = GetQuery("SELECT c.*,COUNT(a.ANGGOTA_KEY) CANGGOTA FROM m_cabang c
    LEFT JOIN m_anggota a ON c.CABANG_KEY = a.CABANG_KEY
    WHERE c.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS NULL AND c.DAERAH_KEY = '$DAERAH_KEY'
    GROUP BY c.CABANG_KEY
    HAVING COUNT(a.ANGGOTA_KEY) > 0"); 
}

// Fetch data for the specific drilldownId from the database (replace this with your actual query)
$drilldownData = [];

while ($Cabang = $getCabang->fetch(PDO::FETCH_ASSOC)) {
    if ($CABANG_ID != '') {
        $drilldownData[] = array(
            'name' => $Cabang['TINGKATAN_NAMA'],
            'y' => (int)$Cabang['CANGGOTA'],
        );
    } else {
        $drilldownData[] = array(
            'name' => $Cabang['CABANG_DESKRIPSI'],
            'y' => (int)$Cabang['CANGGOTA'],
            'drilldown' => $Cabang['CABANG_KEY'].','.$Cabang['CABANG_ID']
        );
    }
}

// Return drilldown data as JSON
echo json_encode($drilldownData);
?>
