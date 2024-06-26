<?php
require_once ("../../connection/conn.php");

$ANGGOTA_ID = $_POST["id"];

$getListAnggota = GetQuery("SELECT 
a.*, 
a.ANGGOTA_RANTING, 
DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') AS TGL_LAHIR,
c.CABANG_DESKRIPSI, 
d.DAERAH_DESKRIPSI,
CASE
    WHEN EXISTS (SELECT 1 FROM m_user u WHERE u.ANGGOTA_ID = '$ANGGOTA_ID' AND u.USER_STATUS = 1) THEN 'ID Anggota belum melakukan verifikasi!'
    WHEN (
        (SELECT COUNT(u.ANGGOTA_KEY) 
         FROM m_user u 
         WHERE u.ANGGOTA_ID = '$ANGGOTA_ID') > 0
        AND 
        (SELECT COUNT(*) 
         FROM m_anggota a2 
         WHERE a2.ANGGOTA_ID = '$ANGGOTA_ID' 
           AND a2.ANGGOTA_RESIGN IS NULL 
           AND a2.ANGGOTA_STATUS = 0) > 0
    ) THEN 'ID Anggota sudah terdaftar!'
    WHEN NOT EXISTS (
        SELECT 1 
        FROM m_anggota a3 
        WHERE a3.ANGGOTA_ID = '$ANGGOTA_ID' 
          AND a3.ANGGOTA_RESIGN IS NULL 
          AND a3.ANGGOTA_STATUS = 0
    ) THEN 'ID Anggota tidak terdaftar!'
    ELSE ''
END AS ANGGOTA_REMARKS
FROM 
m_anggota a
LEFT JOIN 
m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN 
m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN 
m_user u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 
WHERE 
a.DELETION_STATUS = 0 
AND a.ANGGOTA_RESIGN IS NULL 
AND a.ANGGOTA_STATUS = 0 
AND a.ANGGOTA_ID = '$ANGGOTA_ID' 
AND a.ANGGOTA_AKSES <> 'Administrator';
");

$data = array();

while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
    if ($ListAnggota["ANGGOTA_REMARKS"] == 'ID Anggota sudah terdaftar!' || $ListAnggota["ANGGOTA_REMARKS"] == 'ID Anggota tidak terdaftar!') {
        $data['ANGGOTA_REMARKS'] = $ListAnggota["ANGGOTA_REMARKS"];
        $data['ANGGOTA_NAMA'] = '';
        $data['ANGGOTA_TTL'] = '';
        $data['ANGGOTA_RANTING'] = '';
        $data['CABANG_KEY'] = '';
        $data['DAERAH_KEY'] = '';
        $data['ANGGOTA_EMAIL'] = '';
    } else {
        $data['ANGGOTA_REMARKS'] = $ListAnggota["ANGGOTA_REMARKS"];
        $data['ANGGOTA_NAMA'] = $ListAnggota["ANGGOTA_NAMA"];
        $data['ANGGOTA_TTL'] = $ListAnggota["ANGGOTA_TEMPAT_LAHIR"] . ", " . $ListAnggota["TGL_LAHIR"];
        $data['ANGGOTA_RANTING'] = $ListAnggota["ANGGOTA_RANTING"];
        $data['CABANG_KEY'] = $ListAnggota["CABANG_DESKRIPSI"];
        $data['DAERAH_KEY'] = $ListAnggota["DAERAH_DESKRIPSI"];
        $data['ANGGOTA_EMAIL'] = $ListAnggota["ANGGOTA_EMAIL"];
    }
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($data);
?>
