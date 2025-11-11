<?php
require_once ("../../../../../module/connection/conn.php");

// Ensure user context
$USER_ID = $_SESSION["LOGINIDUS_CS"] ?? '';
$USER_CABANG = $_SESSION["LOGINCAB_CS"] ?? '';
$USER_AKSES = $_SESSION["LOGINAKS_CS"] ?? '';
// DateTime for logs
$localDateTime = date('Y-m-d H:i:s');
// Allow long-running bulk imports
@ignore_user_abort(true);
@set_time_limit(0);
@ini_set('max_execution_time', '0');

// Start of the upload template handler
// Expect multipart POST with fileTemplate and upload_template_anggota flag
if (!isset($_POST['upload_template_anggota'])) {
	echo "Invalid Request"; exit;
}

if (!isset($_FILES['fileTemplate']) || !is_uploaded_file($_FILES['fileTemplate']['tmp_name'])) {
	echo "File tidak ditemukan"; exit;
}

$tmpPath = $_FILES['fileTemplate']['tmp_name'];
$origName = $_FILES['fileTemplate']['name'];
$ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

// Columns expected in template
$columns = [
	'ANGGOTA_ID',
	'CABANG_ID',
	'RANTING',
	'TINGKATAN',
	'KTP',
	'NAMA',
	'ALAMAT',
	'AGAMA',
	'PEKERJAAN',
	'JENIS_KELAMIN',
	'TEMPAT_LAHIR',
	'NOMOR_HP',
	'EMAIL',
	'TANGGAL_BERGABUNG',
	'TANGGAL_KELUAR',
    'AKSES',
    'STATUS'
];

function readRowsFromCsv($filePath) {
	$rows = [];
	if (($handle = fopen($filePath, 'r')) !== false) {
		$header = null;
		while (($data = fgetcsv($handle)) !== false) {
			if ($header === null) { $header = $data; continue; }
			$rows[] = array_combine($header, $data);
		}
		fclose($handle);
	}
	return [$header, $rows];
}

function tryReadSpreadsheet($filePath) {
	// Returns [header, rows] or null if library not available
	try {
		if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory')) {
			// Try to locate vendor/autoload.php up to 9 levels up from current dir
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
		$header = [];
		$rows = [];
		// Header at row 1
		$header = $sheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
		$header = array_values($header)[0]; // associative by column letter
		$header = array_values($header);    // numeric indexed
		for ($row = 2; $row <= $highestRow; $row++) {
			$rowArray = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
			$rowArray = array_values($rowArray)[0];
			$rowArray = array_values($rowArray);
			if (count(array_filter($rowArray, function($v){ return $v !== null && $v !== ''; })) === 0) {
				continue; // skip empty rows
			}
			$rows[] = array_combine($header, $rowArray);
		}
		return [$header, $rows];
	} catch (Throwable $e) {
		return null;
	}
}

// Read file
// Import mode: insert (default), upsert, replace (delete existing cabang data then insert)
$mode = strtolower(trim((string)($_POST['mode'] ?? 'insert')));
if (!in_array($mode, ['insert','upsert','replace'], true)) { $mode = 'insert'; }
$isUpsert = ($mode === 'upsert');

if ($ext === 'xlsx') {
	$parsed = tryReadSpreadsheet($tmpPath);
	if ($parsed === null) {
		echo "Library Spreadsheet tidak tersedia. Unggah CSV sebagai alternatif."; exit;
	}
	list($header, $dataRows) = $parsed;
} else if ($ext === 'csv') {
	list($header, $dataRows) = readRowsFromCsv($tmpPath);
} else {
	echo "Format file tidak didukung"; exit;
}

// Validate header contains required columns
$missing = array_diff($columns, $header ?? []);
if (!empty($missing)) {
	echo "Kolom template tidak lengkap: " . implode(', ', $missing); exit;
}

// Determine CABANG_KEY based on user role
if ($USER_AKSES === 'Administrator') {
	// Allow optional CABANG_KEY override via POST; else default to user's cabang
	$CABANG_KEY = $_POST['CABANG_KEY'] ?? $USER_CABANG;
} else {
	$CABANG_KEY = $USER_CABANG;
}

// Prepare helpers from existing logic in t_daftaranggota.php
// Fetch CABANG_ID and CABANG_DESKRIPSI
$CABANG_ID = '';
$CABANG_DESKRIPSI = '';
$getCabangID = GetQuery("select CABANG_ID, CABANG_DESKRIPSI from m_cabang where CABANG_KEY = '$CABANG_KEY'");
while ($rowCab = $getCabangID->fetch(PDO::FETCH_ASSOC)) {
	$CABANG_ID = $rowCab['CABANG_ID'] ?? '';
	$CABANG_DESKRIPSI = $rowCab['CABANG_DESKRIPSI'] ?? '';
}

if (!$CABANG_ID) { echo "Cabang tidak ditemukan"; exit; }

// Build base ID prefix from CABANG_ID per project rule (remove first part)
$parts = explode('.', $CABANG_ID);
unset($parts[0]);
$resultPrefix = implode('.', $parts);

$inserted = 0; $updated = 0; $failed = 0; $errors = [];
// Defer "insert" logs for batch processing later
$logInsertIds = [];
// In replace mode, accumulate rows for batch insert to speed up
$batchRowsReplace = [];

// If replace mode, delete all existing data for this cabang before processing
if ($mode === 'replace') {
	try {
		// Log deletions
		GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, ANGGOTA_RANTING, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'D', '$USER_ID', '$localDateTime' from m_anggota where CABANG_KEY = '$CABANG_KEY'");
		// Delete existing records for this cabang
		GetQuery("delete from m_anggota where CABANG_KEY = '$CABANG_KEY'");
	} catch (Throwable $e) {
		echo "Gagal menghapus data lama: " . $e->getMessage(); exit;
	}
}

// Use a transaction to speed up many inserts/updates
GetQuery('START TRANSACTION');

// Prefetch tingkatan mappings to avoid per-row lookups
$mapNama = []; $mapSebutan = []; $mapLevel = []; $lowestTingkatanId = '';
$lowestLevel = PHP_INT_MAX;
$qtAll = GetQuery("select TINGKATAN_ID, TINGKATAN_NAMA, TINGKATAN_SEBUTAN, TINGKATAN_LEVEL from m_tingkatan where DELETION_STATUS = 0");
while ($rtAll = $qtAll->fetch(PDO::FETCH_ASSOC)) {
    $tid = $rtAll['TINGKATAN_ID'];
    $nm = strtolower(trim((string)($rtAll['TINGKATAN_NAMA'] ?? '')));
    $sb = strtolower(trim((string)($rtAll['TINGKATAN_SEBUTAN'] ?? '')));
    $lv = (string)($rtAll['TINGKATAN_LEVEL'] ?? '');
    if ($nm !== '') $mapNama[$nm] = $tid;
    if ($sb !== '') $mapSebutan[$sb] = $tid;
    if ($lv !== '') $mapLevel[$lv] = $tid;
    if ($lv !== '' && (int)$lv < $lowestLevel) { $lowestLevel = (int)$lv; $lowestTingkatanId = $tid; }
}

foreach ($dataRows as $idx => $row) {
	// Helpers
	$normDate = function($v){
		$v = trim((string)($v ?? ''));
		if ($v === '') return '';

		// 1) Numeric handling: Excel serials or timestamps
		$n = is_numeric($v) ? (float)$v : null;
		if ($n !== null) {
			// Excel serial numbers (1900 date system) typically fall in 1..100000+ range
			// Handle pre-1970 values too (e.g., 22384 => 1961-04-13)
			if ($n >= 1 && $n < 1000000) {
				// Prefer PhpSpreadsheet conversion if available (handles leap-year bug correctly)
				if (class_exists('PhpOffice\\PhpSpreadsheet\\Shared\\Date')) {
					try {
						$dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($n);
						if ($dt) { return $dt->format('Y-m-d'); }
					} catch (Throwable $e) { /* fallback below */ }
				}
				// Fallback conversion: base 1899-12-30 + n days (accounts for Excel's 1900 leap bug)
				try {
					$days = (int)floor($n);
					$seconds = (int)round(($n - $days) * 86400);
					$base = new DateTime('1899-12-30');
					if ($days > 0) { $base->add(new DateInterval('P'.$days.'D')); }
					if ($seconds > 0) { $base->add(new DateInterval('PT'.$seconds.'S')); }
					return $base->format('Y-m-d');
				} catch (Throwable $e) { /* fall through to other strategies */ }
			}
			// Unix timestamp seconds or milliseconds
			if ($n > 1000000000) {
				if ($n > 100000000000) { $n = $n / 1000; }
				return date('Y-m-d', (int)$n);
			}
		}

		// 2) Try common explicit formats after normalizing separators
		$s = str_replace(['/', '.'], '-', $v);
		$formats = [
			'Y-m-d', 'Y-m-d H:i:s', 'Y/m/d',
			'd-m-Y', 'd-m-Y H:i:s', 'd/m/Y',
			'd-m-y', 'd-m-y H:i:s', 'd/m/y',
			'Ymd', 'dmY'
		];
		foreach ($formats as $fmt) {
			$dt = DateTime::createFromFormat($fmt, $s);
			if ($dt instanceof DateTime) {
				return $dt->format('Y-m-d');
			}
		}

		// 3) Last resort: strtotime for other lenient cases
		$ts = strtotime($v);
		if ($ts !== false) {
			return date('Y-m-d', $ts);
		}
		return '';
	};
	$normGender = function($v){
		$v = trim((string)($v ?? ''));
		if ($v === '') return '';
		$l = strtolower($v);
		if ($l === 'l' || strpos($l,'pria') !== false) return 'L';
		if ($l === 'p' || strpos($l,'wanita') !== false) return 'P';
		return strtoupper($v);
	};
	$resolveTingkatanId = function($val) use ($mapNama, $mapSebutan, $mapLevel) {
		$val = trim((string)($val ?? ''));
		if ($val === '') return '';
		$lower = strtolower($val);
		if (isset($mapNama[$lower])) return $mapNama[$lower];
		if (isset($mapSebutan[$lower])) return $mapSebutan[$lower];
		if (ctype_digit($val) && isset($mapLevel[$val])) return $mapLevel[$val];
		// If looks like an ID, trust it
		return $val;
	};

	// Sanitize and map fields from new template headers (with fallback to old)
	$ANGGOTA_ID_INPUT = trim((string)($row['ANGGOTA_ID'] ?? ''));
	$ANGGOTA_RANTING = trim((string)($row['RANTING'] ?? $row['ANGGOTA_RANTING'] ?? ''));
	$TINGKATAN_ID = trim((string)($row['TINGKATAN_ID'] ?? ''));
	if ($TINGKATAN_ID === '') { $TINGKATAN_ID = $resolveTingkatanId($row['TINGKATAN'] ?? ''); }
	// Fallback to the lowest level tingkatan if still empty
	if ($TINGKATAN_ID === '') { $TINGKATAN_ID = $lowestTingkatanId; }
	$ANGGOTA_KTP = trim((string)($row['KTP'] ?? $row['ANGGOTA_KTP'] ?? ''));
	$ANGGOTA_NAMA = trim((string)($row['NAMA'] ?? $row['ANGGOTA_NAMA'] ?? ''));
	$ANGGOTA_ALAMAT = trim((string)($row['ALAMAT'] ?? $row['ANGGOTA_ALAMAT'] ?? ''));
	$ANGGOTA_AGAMA = trim((string)($row['AGAMA'] ?? $row['ANGGOTA_AGAMA'] ?? ''));
	$ANGGOTA_PEKERJAAN = trim((string)($row['PEKERJAAN'] ?? $row['ANGGOTA_PEKERJAAN'] ?? ''));
	$ANGGOTA_KELAMIN = $normGender($row['JENIS_KELAMIN'] ?? $row['ANGGOTA_KELAMIN'] ?? '');
	$ANGGOTA_TEMPAT_LAHIR = trim((string)($row['TEMPAT_LAHIR'] ?? $row['ANGGOTA_TEMPAT_LAHIR'] ?? ''));
	$ANGGOTA_TANGGAL_LAHIR = $normDate($row['TANGGAL_LAHIR'] ?? $row['ANGGOTA_TANGGAL_LAHIR'] ?? '');
	$ANGGOTA_HP = trim((string)($row['NOMOR_HP'] ?? $row['ANGGOTA_HP'] ?? ''));
	$ANGGOTA_EMAIL = trim((string)($row['EMAIL'] ?? $row['ANGGOTA_EMAIL'] ?? ''));
	$ANGGOTA_JOIN = $normDate($row['TANGGAL_BERGABUNG'] ?? $row['ANGGOTA_JOIN'] ?? '');
	$ANGGOTA_RESIGN = $normDate($row['TANGGAL_KELUAR'] ?? $row['ANGGOTA_RESIGN'] ?? '');
	$ANGGOTA_AKSES = trim((string)($row['AKSES'] ?? $row['ANGGOTA_AKSES'] ?? 'User'));
	$ANGGOTA_STATUS_IN = trim((string)($row['STATUS'] ?? ''));
	$ANGGOTA_ID_NO = trim((string)($row['ANGGOTA_ID_NO_URUT'] ?? ''));

	// Basic validations: require Name and either provided ANGGOTA_ID or derivable pieces
	if (!$ANGGOTA_NAMA) {
		$failed++; $errors[] = "Baris " . ($idx+2) . ": Nama wajib."; continue;
	}
	// Determine full ANGGOTA_ID: prefer provided value; else build from prefix + year + 5-digit no
	$ANGGOTA_ID_FULL = '';
	if ($ANGGOTA_ID_INPUT !== '') {
		$ANGGOTA_ID_FULL = $ANGGOTA_ID_INPUT;
	} else {
		// Fallback build only if we have components
		if ($ANGGOTA_ID_NO === '') { $failed++; $errors[] = "Baris " . ($idx+2) . ": ANGGOTA_ID tidak tersedia."; continue; }
		$year = '';
		try { $year = (new DateTime($ANGGOTA_JOIN ?: date('Y-m-d')))->format('Y'); } catch (Throwable $e) { $year = date('Y'); }
		$ANGGOTA_ID_FULL = $resultPrefix . '.' . $year . '.' . preg_replace('/\D/', '', $ANGGOTA_ID_NO);
	}

	// Check duplicate (skip in replace mode; data already cleared)
	$existing = null; $existingKey = null;
	if ($mode !== 'replace') {
		$check = GetQuery("select ANGGOTA_KEY, ANGGOTA_ID, ANGGOTA_NAMA from m_anggota where ANGGOTA_ID = '$ANGGOTA_ID_FULL' and CABANG_KEY = '$CABANG_KEY' limit 1");
		$existing = $check->fetch(PDO::FETCH_ASSOC) ?: null;
		$existingKey = $existing['ANGGOTA_KEY'] ?? null;
		if ($existingKey && !$isUpsert) {
			$existId = $existing['ANGGOTA_ID'] ?? $ANGGOTA_ID_FULL;
			$existNama = $existing['ANGGOTA_NAMA'] ?? '-';
			$failed++;
			$errors[] = "Baris " . ($idx+2) . ": ID sudah terdaftar (ANGGOTA_ID: " . $existId . ", NAMA: " . $existNama . ").";
			continue;
		}
	}

	// Default PIC path
	$idCardFileDestination = "./assets/images/daftaranggota/default/avatar.png";

	try {
		if ($existingKey && $isUpsert) {
			// Log before update
			GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, ANGGOTA_RANTING, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'U', '$USER_ID', '$localDateTime' from m_anggota where ANGGOTA_KEY = '$existingKey'");

			// Update selective fields (no resign/status change here)
			$updateSql = "UPDATE m_anggota set CABANG_KEY = ?, ANGGOTA_RANTING = ?, TINGKATAN_ID = ?, ANGGOTA_KTP = ?, ANGGOTA_NAMA = ?, ANGGOTA_ALAMAT = ?, ANGGOTA_AGAMA = ?, ANGGOTA_PEKERJAAN = ?, ANGGOTA_KELAMIN = ?, ANGGOTA_TEMPAT_LAHIR = ?, ANGGOTA_TANGGAL_LAHIR = ?, ANGGOTA_HP = ?, ANGGOTA_EMAIL = ?, ANGGOTA_JOIN = ?, ANGGOTA_RESIGN = ?, ANGGOTA_AKSES = ?, INPUT_BY = ?, INPUT_DATE = '$localDateTime' where ANGGOTA_KEY = ?";
			GetQuery2($updateSql, [
				$CABANG_KEY,
				$ANGGOTA_RANTING,
				$TINGKATAN_ID,
				$ANGGOTA_KTP,
				$ANGGOTA_NAMA,
				$ANGGOTA_ALAMAT,
				$ANGGOTA_AGAMA,
				$ANGGOTA_PEKERJAAN,
				$ANGGOTA_KELAMIN,
				$ANGGOTA_TEMPAT_LAHIR,
				($ANGGOTA_TANGGAL_LAHIR ?: null),
				$ANGGOTA_HP,
				$ANGGOTA_EMAIL,
				($ANGGOTA_JOIN ?: null),
				($ANGGOTA_RESIGN ?: null),
				$ANGGOTA_AKSES,
				$USER_ID,
				$existingKey
			]);
			$updated++;
		} else {
			// Fast path for replace mode: defer row to batch insert (skip per-row DB insert)
			if ($mode === 'replace') {
				$batchRowsReplace[] = [
					// Order matches VALUES placeholders for multi-insert below
					$ANGGOTA_ID_FULL,
					$CABANG_KEY,
					$ANGGOTA_RANTING,
					$TINGKATAN_ID,
					$ANGGOTA_KTP,
					null, // ANGGOTA_KTA
					null, // ANGGOTA_KTA_AKTIF
					null, // ANGGOTA_KTA_EXP
					$ANGGOTA_NAMA,
					$ANGGOTA_ALAMAT,
					$ANGGOTA_AGAMA,
					$ANGGOTA_PEKERJAAN,
					$ANGGOTA_KELAMIN,
					$ANGGOTA_TEMPAT_LAHIR,
					($ANGGOTA_TANGGAL_LAHIR ?: null),
					$ANGGOTA_HP,
					$ANGGOTA_EMAIL,
					$idCardFileDestination,
					($ANGGOTA_JOIN ?: null),
					$ANGGOTA_AKSES,
					$USER_ID,
					$localDateTime
				];
				$logInsertIds[] = $ANGGOTA_ID_FULL;
				$inserted++;
				continue; // move to next row
			}

			// Insert using same pattern as manual add
			$query = "INSERT INTO m_anggota SELECT UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, 0, 0, ?, '$localDateTime'";
			GetQuery2($query, [
				$ANGGOTA_ID_FULL,
				$CABANG_KEY,
				$ANGGOTA_RANTING,
				$TINGKATAN_ID,
				$ANGGOTA_KTP,
				null,
				null,
				null,
				$ANGGOTA_NAMA,
				$ANGGOTA_ALAMAT,
				$ANGGOTA_AGAMA,
				$ANGGOTA_PEKERJAAN,
				$ANGGOTA_KELAMIN,
				$ANGGOTA_TEMPAT_LAHIR,
				($ANGGOTA_TANGGAL_LAHIR ?: null),
				$ANGGOTA_HP,
				$ANGGOTA_EMAIL,
				$idCardFileDestination,
				($ANGGOTA_JOIN ?: null),
				$ANGGOTA_AKSES,
				$USER_ID
			]);

			// Defer insert log for batch logging
			$logInsertIds[] = $ANGGOTA_ID_FULL;
			$inserted++;
		}
	} catch (Throwable $e) {
		$failed++; $errors[] = "Baris " . ($idx+2) . ": Gagal simpan/perbarui (" . $e->getMessage() . ")";
	}
}

// Perform batch inserts for replace mode in chunks
if ($mode === 'replace' && !empty($batchRowsReplace)) {
	$chunkSize = 500; // tuneable
	$cols = "(ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, ANGGOTA_RANTING, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_KTA, ANGGOTA_KTA_AKTIF, ANGGOTA_KTA_EXP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, ANGGOTA_AKSES, ANGGOTA_STATUS, DELETION_STATUS, INPUT_BY, INPUT_DATE)";
	for ($i = 0, $n = count($batchRowsReplace); $i < $n; $i += $chunkSize) {
		$chunk = array_slice($batchRowsReplace, $i, $chunkSize);
		$placeholdersPerRow = "(UUID(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NULL,?,0,0,?,?)";
		$valuesClause = implode(',', array_fill(0, count($chunk), $placeholdersPerRow));
		$sql = "INSERT INTO m_anggota $cols VALUES $valuesClause";
		$params = [];
		foreach ($chunk as $rowvals) {
			// rowvals already ordered to match placeholders
			$params = array_merge($params, $rowvals);
		}
		try { GetQuery2($sql, $params); }
		catch (Throwable $e) { $failed++; $errors[] = "Batch insert gagal: " . $e->getMessage(); }
	}
}

// Batch log inserts (in chunks to avoid huge IN lists)
if (!empty($logInsertIds)) {
	$chunkSize = 800; $n = count($logInsertIds);
	for ($i = 0; $i < $n; $i += $chunkSize) {
		$chunk = array_slice($logInsertIds, $i, $chunkSize);
		$safe = array_map(function($v){ return str_replace("'", "''", $v); }, $chunk);
		$inList = "('" . implode("','", $safe) . "')";
		GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, ANGGOTA_RANTING, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'I', '$USER_ID', '$localDateTime' from m_anggota where CABANG_KEY = '$CABANG_KEY' and ANGGOTA_ID in $inList");
	}
}

// Commit transaction
GetQuery('COMMIT');

if ($failed === 0 && ($inserted > 0 || $updated > 0)) {
	echo "Success"; exit;
}

// Return error summary (plain text)
echo "Berhasil: $inserted, Diperbarui: $updated, Gagal: $failed\n" . implode("\n", $errors);
exit;
