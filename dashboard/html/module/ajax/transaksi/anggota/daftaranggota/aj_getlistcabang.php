<?php
require_once("../../../../../module/connection/conn.php");

$DAERAH_KEY = $_POST["id"];

$getListCab = GetQuery("SELECT * FROM m_cabang WHERE DELETION_STATUS = 0 AND DAERAH_KEY = '$DAERAH_KEY' order by CABANG_DESKRIPSI asc");

$options = array();

while ($ListCab = $getListCab->fetch(PDO::FETCH_ASSOC)) {
    $options[] = array(
        'value' => $ListCab['CABANG_KEY'],
        'text' => $ListCab['CABANG_DESKRIPSI']
    );
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded array
echo json_encode($options);
?>
