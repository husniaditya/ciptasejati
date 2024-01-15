<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php

$getDaerah = GetQuery("SELECT d.*,COUNT(a.ANGGOTA_KEY) CANGGOTA FROM m_daerah d
LEFT JOIN m_cabang c ON d.DAERAH_KEY = c.DAERAH_KEY
LEFT JOIN m_anggota a ON c.CABANG_KEY = a.CABANG_KEY 
WHERE d.DELETION_STATUS = 0 and a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS null
GROUP BY d.DAERAH_KEY
HAVING COUNT(a.ANGGOTA_KEY) > 0"); 

$data = array();
while ($Daerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array(
        'daerah' => $Daerah['DAERAH_DESKRIPSI'],
        'daerah_anggota' => (int)$Daerah['CANGGOTA'],
        'drilldown' => $Daerah['DAERAH_KEY']
    );
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
