<?php
require_once("../../../../../module/connection/conn.php");

// Enforce HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    sendErrorResponse('HTTPS is required', 403);
    exit;
}

// Secure Headers
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Referrer-Policy: no-referrer');
header('Access-Control-Allow-Origin: https://ciptasejatiindonesia.com'); // Replace with your allowed domain
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Api-Key');

// Validate API Key
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;
if (!$apiKey || $apiKey !== '$2y$12$NMxDXU77/MPLgD44nkvdB.jPdB.n5kJLWcYGe8lxBoBiGyk/Jeysu') { // Replace with your API key
    sendErrorResponse('Invalid API key', 401);
    exit;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Invalid request method', 405);
    exit;
}

// Read and decode raw input data
$inputData = json_decode(file_get_contents("php://input"), true);

// Sanitize and validate inputs
$ANGGOTA_ID = htmlspecialchars($inputData['ANGGOTA_ID'] ?? null, ENT_QUOTES, 'UTF-8');
$PASSWORD = $inputData['PASSWORD'] ?? null; // Passwords should not be sanitized, use as is
$CABANG_ID = $inputData['CABANG_ID'] ?? null;

// Validate CABANG_ID format (e.g., "001.003.003")
if ($CABANG_ID && !preg_match('/^\d{3}\.\d{3}\.\d{3}$/', $CABANG_ID)) {
    $CABANG_ID = null;
}

// Ensure null values are properly handled
$ANGGOTA_ID = $ANGGOTA_ID !== '' ? $ANGGOTA_ID : null;
$CABANG_ID = $CABANG_ID !== '' ? $CABANG_ID : null;

if (!$ANGGOTA_ID || !$PASSWORD || !$CABANG_ID) {
    sendErrorResponse('Missing or invalid data', 400);
    exit;
}


// Execute first query to check credentials securely
$GetDetail = GetQuery2("CALL zsp_getAnggota(:ANGGOTA_ID, :CABANG_ID, 'Anggota')", [
    ':ANGGOTA_ID' => $ANGGOTA_ID,
    ':CABANG_ID' => $CABANG_ID
]);
$rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC);
$GetDetail->closeCursor();

if (!$rowDetail || !password_verify($PASSWORD, $rowDetail['USER_PASSWORD'])) {
    sendErrorResponse('Invalid credentials', 401);
    exit;
}

// Fetch details
$ANGGOTA = fetchDetails($ANGGOTA_ID, $CABANG_ID, 'Anggota');
$PENGURUS = fetchDetails($ANGGOTA_ID, $CABANG_ID, 'Pengurus');
$KOORDINATOR = fetchDetails($ANGGOTA_ID, $CABANG_ID, 'Koordinator');
$KAS = fetchKasDetails($ANGGOTA_ID, $CABANG_ID);

// Send success response
sendSuccessResponse([
    'Anggota' => $ANGGOTA,
    'Pengurus' => $PENGURUS,
    'Koordinator' => $KOORDINATOR,
    'Kas' => $KAS
]);

/**
 * Fetch details for Anggota, Pengurus, or Koordinator
 */
function fetchDetails($ANGGOTA_ID, $CABANG_ID, $type) {
    $query = "CALL zsp_getAnggota(:ANGGOTA_ID, :CABANG_ID, :type)";
    $params = [
        ':ANGGOTA_ID' => $ANGGOTA_ID,
        ':CABANG_ID' => $CABANG_ID,
        ':type' => $type
    ];
    $GetDetails = GetQuery2($query, $params);
    $details = [];
    while ($row = $GetDetails->fetch(PDO::FETCH_ASSOC)) {
        $details[] = [
            "{$type}_ID" => $row['ANGGOTA_ID'],
            "{$type}_NAMA" => $row['ANGGOTA_NAMA'],
            "{$type}_TINGKATAN" => $row['TINGKATAN_NAMA'],
            "{$type}_GELAR" => $row['TINGKATAN_GELAR'],
            "{$type}_SEBUTAN" => $row['TINGKATAN_SEBUTAN'],
            "{$type}_LEVEL" => $row['TINGKATAN_LEVEL'],
            "{$type}_AKSES" => $row['ANGGOTA_AKSES']
        ];
    }
    $GetDetails->closeCursor();
    return $details;
}

/**
 * Fetch Kas details
 */
function fetchKasDetails($ANGGOTA_ID, $CABANG_ID) {
    $query = "CALL zsp_getAnggota(:ANGGOTA_ID, :CABANG_ID, 'Kas')";
    $params = [
        ':ANGGOTA_ID' => $ANGGOTA_ID,
        ':CABANG_ID' => $CABANG_ID
    ];
    $GetDetails = GetQuery2($query, $params);
    $details = [];
    while ($row = $GetDetails->fetch(PDO::FETCH_ASSOC)) {
        $details[] = [
            'KAS_ID' => $row['KAS_ID'],
            'KAS_CABANG' => $row['CABANG_DESKRIPSI'] . ' - ' . $row['DAERAH_DESKRIPSI'],
            'KAS_TANGGAL' => $row['KAS_TANGGAL'],
            'KAS_JENIS' => $row['KAS_JENIS'],
            'KAS_DK' => $row['KAS_DK'],
            'KAS_JUMLAH' => $row['KAS_JUMLAH'],
            'SALDO_AKHIR' => $row['SALDO_AKHIR']
        ];
    }
    $GetDetails->closeCursor();
    return $details;
}

/**
 * Send a success response
 */
function sendSuccessResponse($data) {
    http_response_code(200);
    echo json_encode([
        'result' => [
            'message' => 'OK',
            'id' => bin2hex(random_bytes(16))
        ],
        'data' => $data
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Send an error response
 */
function sendErrorResponse($message, $code) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
}
?>
