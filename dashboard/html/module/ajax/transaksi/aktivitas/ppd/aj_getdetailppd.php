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

// Sanitize and validate input
$PPD_ID = htmlspecialchars($inputData["PPD_ID"] ?? '', ENT_QUOTES, 'UTF-8');
$PPD_ID = $PPD_ID !== '' ? $PPD_ID : null;

if (!$PPD_ID) {
    sendErrorResponse('Missing or invalid data', 400);
    exit;
}

$GetDetail = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,d2.DAERAH_KEY LOKASI_DAERAH_KEY,d2.DAERAH_DESKRIPSI LOKASI_DAERAH,c2.CABANG_KEY LOKASI_CABANG_KEY,c2.CABANG_DESKRIPSI LOKASI_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,
CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS_DESKRIPSI
FROM t_ppd p
LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
WHERE p.PPD_ID = '$PPD_ID'");

$options = [];
while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $options[] = array(
        'PPD_ID' => $rowDetail['PPD_ID'],
        'PPD_TANGGAL' => $rowDetail['PPD_TANGGAL'],
        'PPD_JENIS' => $rowDetail['PPD_JENIS'],
        'PPD_JENIS_DESKRIPSI' => $rowDetail['PPD_JENIS_DESKRIPSI'],
        'PPD_LOKASI' => $rowDetail['PPD_LOKASI'],
        'PPD_DESKRIPSI' => $rowDetail['PPD_DESKRIPSI'],
        'PPD_FILE' => $rowDetail['PPD_FILE'],
        'PPD_APPROVE_PELATIH' => $rowDetail['PPD_APPROVE_PELATIH'],
        'PPD_APPROVE_PELATIH_TGL' => $rowDetail['PPD_APPROVE_PELATIH_TGL'],
        'PPD_APPROVE_GURU' => $rowDetail['PPD_APPROVE_GURU'],
        'PPD_APPROVE_GURU_TGL' => $rowDetail['PPD_APPROVE_GURU_TGL'],
        'CABANG_KEY' => $rowDetail['CABANG_KEY'],
        'CABANG_DESKRIPSI' => $rowDetail['CABANG_DESKRIPSI'],
        'DAERAH_KEY' => $rowDetail['DAERAH_KEY'],
        'DAERAH_DESKRIPSI' => $rowDetail['DAERAH_DESKRIPSI'],
        'LOKASI_DAERAH_KEY' => $rowDetail['LOKASI_DAERAH_KEY'],
        'LOKASI_DAERAH' => $rowDetail['LOKASI_DAERAH'],
        'LOKASI_CABANG_KEY' => $rowDetail['LOKASI_CABANG_KEY'],
        'LOKASI_CABANG' => $rowDetail['LOKASI_CABANG'],
        'TINGKATAN_ID' => $rowDetail['TINGKATAN_ID_BARU'],
        'TINGKATAN_NAMA' => $rowDetail['TINGKATAN_NAMA'],
        'TINGKATAN_SEBUTAN' => $rowDetail['TINGKATAN_SEBUTAN'],
        'ANGGOTA_ID' => $rowDetail['ANGGOTA_ID'],
        'ANGGOTA_NAMA' => $rowDetail['ANGGOTA_NAMA']
    );
}

// Send success response
sendSuccessResponse($options);

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
