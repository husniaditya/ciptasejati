<?php
require_once("../../../../module/connection/conn.php");

// Fetch data from the database
$getStatus = GetQuery("SELECT 
    t.TINGKATAN_NAMA,
    SUM(CASE WHEN a.ANGGOTA_STATUS = 0 THEN 1 ELSE 0 END) AS 'AKTIF',
    SUM(CASE WHEN a.ANGGOTA_STATUS <> 0 THEN 1 ELSE 0 END) AS 'TidakAktif'
    FROM 
    m_tingkatan t
    LEFT JOIN 
    m_anggota a ON a.TINGKATAN_ID = t.TINGKATAN_ID 
    WHERE 
    t.DELETION_STATUS = 0 AND a.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator'
    GROUP BY 
    t.TINGKATAN_NAMA
    ORDER BY t.TINGKATAN_LEVEL"
);

// Prepare data for JavaScript
$data = array(
    'xAxisCategories' => array(),
    'seriesDataAktif' => array('name' => 'Aktif', 'data' => array()),
    'seriesDataTidakAktif' => array('name' => 'Tidak Aktif', 'data' => array())
);

while ($Status = $getStatus->fetch(PDO::FETCH_ASSOC)) {
    // Add category to xAxisCategories
    $data['xAxisCategories'][] = $Status['TINGKATAN_NAMA'];

    // Add data to series
    $data['seriesDataAktif']['data'][] = (int)$Status['AKTIF'];
    $data['seriesDataTidakAktif']['data'][] = (int)$Status['TidakAktif'];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
