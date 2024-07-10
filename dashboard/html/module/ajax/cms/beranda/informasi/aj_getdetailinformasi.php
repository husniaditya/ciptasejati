<?php
require_once("../../../../../module/connection/conn.php");

$INFORMASI_ID = $_POST["id"];

$GetDetail = GetQuery("SELECT i.*,case when i.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END INFORMASI_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(i.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_informasi i 
LEFT JOIN m_anggota a ON i.INPUT_BY = a.ANGGOTA_ID
WHERE INFORMASI_ID = '$INFORMASI_ID'");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['INFORMASI_ID'] = $rowDetail["INFORMASI_ID"];
    $data['INFORMASI_JUDUL'] = $rowDetail["INFORMASI_JUDUL"];
    $data['INFORMASI_KATEGORI'] = $rowDetail["INFORMASI_KATEGORI"];
    $data['INFORMASI_DESKRIPSI'] = $rowDetail["INFORMASI_DESKRIPSI"];
    $data['DELETION_STATUS'] = $rowDetail["DELETION_STATUS"];
    $data['INFORMASI_STATUS'] = $rowDetail["INFORMASI_STATUS"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
