<?php
require_once ("../../../../../module/connection/conn.php");

header('Content-Type: application/json');

$USER_ID = $_SESSION["LOGINIDUS_CS"] ?? '';
$USER_CABANG = $_SESSION["LOGINCAB_CS"] ?? '';
$USER_AKSES = $_SESSION["LOGINAKS_CS"] ?? '';

if (!isset($_POST['check_duplicates'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

if (!isset($_FILES['fileTemplate']) || !is_uploaded_file($_FILES['fileTemplate']['tmp_name'])) {
    echo json_encode(['success' => false, 'message' => 'File tidak ditemukan']);
    exit;
}

$tmpPath = $_FILES['fileTemplate']['tmp_name'];
$ext = strtolower(pathinfo($_FILES['fileTemplate']['name'], PATHINFO_EXTENSION));

// Helper function to read spreadsheet
function tryReadSpreadsheet($filePath) {
    try {
        if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory')) {
            $dir = __DIR__;
            for ($i=0; $i<9; $i++) {
                $autoload = $dir . '/vendor/autoload.php';
                if (file_exists($autoload)) { require_once $autoload; break; }
                $dir = dirname($dir);
            }
        }
        if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory')) return null;
        
        $reader = call_user_func(['\\PhpOffice\\PhpSpreadsheet\\IOFactory','createReaderForFile'], $filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        $header = $sheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
        $header = array_values(array_values($header)[0]);
        
        $rows = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowArray = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
            $rowArray = array_values(array_values($rowArray)[0]);
            if (count(array_filter($rowArray, function($v){ return $v !== null && $v !== ''; })) === 0) {
                continue;
            }
            $rows[] = array_combine($header, $rowArray);
        }
        return [$header, $rows];
    } catch (Throwable $e) {
        return null;
    }
}

// Determine CABANG_KEY
if ($USER_AKSES === 'Administrator') {
    $CABANG_KEY = $_POST['CABANG_KEY'] ?? $USER_CABANG;
} else {
    $CABANG_KEY = $USER_CABANG;
}

// Get cabang info
$CABANG_ID = '';
$getCabangID = GetQuery("select CABANG_ID from m_cabang where CABANG_KEY = '$CABANG_KEY'");
while ($rowCab = $getCabangID->fetch(PDO::FETCH_ASSOC)) {
    $CABANG_ID = $rowCab['CABANG_ID'] ?? '';
}

if (!$CABANG_ID) {
    echo json_encode(['success' => false, 'message' => 'Cabang tidak ditemukan']);
    exit;
}

// Build ID prefix
$parts = explode('.', $CABANG_ID);
unset($parts[0]);
$resultPrefix = implode('.', $parts);

// Read file
if ($ext === 'xlsx') {
    $parsed = tryReadSpreadsheet($tmpPath);
    if ($parsed === null) {
        echo json_encode(['success' => false, 'message' => 'Library Spreadsheet tidak tersedia']);
        exit;
    }
    list($header, $dataRows) = $parsed;
} else {
    echo json_encode(['success' => false, 'message' => 'Format file tidak didukung']);
    exit;
}

// Check for duplicates
$duplicates = [];
$mode = strtolower(trim((string)($_POST['mode'] ?? 'insert')));

foreach ($dataRows as $idx => $row) {
    $ANGGOTA_ID_INPUT = trim((string)($row['ANGGOTA_ID'] ?? ''));
    $ANGGOTA_ID_NO = trim((string)($row['ANGGOTA_ID_NO_URUT'] ?? ''));
    $ANGGOTA_JOIN = trim((string)($row['TANGGAL_BERGABUNG'] ?? $row['ANGGOTA_JOIN'] ?? ''));
    
    // Determine full ANGGOTA_ID
    $ANGGOTA_ID_FULL = '';
    if ($ANGGOTA_ID_INPUT !== '') {
        $ANGGOTA_ID_FULL = $ANGGOTA_ID_INPUT;
    } else if ($ANGGOTA_ID_NO !== '') {
        $year = '';
        try { 
            $date = new DateTime($ANGGOTA_JOIN ?: date('Y-m-d'));
            $year = $date->format('Y'); 
        } catch (Throwable $e) { 
            $year = date('Y'); 
        }
        $ANGGOTA_ID_FULL = $resultPrefix . '.' . $year . '.' . preg_replace('/\D/', '', $ANGGOTA_ID_NO);
    } else {
        continue; // Skip if no ID available
    }
    
    // Check if exists (skip in replace mode or upsert mode as those handle it differently)
    if ($mode === 'insert') {
        $check = GetQuery("select ANGGOTA_ID, ANGGOTA_NAMA, ANGGOTA_KTP, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_JOIN, ANGGOTA_TANGGAL_LAHIR 
                          from m_anggota 
                          where ANGGOTA_ID = '$ANGGOTA_ID_FULL' and CABANG_KEY = '$CABANG_KEY' 
                          limit 1");
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Get new data values
            $newNama = trim((string)($row['NAMA'] ?? $row['ANGGOTA_NAMA'] ?? '-'));
            $newKtp = trim((string)($row['KTP'] ?? $row['ANGGOTA_KTP'] ?? '-'));
            $newHp = trim((string)($row['NOMOR_HP'] ?? $row['ANGGOTA_HP'] ?? '-'));
            $newEmail = trim((string)($row['EMAIL'] ?? $row['ANGGOTA_EMAIL'] ?? '-'));
            $newJoinRaw = trim((string)($row['TANGGAL_BERGABUNG'] ?? $row['ANGGOTA_JOIN'] ?? '-'));
            $newTglLahirRaw = trim((string)($row['TANGGAL_LAHIR'] ?? $row['ANGGOTA_TANGGAL_LAHIR'] ?? '-'));
            
            // Compare existing and new data (case-insensitive, trimmed)
            $existingNama = trim((string)($existing['ANGGOTA_NAMA'] ?? ''));
            $existingKtp = trim((string)($existing['ANGGOTA_KTP'] ?? ''));
            $existingHp = trim((string)($existing['ANGGOTA_HP'] ?? ''));
            $existingEmail = trim((string)($existing['ANGGOTA_EMAIL'] ?? ''));
            $existingJoin = trim((string)($existing['ANGGOTA_JOIN'] ?? ''));
            $existingTglLahir = trim((string)($existing['ANGGOTA_TANGGAL_LAHIR'] ?? ''));
            
            // Normalize dates for comparison (convert to Y-m-d format)
            $normDate = function($val) {
                if (!$val || $val === '-') return '';
                // Try parsing as date
                try {
                    // Handle Excel serial numbers
                    if (is_numeric($val)) {
                        $n = (float)$val;
                        if ($n >= 1 && $n < 1000000) {
                            // Excel serial date (base 1899-12-30)
                            $base = new DateTime('1899-12-30');
                            $base->modify('+' . floor($n) . ' days');
                            return $base->format('Y-m-d');
                        }
                    }
                    // Try standard formats
                    $dt = new DateTime($val);
                    return $dt->format('Y-m-d');
                } catch (Throwable $e) {
                    return $val; // Return as-is if can't parse
                }
            };
            
            $existingJoinNorm = $normDate($existingJoin);
            $newJoinNorm = $normDate($newJoinRaw);
            $existingTglLahirNorm = $normDate($existingTglLahir);
            $newTglLahirNorm = $normDate($newTglLahirRaw);
            
            // Skip if all key fields are identical
            if (strcasecmp($existingNama, $newNama) === 0 &&
                strcasecmp($existingKtp, $newKtp) === 0 &&
                strcasecmp($existingHp, $newHp) === 0 &&
                strcasecmp($existingEmail, $newEmail) === 0 &&
                $existingJoinNorm === $newJoinNorm &&
                $existingTglLahirNorm === $newTglLahirNorm) {
                // Data is identical, skip this row
                continue;
            }
            
            // Data differs, add to duplicates for user resolution with diff flags
            $duplicates[] = [
                'anggota_id' => $ANGGOTA_ID_FULL,
                'existing' => [
                    'nama' => $existing['ANGGOTA_NAMA'] ?? '-',
                    'ktp' => $existing['ANGGOTA_KTP'] ?? '-',
                    'hp' => $existing['ANGGOTA_HP'] ?? '-',
                    'email' => $existing['ANGGOTA_EMAIL'] ?? '-',
                    'join' => $existing['ANGGOTA_JOIN'] ?? '-',
                    'tgl_lahir' => $existing['ANGGOTA_TANGGAL_LAHIR'] ?? '-'
                ],
                'new' => [
                    'nama' => $newNama,
                    'ktp' => $newKtp,
                    'hp' => $newHp,
                    'email' => $newEmail,
                    'join' => $newJoinRaw,
                    'tgl_lahir' => $newTglLahirRaw
                ],
                'diff' => [
                    'nama' => strcasecmp($existingNama, $newNama) !== 0,
                    'ktp' => strcasecmp($existingKtp, $newKtp) !== 0,
                    'hp' => strcasecmp($existingHp, $newHp) !== 0,
                    'email' => strcasecmp($existingEmail, $newEmail) !== 0,
                    'join' => $existingJoinNorm !== $newJoinNorm,
                    'tgl_lahir' => $existingTglLahirNorm !== $newTglLahirNorm
                ]
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'duplicates' => $duplicates
]);
exit;
?>
