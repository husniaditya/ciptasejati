<?php
require_once("../../../../../module/connection/conn.php");

$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$TINGKATAN_ID = $_POST["TINGKATAN_ID"];
if ($USER_AKSES == "Administrator") {
    $CABANG_KEY = $_POST["CABANG_KEY"];
} else {
    $CABANG_KEY = $USER_CABANG;
}

if ($TINGKATAN_ID <> "" && $CABANG_KEY <> "") {
    $getTingkatanLevel = GetQuery("SELECT TINGKATAN_LEVEL FROM m_tingkatan WHERE TINGKATAN_ID = '$TINGKATAN_ID'");
    while ($TingkatanLevel = $getTingkatanLevel->fetch(PDO::FETCH_ASSOC)) {
        extract($TingkatanLevel);
    }

    $getListAnggota = GetQuery("SELECT a.*,t.TINGKATAN_NAMA FROM m_anggota a
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE t.TINGKATAN_LEVEL = ($TINGKATAN_LEVEL-1) AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 AND t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.CABANG_KEY = '$CABANG_KEY' ORDER BY a.ANGGOTA_NAMA ASC");

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
} else {
    echo json_encode(array('message' => 'Data tidak ditemukan'));
}
?>
