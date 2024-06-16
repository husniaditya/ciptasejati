<?php
require_once("../../../../../module/connection/conn.php");

$PPD_ID = $_POST["PPD_ID"];

$GetDetail = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,d2.DAERAH_KEY LOKASI_DAERAH_KEY,d2.DAERAH_DESKRIPSI LOKASI_DAERAH,c2.CABANG_KEY LOKASI_CABANG_KEY,c2.CABANG_DESKRIPSI LOKASI_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,
CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS_DESKRIPSI
FROM t_ppd p
LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
WHERE p.PPD_ID = '$PPD_ID'");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['PPD_ID'] = $rowDetail["PPD_ID"];
    $data['PPD_TANGGAL'] = $rowDetail["PPD_TANGGAL"];
    $data['PPD_JENIS'] = $rowDetail["PPD_JENIS"];
    $data['PPD_JENIS_DESKRIPSI'] = $rowDetail["PPD_JENIS_DESKRIPSI"];
    $data['PPD_LOKASI'] = $rowDetail["PPD_LOKASI"];
    $data['PPD_DESKRIPSI'] = $rowDetail["PPD_DESKRIPSI"];
    $data['PPD_FILE'] = $rowDetail["PPD_FILE"];
    $data['PPD_APPROVE_PELATIH'] = $rowDetail["PPD_APPROVE_PELATIH"];
    $data['PPD_APPROVE_PELATIH_TGL'] = $rowDetail["PPD_APPROVE_PELATIH_TGL"];
    $data['PPD_APPROVE_GURU'] = $rowDetail["PPD_APPROVE_GURU"];
    $data['PPD_APPROVE_GURU_TGL'] = $rowDetail["PPD_APPROVE_GURU_TGL"];
    $data['CABANG_KEY'] = $rowDetail["CABANG_KEY"];
    $data['CABANG_DESKRIPSI'] = $rowDetail["CABANG_DESKRIPSI"];
    $data['DAERAH_KEY'] = $rowDetail["DAERAH_KEY"];
    $data['DAERAH_DESKRIPSI'] = $rowDetail["DAERAH_DESKRIPSI"];
    $data['LOKASI_DAERAH_KEY'] = $rowDetail["LOKASI_DAERAH_KEY"];
    $data['LOKASI_DAERAH'] = $rowDetail["LOKASI_DAERAH"];
    $data['LOKASI_CABANG_KEY'] = $rowDetail["LOKASI_CABANG_KEY"];
    $data['LOKASI_CABANG'] = $rowDetail["LOKASI_CABANG"];
    $data['TINGKATAN_ID'] = $rowDetail["TINGKATAN_ID"];
    $data['TINGKATAN_NAMA'] = $rowDetail["TINGKATAN_NAMA"];
    $data['TINGKATAN_SEBUTAN'] = $rowDetail["TINGKATAN_SEBUTAN"];
    $data['ANGGOTA_ID'] = $rowDetail["ANGGOTA_ID"];
    $data['ANGGOTA_NAMA'] = $rowDetail["ANGGOTA_NAMA"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
