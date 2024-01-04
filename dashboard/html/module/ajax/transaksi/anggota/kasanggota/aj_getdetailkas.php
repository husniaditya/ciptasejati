<?php
require_once("../../../../../module/connection/conn.php");

$KAS_ID = $_POST["KAS_ID"];
$ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
$KAS_JENIS = $_POST["KAS_JENIS"];

$getDetail = GetQuery("SELECT 
k.*,
d.DAERAH_KEY,
c.CABANG_KEY,
d.DAERAH_DESKRIPSI,
c.CABANG_DESKRIPSI,
a.ANGGOTA_ID,
a.ANGGOTA_NAMA,
t.TINGKATAN_NAMA,
t.TINGKATAN_SEBUTAN,
a2.ANGGOTA_NAMA INPUT_BY,
DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL,
DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
FORMAT(ABS(REPLACE(k.KAS_JUMLAH,'-','')), 0) KAS_JUMLAH,
CONCAT(a.ANGGOTA_ID,' - ',a.ANGGOTA_NAMA) AS ANGGOTA_IDNAMA,
CASE
    WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
    ELSE FORMAT(k.KAS_JUMLAH, 0)
END AS FKAS_JUMLAH,
CASE 
    WHEN k.KAS_DK = 'D' THEN 'Debit'
    ELSE 'Kredit' 
END AS KAS_DK_DES,
COALESCE(saldo_query.FKAS_SALDO, '0') AS FKAS_SALDO
FROM 
t_kas k
LEFT JOIN 
m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
LEFT JOIN 
m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
LEFT JOIN 
m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN 
m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN 
m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN 
(
    SELECT 
        ANGGOTA_KEY,
        KAS_JENIS,
        CASE
            WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
            ELSE FORMAT(SUM(KAS_JUMLAH), 0)
        END AS FKAS_SALDO
    FROM 
        t_kas
    WHERE 
        DELETION_STATUS = 0 and ANGGOTA_KEY = '$ANGGOTA_KEY' and KAS_JENIS = '$KAS_JENIS' and KAS_ID <= '$KAS_ID'
    GROUP BY 
        ANGGOTA_KEY, KAS_JENIS
) saldo_query ON k.ANGGOTA_KEY = saldo_query.ANGGOTA_KEY AND k.KAS_JENIS = saldo_query.KAS_JENIS
WHERE 
k.DELETION_STATUS = 0 AND a.DELETION_STATUS = 0 AND k.KAS_ID = '$KAS_ID'
ORDER BY 
k.KAS_ID");

// make a select sum query to get saldo awal where anggota_key = $ANGGOTA_KEY and KAS_ID < $KAS_ID and deletion_status = 0 (not deleted)
$getSaldoAwal = GetQuery("SELECT FORMAT(IFNULL(SUM(KAS_JUMLAH),0), 0) AS SALDOAWAL FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_KEY = '$ANGGOTA_KEY' AND KAS_ID < '$KAS_ID' AND KAS_JENIS = '$KAS_JENIS'");

// Initialize an associative array to hold the data
$data = array();

while ($rowSaldoAwal = $getSaldoAwal->fetch(PDO::FETCH_ASSOC)) {
    $data['SALDOAWAL'] = $rowSaldoAwal["SALDOAWAL"];
}

while ($rowDetail = $getDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['KAS_ID'] = $rowDetail["KAS_ID"];
    $data['DAERAH_KEY'] = $rowDetail["DAERAH_KEY"];
    $data['CABANG_KEY'] = $rowDetail["CABANG_KEY"];
    $data['DAERAH_DESKRIPSI'] = $rowDetail["DAERAH_DESKRIPSI"];
    $data['CABANG_DESKRIPSI'] = $rowDetail["CABANG_DESKRIPSI"];
    $data['ANGGOTA_ID'] = $rowDetail["ANGGOTA_ID"];
    $data['ANGGOTA_IDNAMA'] = $rowDetail["ANGGOTA_IDNAMA"];
    $data['ANGGOTA_NAMA'] = $rowDetail["ANGGOTA_NAMA"];
    $data['TINGKATAN_NAMA'] = $rowDetail["TINGKATAN_NAMA"];
    $data['TINGKATAN_SEBUTAN'] = $rowDetail["TINGKATAN_SEBUTAN"];
    $data['KAS_JENIS'] = $rowDetail["KAS_JENIS"];
    $data['FKAS_TANGGAL'] = $rowDetail["FKAS_TANGGAL"];
    $data['KAS_DK'] = $rowDetail["KAS_DK"];
    $data['KAS_DK_DES'] = $rowDetail["KAS_DK_DES"];
    $data['KAS_JUMLAH'] = $rowDetail["KAS_JUMLAH"];
    $data['FKAS_JUMLAH'] = $rowDetail["FKAS_JUMLAH"];
    $data['KAS_SALDO'] = $rowDetail["KAS_SALDO"];
    $data['FKAS_SALDO'] = $rowDetail["FKAS_SALDO"];
    $data['KAS_DESKRIPSI'] = $rowDetail["KAS_DESKRIPSI"];
    $data['INPUT_BY'] = $rowDetail["INPUT_BY"];
    $data['INPUT_DATE'] = $rowDetail["INPUT_DATE"];
}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>