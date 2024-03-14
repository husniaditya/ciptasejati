<?php
require_once ("../../dashboard/html/module/connection/conn.php");

$getListCabang = GetQuery("SELECT * FROM m_cabang WHERE DELETION_STATUS = 0");

$data = array();
while ($rowListCabang = $getListCabang->fetch(PDO::FETCH_ASSOC)) {
    $location = array(
        'lat' => $rowListCabang["CABANG_LAT"],
        'lng' => $rowListCabang["CABANG_LONG"],
        'title' => $rowListCabang["CABANG_DESKRIPSI"]
    );
    $data[] = $location;
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
