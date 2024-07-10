<?php
require_once("../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

// Generate past 12 months in reverse order (from current month to 11 months ago)
$months = [];
$currentMonth = new DateTime();
for ($i = 0; $i < 12; $i++) {
    $monthYear = $currentMonth->format('Y-m');
    $months[$monthYear] = 0; // Initialize with 0
    $currentMonth->modify('-1 month');
}

// Fetch the data from the database
if ($USER_AKSES == "Administrator") {
    $getPPD = GetQuery("SELECT COUNT(*) PPD, DATE_FORMAT(PPD_TANGGAL, '%Y-%m') AS month_year
                        FROM t_ppd
                        WHERE DELETION_STATUS = 0 AND PPD_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        GROUP BY month_year
                        ORDER BY month_year");
    
    $getUKT = GetQuery("SELECT COUNT(*) UKT, DATE_FORMAT(UKT_TANGGAL, '%Y-%m') AS month_year
                        FROM t_ukt
                        WHERE DELETION_STATUS = 0 AND UKT_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        GROUP BY month_year
                        ORDER BY month_year");
} else {
    $getPPD = GetQuery("SELECT COUNT(*) PPD, DATE_FORMAT(PPD_TANGGAL, '%Y-%m') AS month_year
                        FROM t_ppd
                        WHERE DELETION_STATUS = 0 AND PPD_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND CABANG_KEY = '$USER_CABANG'
                        GROUP BY month_year
                        ORDER BY month_year");
    
    $getUKT = GetQuery("SELECT COUNT(*) UKT, DATE_FORMAT(UKT_TANGGAL, '%Y-%m') AS month_year
                        FROM t_ukt
                        WHERE DELETION_STATUS = 0 AND UKT_TANGGAL >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND CABANG_KEY = '$USER_CABANG'
                        GROUP BY month_year
                        ORDER BY month_year");
}

// Fetch PPD data and update the $months array
while ($row = $getPPD->fetch(PDO::FETCH_ASSOC)) {
    $months[$row['month_year']] = (int)$row['PPD'];
}
$PPD_data = array_values($months);

// Reset $months array for UKT data
foreach ($months as $key => $value) {
    $months[$key] = 0;
}

// Fetch UKT data and update the $months array
while ($row = $getUKT->fetch(PDO::FETCH_ASSOC)) {
    $months[$row['month_year']] = (int)$row['UKT'];
}
$UKT_data = array_values($months);

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
