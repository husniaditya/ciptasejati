<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$ANGGOTA_ID=$_POST["id"];

$getUser = GetQuery("SELECT u.ANGGOTA_KEY,u.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_RANTING,c.CABANG_DESKRIPSI,d.DAERAH_DESKRIPSI,a.ANGGOTA_AKSES,a.ANGGOTA_PIC, CASE WHEN u.USER_STATUS = 1 THEN 'Verifikasi' ELSE 'Aktif' END USER_STATUS, u.INPUT_BY, DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM m_user u
LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE a.DELETION_STATUS = 0 AND u.ANGGOTA_ID = '$ANGGOTA_ID'"); 

$data = array();
while ($rowUser = $getUser->fetch(PDO::FETCH_ASSOC)) {
    $data['DAERAH_DESKRIPSI'] = $rowUser["DAERAH_DESKRIPSI"];
    $data['CABANG_DESKRIPSI'] = $rowUser["CABANG_DESKRIPSI"];
    $data['ANGGOTA_RANTING'] = $rowUser["ANGGOTA_RANTING"];
    $data['ANGGOTA_TINGKATAN'] = $rowUser["TINGKATAN_NAMA"] .' - '. $rowUser["TINGKATAN_SEBUTAN"];
    $data['ANGGOTA_ID'] = $rowUser["ANGGOTA_ID"];
    $data['ANGGOTA_NAMA'] = $rowUser["ANGGOTA_NAMA"];
    $data['ANGGOTA_AKSES'] = $rowUser["ANGGOTA_AKSES"];
    $data['ANGGOTA_PIC'] = $rowUser["ANGGOTA_PIC"];
    $data['USER_STATUS'] = $rowUser["USER_STATUS"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
