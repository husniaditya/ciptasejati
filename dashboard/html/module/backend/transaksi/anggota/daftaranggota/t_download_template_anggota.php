<?php
require_once ("../../../../../module/connection/conn.php");

// Serve the existing template file directly from dashboard/assets/template
// Current file path: dashboard/html/module/backend/transaksi/anggota/daftaranggota/t_download_template_anggota.php
// Template path:      dashboard/assets/template/template_anggota_cs.xlsx

$templateRelative = '../../../../../..' . '/assets/template/template_anggota_cs.xlsx';
$templatePath = realpath(__DIR__ . '/' . $templateRelative);

if ($templatePath === false || !file_exists($templatePath)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Template tidak ditemukan.';
    exit;
}

// Stream the file as a download
$filename = basename($templatePath);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($templatePath));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Clear output buffer if any to avoid corruption
if (function_exists('ob_get_length')) {
    while (ob_get_length()) { ob_end_clean(); }
}

readfile($templatePath);
exit;
