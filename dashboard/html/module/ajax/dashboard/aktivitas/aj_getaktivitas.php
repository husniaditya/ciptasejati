<?php
require_once("../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

// Generate past 5 years in reverse order (from current year to 4 years ago)
$years = [];
$currentYear = new DateTime();
for ($i = 0; $i < 7; $i++) {
    $year = $currentYear->format('Y');
    $years[$year] = 0; // Initialize with 0
    $currentYear->modify('-1 year');
}

// Fetch the data from the database
if ($USER_AKSES == "Administrator") {
    $getPPD = GetQuery("SELECT COUNT(*) PPD, DATE_FORMAT(PPD_TANGGAL, '%Y') AS year
                        FROM t_ppd
                        WHERE DELETION_STATUS = 0 AND PPD_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 7 YEAR) AND PPD_APPROVE_PELATIH = 1
                        GROUP BY year
                        ORDER BY year");
    
    $getUKT = GetQuery("SELECT COUNT(*) UKT, DATE_FORMAT(UKT_TANGGAL, '%Y') AS year
                        FROM t_ukt
                        WHERE DELETION_STATUS = 0 AND UKT_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 7 YEAR) AND UKT_APP_KOOR = 1
                        GROUP BY year
                        ORDER BY year");
} else {
    $getPPD = GetQuery("SELECT COUNT(*) PPD, DATE_FORMAT(PPD_TANGGAL, '%Y') AS year
                        FROM t_ppd
                        WHERE DELETION_STATUS = 0 AND PPD_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 7 YEAR) AND CABANG_KEY = '$USER_CABANG' AND PPD_APPROVE_PELATIH = 1
                        GROUP BY year
                        ORDER BY year");
    
    $getUKT = GetQuery("SELECT COUNT(*) UKT, DATE_FORMAT(UKT_TANGGAL, '%Y') AS year
                        FROM t_ukt
                        WHERE DELETION_STATUS = 0 AND UKT_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 7 YEAR) AND CABANG_KEY = '$USER_CABANG' AND UKT_APP_KOOR = 1
                        GROUP BY year
                        ORDER BY year");
}

// Fetch PPD data and update the $years array
while ($row = $getPPD->fetch(PDO::FETCH_ASSOC)) {
    $years[$row['year']] = (int)$row['PPD'];
}
$PPD_data = array_values($years);

// Reset $years array for UKT data
foreach ($years as $key => $value) {
    $years[$key] = 0;
}

// Fetch UKT data and update the $years array
while ($row = $getUKT->fetch(PDO::FETCH_ASSOC)) {
    $years[$row['year']] = (int)$row['UKT'];
}
$UKT_data = array_values($years);

// Reverse the arrays to have them in the correct order (from 12 months ago to current month)
$PPD_data = array_reverse($PPD_data);
$UKT_data = array_reverse($UKT_data);

// Prepare the data for the chart
$data = [
    'series' => [
        [
            'name' => 'Pembukaan Pusat Daya',
            'data' => $PPD_data
        ],
        [
            'name' => 'Ujian Kenaikan Tingkat',
            'data' => $UKT_data
        ],
        // Add other series as needed
    ]
];

// Output the JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
