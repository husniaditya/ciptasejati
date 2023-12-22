<?php
require_once("../../../../../module/connection/conn.php");

$MUTASI_ID = $_POST["MUTASI_ID"];

$GetDetail = GetQuery("SELECT t.MUTASI_ID,daeawal.DAERAH_KEY AS DAERAH_AWAL_KEY,daeawal.DAERAH_DESKRIPSI AS DAERAH_AWAL_DES,t.CABANG_AWAL,cabawal.CABANG_DESKRIPSI AS CABANG_AWAL_DES,daetujuan.DAERAH_KEY AS DAERAH_TUJUAN_KEY,daetujuan.DAERAH_DESKRIPSI AS DAERAH_TUJUAN_DES,t.CABANG_TUJUAN,cabtujuan.CABANG_DESKRIPSI AS CABANG_TUJUAN_DES,a.ANGGOTA_KEY,a.ANGGOTA_ID,a.ANGGOTA_NAMA,CONCAT(a.ANGGOTA_ID,' - ',a.ANGGOTA_NAMA) AS ANGGOTA_IDNAMA,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.MUTASI_DESKRIPSI,t.MUTASI_TANGGAL,t.MUTASI_STATUS,t.MUTASI_STATUS_TANGGAL,t.MUTASI_APPROVE_TANGGAL,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,a3.ANGGOTA_NAMA APPROVE_BY,DATE_FORMAT(t.MUTASI_APPROVE_TANGGAL, '%d %M %Y %H:%i') MUTASI_APP_TANGGAL,
CASE 
    WHEN t.MUTASI_STATUS = '0' THEN 'Status Persetujuan : <b><span class=\"badge badge-inverse\"><i class=\"fa-solid fa-spinner fa-spin\"></i> Menunggu Persetujuan</span></b>' 
    WHEN t.MUTASI_STATUS = '1' THEN 'Status Persetujuan : <b><span class=\"badge badge-success\"><i class=\"fa-solid fa-check\"></i> Dokumen Mutasi Disetujui</span></b>' 
    ELSE 'Status Persetujuan : <b><span class=\"badge badge-danger\"><i class=\"fa-solid fa-xmark\"></i> Dokumen Mutasi Ditolak</span></b>' 
END AS MUTASI_STATUS_DES
FROM t_mutasi t
LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
LEFT JOIN m_anggota a3 ON t.MUTASI_APPROVE_BY = a3.ANGGOTA_ID
LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
WHERE t.MUTASI_ID = '$MUTASI_ID'
ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

// Initialize an associative array to hold the data
$data = array();

while ($rowDetail = $GetDetail->fetch(PDO::FETCH_ASSOC)) {
    $data['MUTASI_ID'] = $rowDetail["MUTASI_ID"];
    $data['DAERAH_AWAL_KEY'] = $rowDetail["DAERAH_AWAL_KEY"];
    $data['DAERAH_AWAL_DES'] = $rowDetail["DAERAH_AWAL_DES"];
    $data['CABANG_AWAL'] = $rowDetail["CABANG_AWAL"];
    $data['CABANG_AWAL_DES'] = $rowDetail["CABANG_AWAL_DES"];
    $data['DAERAH_TUJUAN_KEY'] = $rowDetail["DAERAH_TUJUAN_KEY"];
    $data['DAERAH_TUJUAN_DES'] = $rowDetail["DAERAH_TUJUAN_DES"];
    $data['CABANG_TUJUAN'] = $rowDetail["CABANG_TUJUAN"];
    $data['CABANG_TUJUAN_DES'] = $rowDetail["CABANG_TUJUAN_DES"];
    $data['ANGGOTA_KEY'] = $rowDetail["ANGGOTA_KEY"];
    $data['ANGGOTA_ID'] = $rowDetail["ANGGOTA_ID"];
    $data['ANGGOTA_IDNAMA'] = $rowDetail["ANGGOTA_IDNAMA"];
    $data['ANGGOTA_NAMA'] = $rowDetail["ANGGOTA_NAMA"];
    $data['TINGKATAN_NAMA'] = $rowDetail["TINGKATAN_NAMA"];
    $data['TINGKATAN_SEBUTAN'] = $rowDetail["TINGKATAN_SEBUTAN"];
    $data['MUTASI_DESKRIPSI'] = $rowDetail["MUTASI_DESKRIPSI"];
    $data['MUTASI_TANGGAL'] = $rowDetail["MUTASI_TANGGAL"];
    $data['MUTASI_STATUS'] = $rowDetail["MUTASI_STATUS"];
    $data['MUTASI_STATUS_TANGGAL'] = $rowDetail["MUTASI_STATUS_TANGGAL"];
    $data['MUTASI_TGL'] = $rowDetail["MUTASI_TGL"];
    $data['MUTASI_STATUS_TANGGAL'] = $rowDetail["MUTASI_STATUS_TANGGAL"];
    $data['MUTASI_APPROVE_TANGGAL'] = $rowDetail["MUTASI_APPROVE_TANGGAL"];
    $data['INPUT_BY'] = $rowDetail["INPUT_BY"];
    $data['INPUT_DATE'] = $rowDetail["INPUT_DATE"];
    $data['TANGGAL_EFEKTIF'] = $rowDetail["TANGGAL_EFEKTIF"];
    $data['APPROVE_BY'] = $rowDetail["APPROVE_BY"];
    $data['MUTASI_APP_TANGGAL'] = $rowDetail["MUTASI_APP_TANGGAL"];
    $data['MUTASI_STATUS_DES'] = $rowDetail["MUTASI_STATUS_DES"];

}

// Convert the data array to JSON and echo it
header('Content-Type: application/json');
echo json_encode($data);
?>
