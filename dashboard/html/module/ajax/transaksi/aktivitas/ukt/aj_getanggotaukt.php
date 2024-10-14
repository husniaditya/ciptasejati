<?php
require_once("../../../../../module/connection/conn.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Invalid request method', 405); // Method Not Allowed
    exit;
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Read raw input data
$inputData = json_decode(file_get_contents("php://input"), true);

// Access the input data safely
$CABANG_KEY = $inputData["CABANG_KEY"] ?? null;

// Validate if necessary data is provided
if (empty($CABANG_KEY)) {
    sendErrorResponse('Missing or invalid data', 400); // Bad Request
    exit;
}

$getListAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 AND DELETION_STATUS = 0 AND CABANG_KEY = '$CABANG_KEY' ORDER BY ANGGOTA_NAMA ASC");

$options = array();

while ($ListAnggota = $getListAnggota->fetch(PDO::FETCH_ASSOC)) {
    $options[] = array(
        'value' => $ListAnggota['ANGGOTA_ID'],
        'text' => $ListAnggota['ANGGOTA_ID'] . ' - ' .$ListAnggota['ANGGOTA_NAMA']
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
?>
