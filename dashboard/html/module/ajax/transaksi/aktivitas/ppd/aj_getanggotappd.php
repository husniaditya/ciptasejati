<?php
require_once("../../../../../module/connection/conn.php");

// Read raw input data
$inputData = json_decode(file_get_contents("php://input"), true);

// Access the input data safely
$TINGKATAN_ID = isset($inputData["TINGKATAN_ID"]) ? $inputData["TINGKATAN_ID"] : null;
$PPD_JENIS = isset($inputData["PPD_JENIS"]) ? $inputData["PPD_JENIS"] : null;
$CABANG_KEY = isset($inputData["CABANG_KEY"]) ? $inputData["CABANG_KEY"] : null;

// Proceed only if PPD_JENIS is 0
if (!empty($TINGKATAN_ID)) {
    // Fetch Tingkatan Level
    $getTingkatanLevel = GetQuery("SELECT TINGKATAN_LEVEL FROM m_tingkatan WHERE TINGKATAN_ID = '$TINGKATAN_ID'");
    $TingkatanLevel = $getTingkatanLevel->fetch(PDO::FETCH_ASSOC);
    
    if (!$TingkatanLevel) {
        echo json_encode(array('error' => 'Tingkatan Level not found'));
        exit;
    }

    $TINGKATAN_LEVEL = $TingkatanLevel['TINGKATAN_LEVEL'];
    
    if ($PPD_JENIS == "0") {
        // Fetch List of Anggota
        $getListAnggota = GetQuery("SELECT a.*, t.TINGKATAN_NAMA FROM m_anggota a
            LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
            WHERE t.TINGKATAN_LEVEL = ($TINGKATAN_LEVEL - 1) AND a.ANGGOTA_STATUS = 0 
            AND a.DELETION_STATUS = 0 AND t.DELETION_STATUS = 0 
            AND a.ANGGOTA_AKSES <> 'Administrator' 
            AND a.CABANG_KEY = '$CABANG_KEY' 
            ORDER BY a.ANGGOTA_NAMA ASC");
    } else {
        // Fetch List of Anggota
        $getListAnggota = GetQuery("SELECT a.*, t.TINGKATAN_NAMA FROM m_anggota a
            LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
            WHERE t.TINGKATAN_ID = '$TINGKATAN_ID' AND a.ANGGOTA_STATUS = 0 
            AND a.DELETION_STATUS = 0 AND t.DELETION_STATUS = 0 
            AND a.ANGGOTA_AKSES <> 'Administrator' 
            AND a.CABANG_KEY = '$CABANG_KEY' 
            ORDER BY a.ANGGOTA_NAMA ASC");
    }

    $options = array();

    // Build options array with Anggota data
    while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
        $options[] = array(
            'value' => $ListAnggota['ANGGOTA_ID'],
            'text' => $ListAnggota['ANGGOTA_ID'] . ' - ' . $ListAnggota['ANGGOTA_NAMA']
        );
    }

    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    $response = array(
        'result' => array(
            'message' => 'OK',
            'id' => bin2hex(random_bytes(16))
        ),
        'data' => $options
    );
    
    // Return the response in JSON format
    echo json_encode($response);
} else {
    echo json_encode(array('message' => 'Tingkatan ID is missing or invalid'));
}
?>
