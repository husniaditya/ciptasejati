<?php

require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if (isset($_POST["PPD_LOKASI"]) || isset($_POST["PPD_TANGGAL"])) {

    $PPD_LOKASI = $_POST["PPD_LOKASI"];
    $PPD_TANGGAL = $_POST["PPD_TANGGAL"];

    $getPPD = GetQuery("SELECT ROW_NUMBER() OVER (ORDER BY p.PPD_ID) AS row_num,p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA PPD_TINGKATAN,t2.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,c2.CABANG_DESKRIPSI PPD_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE,
    CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS
    FROM t_ppd p
    LEFT JOIN m_anggota a ON p.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 1 AND p.PPD_APPROVE_GURU = 0 AND p.PPD_LOKASI = '$PPD_LOKASI' AND p.PPD_TANGGAL = '$PPD_TANGGAL'
    ORDER BY p.PPD_ID");

    $response = [
        "PPD_CABANG" => '',
        "table_rows" => ''
    ];

    while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
        extract($rowPPD);
        $response["PPD_CABANG"] = $PPD_CABANG;  // Assuming all rows have the same PPD_CABANG value
        $response["table_rows"] .= "
        <tr>
            <td align='center'>{$row_num}</td>
            <td align='center'>{$PPD_ID}</td>
            <td align='center'>{$ANGGOTA_ID}</td>
            <td align='center'>{$ANGGOTA_NAMA}</td>
            <td align='center'>{$DAERAH_DESKRIPSI}</td>
            <td align='center'>{$CABANG_DESKRIPSI}</td>
            <td align='center'>{$ANGGOTA_RANTING}</td>
            <td align='center'>{$PPD_JENIS}</td>
            <td align='center'>{$TINGKATAN_NAMA} - {$TINGKATAN_SEBUTAN}</td>
            <td align='center'>{$PPD_TINGKATAN} - {$PPD_SEBUTAN}</td>
            <td align='center'>{$PPD_CABANG}</td>
            <td align='center'>{$PPD_TANGGAL}</td>
            <td>{$PPD_DESKRIPSI}</td>
        </tr>";
    }

    echo json_encode($response);
}

?>