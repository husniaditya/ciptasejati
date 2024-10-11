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

// Extract input data
$ANGGOTA_ID = $inputData["ANGGOTA_ID"] ?? null;
$PASSWORD = $inputData["PASSWORD"] ?? null;
$CABANG_ID = $inputData["CABANG_ID"] ?? null;

// Validate if necessary data is provided
if (empty($ANGGOTA_ID) || empty($PASSWORD) || empty($CABANG_ID)) {
    sendErrorResponse('Missing or invalid data', 400); // Bad Request
    exit;
}

// Execute first query to check credentials
$GetDetail = GetQuery("call zsp_getAnggota('$ANGGOTA_ID','$CABANG_ID','Anggota')");
$rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC);
$GetDetail->closeCursor();

if (!$rowDetail || !password_verify($PASSWORD, $rowDetail['USER_PASSWORD'])) {
    sendErrorResponse('Wrong Password', 401); // Unauthorized
    exit;
}

// Prepare Anggota details
$ANGGOTA = extractAnggotaDetails($rowDetail);

// Fetch Pengurus and Koordinator details
$PENGURUS = fetchAnggotaDetails($ANGGOTA_ID, $CABANG_ID, 'Pengurus');
$KOORDINATOR = fetchAnggotaDetails($ANGGOTA_ID, $CABANG_ID, 'Koordinator');

// Send success response
$options[] = ['Anggota' => $ANGGOTA, 'Pengurus' => $PENGURUS, 'Koordinator' => $KOORDINATOR];
sendSuccessResponse($options);

/**
 * Fetch Anggota details based on type
 */
function fetchAnggotaDetails($ANGGOTA_ID, $CABANG_ID, $type) {
    $GetDetails = GetQuery("call zsp_getAnggota('$ANGGOTA_ID','$CABANG_ID','$type')");
    $details = [];
    while ($row = $GetDetails->fetch(PDO::FETCH_ASSOC)) {
        $details[] = array(
            "{$type}_ID" => $row['ANGGOTA_ID'],
            "{$type}_NAMA" => $row['ANGGOTA_NAMA'],
            "{$type}_TINGKATAN" => $row['TINGKATAN_NAMA'],
            "{$type}_GELAR" => $row['TINGKATAN_GELAR'],
            "{$type}_SEBUTAN" => $row['TINGKATAN_SEBUTAN'],
            "{$type}_LEVEL" => $row['TINGKATAN_LEVEL'],
            "{$type}_AKSES" => $row['ANGGOTA_AKSES']
        );
    }
    $GetDetails->closeCursor();
    return $details;
}

/**
 * Extract Anggota details from row
 */
function extractAnggotaDetails($row) {
    return array(
        'ANGGOTA_ID' => $row['ANGGOTA_ID'],
        'ANGGOTA_NAMA' => $row['ANGGOTA_NAMA'],
        'ANGGOTA_KELAMIN' => $row['ANGGOTA_KELAMIN'],
        'ANGGOTA_PIC' => $row['ANGGOTA_PIC'],
        'ANGGOTA_JOIN' => $row['ANGGOTA_JOIN'],
        'ANGGOTA_RESIGN' => $row['ANGGOTA_RESIGN'],
        'ANGGOTA_STATUS' => $row['ANGGOTA_STATUS'],
        'TINGKATAN' => $row['TINGKATAN_NAMA'],
        'GELAR' => $row['TINGKATAN_GELAR'],
        'SEBUTAN' => $row['TINGKATAN_SEBUTAN'],
        'LEVEL' => $row['TINGKATAN_LEVEL'],
        'ANGGOTA_AKSES' => $row['ANGGOTA_AKSES'],
    );
}

/**
 * Send success response with data
 */
function sendSuccessResponse($data) {
    echo json_encode(array(
        'result' => array(
            'message' => 'OK',
            'id' => bin2hex(random_bytes(16))
        ),
        'data' => $data
    ));
    http_response_code(200); // OK
}

/**
 * Send error response with message and status code
 */
function sendErrorResponse($message, $code) {
    echo json_encode(array('message' => $message));
    http_response_code($code);
}
?>
