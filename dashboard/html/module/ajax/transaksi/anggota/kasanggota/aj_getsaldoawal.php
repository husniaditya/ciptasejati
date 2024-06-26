<?php
require_once("../../../../../module/connection/conn.php");

if (isset($_POST["KAS_ID"])) {
    $KAS_ID = $_POST["KAS_ID"];
    $KAS_JENIS = $_POST["KAS_JENIS"];
    $ANGGOTA_ID = $_POST["ANGGOTA_KEY"];
    $CABANG_KEY = $_POST["CABANG_KEY"];

    $getSaldoAwal = GetQuery("SELECT FORMAT(IFNULL(SUM(KAS_JUMLAH),0), 0) AS SALDOAWAL FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_ID = '$ANGGOTA_ID' AND KAS_JENIS = '$KAS_JENIS' AND KAS_ID < '$KAS_ID' AND CABANG_KEY = '$CABANG_KEY'");

    // Initialize an associative array to hold the data
    $data = array();

    while ($rowSaldoAwal = $getSaldoAwal->fetch(PDO::FETCH_ASSOC)) {
        $data['SALDOAWAL'] = $rowSaldoAwal["SALDOAWAL"];
    }

} else {
    $KAS_JENIS = $_POST["KAS_JENIS"];
    $ANGGOTA_ID = $_POST["ANGGOTA_KEY"];
    $CABANG_KEY = $_POST["CABANG_KEY"];

    $getSaldoAwal = GetQuery("SELECT FORMAT(IFNULL(SUM(KAS_JUMLAH),0), 0) AS SALDOAWAL FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_ID = '$ANGGOTA_ID' AND CABANG_KEY = '$CABANG_KEY' AND KAS_JENIS = '$KAS_JENIS' AND KAS_TANGGAL <= (SELECT MAX(KAS_TANGGAL) FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_ID = '$ANGGOTA_ID' AND CABANG_KEY = '$CABANG_KEY' and KAS_JENIS = '$KAS_JENIS')");

    // Initialize an associative array to hold the data
    $data = array();

    while ($rowSaldoAwal = $getSaldoAwal->fetch(PDO::FETCH_ASSOC)) {
        $data['SALDOAWAL'] = $rowSaldoAwal["SALDOAWAL"];
    }
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>