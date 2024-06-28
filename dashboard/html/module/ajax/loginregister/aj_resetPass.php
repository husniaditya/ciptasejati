<?php
require_once ("../../connection/conn.php");

$ANGGOTA_ID = $_POST["id"];

$getListAnggota = GetQuery("SELECT a.*,a.ANGGOTA_RANTING,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,c.CABANG_DESKRIPSI,d.DAERAH_DESKRIPSI,
CASE
WHEN (SELECT 1 FROM m_user u WHERE u.ANGGOTA_ID = '$ANGGOTA_ID' AND u.USER_STATUS = 1) = 1 THEN 'ID Anggota belum melakukan verifikasi. Silahkan verifikasi melalui halaman daftar user!'
WHEN (SELECT 1 FROM m_user u WHERE u.ANGGOTA_ID = '$ANGGOTA_ID' AND u.USER_STATUS = 0) IS null THEN 'ID Anggota tidak terdaftar. Silahkan melakukan daftar user melalui halaman daftar user!'
WHEN a.ANGGOTA_ID IS NULL AND (SELECT 1 FROM m_anggota a WHERE a.ANGGOTA_ID = '$ANGGOTA_ID' AND a.ANGGOTA_RESIGN IS NULL AND a.ANGGOTA_STATUS = 0) IS NULL then 'ID Anggota tidak terdaftar!'
ELSE ''
END ANGGOTA_REMARKS
FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_user u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0
WHERE a.DELETION_STATUS = 0 AND a.ANGGOTA_RESIGN IS NULL AND a.ANGGOTA_STATUS = 0 AND a.ANGGOTA_ID = '$ANGGOTA_ID' AND a.ANGGOTA_AKSES <> 'Administrator'");

$data = array();

if ($getListAnggota->rowCount() > 0) {
    while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
            $data['ANGGOTA_REMARKS'] = $ListAnggota["ANGGOTA_REMARKS"];
            $data['ANGGOTA_NAMA'] = $ListAnggota["ANGGOTA_NAMA"];
            $data['ANGGOTA_TTL'] = $ListAnggota["ANGGOTA_TEMPAT_LAHIR"] . ", " . $ListAnggota["TGL_LAHIR"];
            $data['ANGGOTA_RANTING'] = $ListAnggota["ANGGOTA_RANTING"];
            $data['CABANG_KEY'] = $ListAnggota["CABANG_DESKRIPSI"];
            $data['DAERAH_KEY'] = $ListAnggota["DAERAH_DESKRIPSI"];
            $data['ANGGOTA_EMAIL'] = $ListAnggota["ANGGOTA_EMAIL"];
    }
}
else {
    $data['ANGGOTA_REMARKS'] = 'ID Anggota tidak terdaftar!';
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($data);
?>
