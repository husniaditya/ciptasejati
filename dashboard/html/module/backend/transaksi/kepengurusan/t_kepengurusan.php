<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

// Get organization chart data
if (isset($_POST["getOrganizationData"])) {
    try {
        // Get the cabang key from POST or use user's cabang
        if ($USER_AKSES == "Administrator" && isset($_POST["CABANG_KEY"])) {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        } else {
            $CABANG_KEY = $USER_CABANG;
        }

        // Get cabang information
        $getCabang = GetQuery("SELECT CABANG_DESKRIPSI FROM m_cabang WHERE CABANG_KEY = '$CABANG_KEY'");
        $cabangData = $getCabang->fetch(PDO::FETCH_ASSOC);
        $CABANG_NAMA = $cabangData['CABANG_DESKRIPSI'];

        // Query to get organization members
        $query = "SELECT 
            a.CABANG_KEY,
            a.ANGGOTA_ID,
            a.ANGGOTA_NAMA,
            a.ANGGOTA_AKSES,
            a.ANGGOTA_PIC,
            t.TINGKATAN_NAMA,
            t.TINGKATAN_SEBUTAN 
        FROM m_anggota a
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE a.CABANG_KEY = '$CABANG_KEY' 
            AND a.ANGGOTA_AKSES NOT IN ('anggota','Administrator','user') 
            AND a.ANGGOTA_STATUS = 0 
            AND a.DELETION_STATUS = 0
        ORDER BY 
            CASE 
                WHEN a.ANGGOTA_AKSES = 'Ketua' THEN 1
                WHEN a.ANGGOTA_AKSES = 'Pengurus' THEN 2
                WHEN a.ANGGOTA_AKSES = 'Koordinator' THEN 3
                ELSE 4
            END,
            a.ANGGOTA_NAMA";

        $getMembers = GetQuery($query);
        
        $ketua = null;
        $pengurus = [];
        $koordinator = [];
        
        // Categorize members by their role
        while ($row = $getMembers->fetch(PDO::FETCH_ASSOC)) {
            // Set default image if null
            if (empty($row['ANGGOTA_PIC'])) {
                $row['ANGGOTA_PIC'] = './assets/images/daftaranggota/default/avatar.png';
            }
            
            if ($row['ANGGOTA_AKSES'] == 'Ketua') {
                $ketua = $row;
            } elseif ($row['ANGGOTA_AKSES'] == 'Pengurus') {
                $pengurus[] = $row;
            } elseif ($row['ANGGOTA_AKSES'] == 'Koordinator') {
                $koordinator[] = $row;
            }
        }

        // Prepare response
        $response = [
            'status' => 'success',
            'cabang' => [
                'nama' => $CABANG_NAMA,
            ],
            'ketua' => $ketua,
            'pengurus' => $pengurus,
            'koordinator' => $koordinator
        ];

        echo json_encode($response);

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
        echo json_encode($response);
    }
}
?>
