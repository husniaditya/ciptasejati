<?php
require_once("../../../../module/connection/conn.php");

$getDaerah = GetQuery("SELECT t.*,COUNT(a.TINGKATAN_ID) CTINGKATAN FROM m_tingkatan t
LEFT JOIN m_anggota a ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE t.DELETION_STATUS = 0 AND a.ANGGOTA_AKSES <> 'Administrator' AND a.ANGGOTA_RESIGN IS null
GROUP BY t.TINGKATAN_ID
HAVING COUNT(a.ANGGOTA_KEY) > 0"); 

$data = array();
while ($Daerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array(
        'tingkatan' => $Daerah['TINGKATAN_NAMA'],
        'tingkatan_anggota' => (int)$Daerah['CTINGKATAN'],
        'drilldown' => $Daerah['TINGKATAN_ID']
    );
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
