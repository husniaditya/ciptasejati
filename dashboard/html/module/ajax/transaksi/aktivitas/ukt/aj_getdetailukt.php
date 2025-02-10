<?php
require_once("../../../../../module/connection/conn.php");

// Read raw input data
$inputData = json_decode(file_get_contents("php://input"), true);

$UKT_ID = isset($inputData["id"]) ? $inputData["id"] : null;

$GetDetail = GetQuery("SELECT u.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,t2.TINGKATAN_NAMA UKT_TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN UKT_TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL_DESKRIPSI,
    CASE
    WHEN u.UKT_TOTAL >= 85 THEN 'fa-solid fa-a'
    WHEN u.UKT_TOTAL >= 75 THEN 'fa-solid fa-b'
    WHEN u.UKT_TOTAL >= 60 THEN 'fa-solid fa-c'
    WHEN u.UKT_TOTAL >= 40 THEN 'fa-solid fa-d'
    ELSE 'fa-solid fa-e' END UKT_NILAI
    FROM t_ukt u
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
    LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY AND a2.ANGGOTA_STATUS = 0
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON t2.TINGKATAN_ID = u.TINGKATAN_ID
    WHERE u.DELETION_STATUS = 0 AND u.UKT_ID = '$UKT_ID'
    ORDER BY u.UKT_ID DESC");


if (!empty($UKT_ID)) {
    while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
        $options[] = array(
            'UKT_ID' => $rowDetail["UKT_ID"],
            'UKT_TANGGAL' => $rowDetail["UKT_TANGGAL"],
            'UKT_TANGGAL_DESKRIPSI' => $rowDetail["UKT_TANGGAL_DESKRIPSI"],
            'ANGGOTA_ID' => $rowDetail["ANGGOTA_ID"],
            'ANGGOTA_NAMA' => $rowDetail["ANGGOTA_NAMA"],
            'TINGKATAN_ID' => $rowDetail["TINGKATAN_ID"],
            'TINGKATAN_NAMA' => $rowDetail["TINGKATAN_NAMA"],
            'TINGKATAN_SEBUTAN' => $rowDetail["TINGKATAN_SEBUTAN"],
            'UKT_TINGKATAN_NAMA' => $rowDetail["UKT_TINGKATAN_NAMA"],
            'UKT_TINGKATAN_SEBUTAN' => $rowDetail["UKT_TINGKATAN_SEBUTAN"],
            'DAERAH_KEY' => $rowDetail["DAERAH_KEY"],
            'DAERAH_DESKRIPSI' => $rowDetail["DAERAH_DESKRIPSI"],
            'UKT_DAERAH' => $rowDetail["UKT_DAERAH"],
            'CABANG_KEY' => $rowDetail["CABANG_KEY"],
            'CABANG_DESKRIPSI' => $rowDetail["CABANG_DESKRIPSI"],
            'UKT_LOKASI' => $rowDetail["UKT_LOKASI"],
            'UKT_CABANG' => $rowDetail["UKT_CABANG"],
            'UKT_DESKRIPSI' => $rowDetail["UKT_DESKRIPSI"],
            'UKT_TOTAL' => $rowDetail["UKT_TOTAL"],
            'UKT_NILAI' => $rowDetail["UKT_NILAI"]
        );
    }

    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    $response = array(
        'result' => array(
            'message' => 'OK',
            'id' => bin2hex(random_bytes(length: 16))
        ),
        'data' => $options
    );
        
    // Return the response in JSON format
    echo json_encode($response);
} else {
    echo json_encode(array('message' => 'PPD ID is missing or invalid'));
}
?>
