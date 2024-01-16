<?php
require_once("../../../../module/connection/conn.php");

// Retrieve the drilldownId from the AJAX request

$drilldownId = isset($_POST['drilldownId']) ? $_POST['drilldownId'] : '';
$string = $_POST['drilldownId'];

$array = explode(",", $string);
$TINGKATAN_ID = isset($array[0]) ? $array[0] : '';
$DAERAH_KEY = isset($array[1]) ? $array[1] : '';

if ($DAERAH_KEY != '') {
    $test = $getDaerah = GetQuery("SELECT c.*,t.TINGKATAN_ID,COUNT(a.TINGKATAN_ID) CTINGKATAN FROM m_tingkatan t
    LEFT JOIN m_anggota a ON a.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    WHERE t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS null AND t.TINGKATAN_ID = '$TINGKATAN_ID' AND d.DAERAH_KEY = '$DAERAH_KEY'
    GROUP BY t.TINGKATAN_ID
    HAVING COUNT(a.ANGGOTA_KEY) > 0"); 
} else {
    $test = $getDaerah = GetQuery("SELECT d.*,t.TINGKATAN_ID,COUNT(a.TINGKATAN_ID) CTINGKATAN FROM m_tingkatan t
    LEFT JOIN m_anggota a ON a.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    WHERE t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS null AND t.TINGKATAN_ID = '$TINGKATAN_ID'
    GROUP BY t.TINGKATAN_ID
    HAVING COUNT(a.ANGGOTA_KEY) > 0"); 
}

// Fetch data for the specific drilldownId from the database (replace this with your actual query)
$drilldownData = [];

while ($Daerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
    if ($DAERAH_KEY != '') {
        $drilldownData[] = array(
            'name' => $Daerah['CABANG_DESKRIPSI'],
            'y' => (int)$Daerah['CTINGKATAN'],
        );
    } else {
        $drilldownData[] = array(
            'name' => $Daerah['DAERAH_DESKRIPSI'],
            'y' => (int)$Daerah['CTINGKATAN'],
            'drilldown' => $Daerah['TINGKATAN_ID'].','. $Daerah['DAERAH_KEY']
        );
    }
}

// Return drilldown data as JSON
header('Content-Type: application/json');
echo json_encode($drilldownData);
?>
