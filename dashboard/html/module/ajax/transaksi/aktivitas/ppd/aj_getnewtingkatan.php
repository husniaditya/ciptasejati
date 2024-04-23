<?php
require_once("../../../../../module/connection/conn.php");

$TINGKATAN_ID = $_POST["TINGKATAN_ID"];
$CABANG_KEY = $_POST["CABANG_KEY"];

$getTingkatanLevel = GetQuery("SELECT TINGKATAN_LEVEL FROM m_tingkatan WHERE TINGKATAN_ID = '$TINGKATAN_ID'");
while ($TingkatanLevel = $getTingkatanLevel->fetch(PDO::FETCH_ASSOC)) {
    extract($TingkatanLevel);
}

$getListAnggota = GetQuery("SELECT a.*,t.TINGKATAN_NAMA FROM m_anggota a
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE t.TINGKATAN_LEVEL = ($TINGKATAN_LEVEL-1) AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 AND t.DELETION_STATUS = 0");

$options = array();

while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
    $options[] = array(
        'value' => $ListAnggota['ANGGOTA_KEY'],
        'text' => $ListAnggota['ANGGOTA_ID'] . ' - ' .$ListAnggota['ANGGOTA_NAMA']
    );
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($options);
?>
