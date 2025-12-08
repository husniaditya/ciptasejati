<?php
require_once("../../../../../module/connection/conn.php");

if (!function_exists('h')) {
    function h($v) {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

// Get the ANGGOTA_KEY from POST
$ANGGOTA_KEY = $_POST['ANGGOTA_KEY'] ?? '';

if (empty($ANGGOTA_KEY)) {
    echo json_encode(['success' => false, 'message' => 'ANGGOTA_KEY is required']);
    exit;
}

try {
    // Fetch anggota data with related information
    $sql = "SELECT a.*, 
            d.DAERAH_KEY, d.DAERAH_DESKRIPSI, 
            c.CABANG_DESKRIPSI, 
            t.TINGKATAN_NAMA, t.TINGKATAN_GELAR, t.TINGKATAN_SEBUTAN,
            RIGHT(a.ANGGOTA_ID, 5) AS SHORT_ID,
            CASE 
                WHEN a.ANGGOTA_STATUS = 0 THEN 'Aktif' 
                WHEN a.ANGGOTA_STATUS = 1 THEN 'Non Aktif' 
                ELSE 'Mutasi' 
            END AS STATUS_DES
            FROM m_anggota a
            LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
            LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
            LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
            WHERE a.ANGGOTA_KEY = :anggota_key";
    
    $stmt = $db1->prepare($sql);
    $stmt->bindParam(':anggota_key', $ANGGOTA_KEY, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data not found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
exit;
?>