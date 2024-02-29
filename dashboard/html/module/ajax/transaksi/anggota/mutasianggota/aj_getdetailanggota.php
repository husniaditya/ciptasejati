<?php
require_once("../../../../../module/connection/conn.php");

$ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];

$GetDetail = GetQuery("SELECT a.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE a.ANGGOTA_KEY = '$ANGGOTA_KEY'");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['DAERAH_DESKRIPSI'] = $rowDetail["DAERAH_DESKRIPSI"];
    $data['CABANG_KEY'] = $rowDetail["CABANG_KEY"];
    $data['CABANG_DESKRIPSI'] = $rowDetail["CABANG_DESKRIPSI"];
    $data['TINGKATAN_ID'] = $rowDetail["TINGKATAN_ID"];
    $data['TINGKATAN_NAMA'] = $rowDetail["TINGKATAN_NAMA"];
    $data['TINGKATAN_SEBUTAN'] = $rowDetail["TINGKATAN_SEBUTAN"];
    $data['ANGGOTA_RANTING'] = $rowDetail["ANGGOTA_RANTING"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
