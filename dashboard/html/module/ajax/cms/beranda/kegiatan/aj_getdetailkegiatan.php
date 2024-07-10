<?php
require_once("../../../../../module/connection/conn.php");

$KEGIATAN_ID = $_POST["id"];

$GetDetail = GetQuery("SELECT k.*,case when k.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END KEGIATAN_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_kegiatan k 
LEFT JOIN m_anggota a ON k.INPUT_BY = a.ANGGOTA_ID
WHERE KEGIATAN_ID = '$KEGIATAN_ID'");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['KEGIATAN_ID'] = $rowDetail["KEGIATAN_ID"];
    $data['KEGIATAN_JUDUL'] = $rowDetail["KEGIATAN_JUDUL"];
    $data['KEGIATAN_DESKRIPSI'] = $rowDetail["KEGIATAN_DESKRIPSI"];
    $data['DELETION_STATUS'] = $rowDetail["DELETION_STATUS"];
    $data['KEGIATAN_STATUS'] = $rowDetail["KEGIATAN_STATUS"];
    $data['KEGIATAN_IMAGE'] = $rowDetail["KEGIATAN_IMAGE"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
