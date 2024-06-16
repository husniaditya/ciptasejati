<?php
require_once("../../../../../module/connection/conn.php");

$CABANG_KEY = $_POST["id"];

$getListAnggota = GetQuery("SELECT * FROM m_anggota WHERE DELETION_STATUS = 0 AND ANGGOTA_RESIGN IS NULL AND ANGGOTA_STATUS = 0 AND CABANG_KEY = '$CABANG_KEY' AND ANGGOTA_AKSES <> 'Administrator' ORDER BY ANGGOTA_NAMA");

$options = array();

while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
    $options[] = array(
        'value' => $ListAnggota['ANGGOTA_ID'],
        'text' => $ListAnggota['ANGGOTA_ID'] . ' - ' .$ListAnggota['ANGGOTA_NAMA']
    );
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($options);
?>
