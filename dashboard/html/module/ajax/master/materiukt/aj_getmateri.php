<?php
require_once ("../../../../module/connection/conn.php");

$MATERI_ID = $_POST["MATERI_ID"];

$GetDetail = GetQuery("SELECT m.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
    FROM m_materi m
    LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
    WHERE m.MATERI_ID = '$MATERI_ID'");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['MATERI_ID'] = $rowDetail["MATERI_ID"];
    $data['DAERAH_KEY'] = $rowDetail["DAERAH_KEY"];
    $data['DAERAH_DESKRIPSI'] = $rowDetail["DAERAH_DESKRIPSI"];
    $data['CABANG_KEY'] = $rowDetail["CABANG_KEY"];
    $data['CABANG_DESKRIPSI'] = $rowDetail["CABANG_DESKRIPSI"];
    $data['MATERI_DESKRIPSI'] = $rowDetail["MATERI_DESKRIPSI"];
    $data['MATERI_BOBOT'] = $rowDetail["MATERI_BOBOT"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
