<?php
require_once ("../../connection/conn.php");

$ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
$CABANG_KEY = $_POST["CABANG_KEY"];

$GetDetail = GetQuery("SELECT a.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 AND  a.ANGGOTA_KEY = '$ANGGOTA_KEY'");

$data = array();

while ($detail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    extract($detail);

    $GetKas = GetQuery("SELECT SUM(KAS_JUMLAH) AS KAS_ANGGOTA 
    FROM t_kas 
    WHERE ANGGOTA_KEY = '$ANGGOTA_KEY'");

    while ($kas = $GetKas->fetch(PDO::FETCH_ASSOC)) {
        extract($kas);
    }
    
    // Set the values for the array of data Profile
    $data['ANGGOTA_NAMA'] = $detail["ANGGOTA_NAMA"];
    $data['ANGGOTA_PIC'] = $detail["ANGGOTA_PIC"];
    $data['ANGGOTA_TEMPAT_LAHIR'] = $detail["ANGGOTA_TEMPAT_LAHIR"];
    $data['ANGGOTA_TANGGAL_LAHIR'] = $detail["ANGGOTA_TANGGAL_LAHIR"];
    $data['ANGGOTA_AGAMA'] = $detail["ANGGOTA_AGAMA"];
    $data['ANGGOTA_KELAMIN'] = $detail["ANGGOTA_KELAMIN"];
    $data['ANGGOTA_KTP'] = $detail["ANGGOTA_KTP"];
    $data['ANGGOTA_ALAMAT'] = $detail["ANGGOTA_ALAMAT"];
    $data['ANGGOTA_PEKERJAAN'] = $detail["ANGGOTA_PEKERJAAN"];
    $data['ANGGOTA_HP'] = $detail["ANGGOTA_HP"];
    $data['ANGGOTA_RANTING'] = $detail["ANGGOTA_RANTING"];
    $data['CABANG_KEY'] = $detail["CABANG_DESKRIPSI"];
    $data['DAERAH_KEY'] = $detail["DAERAH_DESKRIPSI"];
    $data['ANGGOTA_EMAIL'] = $detail["ANGGOTA_EMAIL"];
    // Handle the case where KAS_ANGGOTA is NULL
    $data['KAS_ANGGOTA'] = isset($kas['KAS_ANGGOTA']) ? $kas['KAS_ANGGOTA'] : 0;
    
    // Set the values for the array of data Member
    $data['ANGGOTA_ID'] = $detail["ANGGOTA_ID"];
    $data['TINGKATAN'] = $detail["TINGKATAN_NAMA"] . " - " . $detail["TINGKATAN_SEBUTAN"];
    $data['ANGGOTA_RANTING'] = $detail["ANGGOTA_RANTING"];
    $data['CABANG_DESKRIPSI'] = $detail["CABANG_DESKRIPSI"];
    $data['DAERAH_DESKRIPSI'] = $detail["DAERAH_DESKRIPSI"];
    $data['ANGGOTA_EMAIL'] = $detail["ANGGOTA_EMAIL"];
    $data['ANGGOTA_HP'] = $detail["ANGGOTA_HP"];

}
// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($data);
?>