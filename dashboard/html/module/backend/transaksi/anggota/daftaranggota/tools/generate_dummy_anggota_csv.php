<?php
// Simple generator for 10k dummy anggota rows in CSV format compatible with the new template headers
// Output file: dashboard/html/module/backend/transaksi/anggota/daftaranggota/tools/dummy_anggota_10k.csv
// Columns per template: ANGGOTA_ID,CABANG_ID,RANTING,TINGKATAN,KTP,NAMA,ALAMAT,AGAMA,PEKERJAAN,JENIS_KELAMIN,TEMPAT_LAHIR,NOMOR_HP,EMAIL,TANGGAL_BERGABUNG,TANGGAL_KELUAR,AKSES,STATUS

$target = __DIR__ . DIRECTORY_SEPARATOR . 'dummy_anggota_10k.csv';
$fh = fopen($target, 'w');
if (!$fh) { fwrite(STDERR, "Cannot open $target for writing\n"); exit(1); }

$headers = ['ANGGOTA_ID','CABANG_ID','RANTING','TINGKATAN','KTP','NAMA','ALAMAT','AGAMA','PEKERJAAN','JENIS_KELAMIN','TEMPAT_LAHIR','NOMOR_HP','EMAIL','TANGGAL_BERGABUNG','TANGGAL_KELUAR','AKSES','STATUS'];
fputcsv($fh, $headers);

// Configure these to match your environment
$cabangId = '003.003'; // use a valid CABANG_ID prefix if desired; importer will derive prefix from actual CABANG_KEY anyway
$startYear = 2018; $endYear = 2025;
$agamas = ['Islam','Kristen','Katolik','Hindu','Buddha','Khonghucu'];
$jobs = ['Swasta','PNS','Wiraswasta','Pelajar','Mahasiswa','TNI/Polri'];
$aks = ['User','Koordinator'];

for ($i=1; $i<=10000; $i++) {
    $no5 = str_pad((string)(($i % 99999) ?: 1), 5, '0', STR_PAD_LEFT);
    $year = (string)rand($startYear, $endYear);
    $anggotaId = $cabangId . '.' . $year . '.' . $no5;
    $ranting = 'Ranting Dummy ' . ((($i-1)%50)+1);
    // TINGKATAN: we can put name like 'SABUK PUTIH' or keep empty to let backend fallback; choose simple numeric level
    $tingkatan = ($i % 6) + 1; // backend resolves numeric level
    $ktp = (string)rand(1000000000000000, 9999999999999999);
    $nama = 'Anggota Dummy ' . $i;
    $alamat = 'Jl. Contoh No. ' . (($i%200)+1) . ', RT ' . (($i%10)+1) . ', Kota Contoh';
    $agama = $agamas[array_rand($agamas)];
    $pekerjaan = $jobs[array_rand($jobs)];
    $jk = ($i % 2 === 0) ? 'L' : 'P';
    $tempat = 'Kota ' . (($i%100)+1);
    $hp = '08' . rand(1000000000, 9999999999);
    $email = 'anggota' . $i . '@example.com';
    $tglJoin = $year . '-' . str_pad((string)rand(1,12),2,'0',STR_PAD_LEFT) . '-' . str_pad((string)rand(1,28),2,'0',STR_PAD_LEFT);
    $tglKeluar = ''; // keep empty
    $akses = $aks[array_rand($aks)];
    $status = 0; // aktif

    $row = [$anggotaId,$cabangId,$ranting,$tingkatan,$ktp,$nama,$alamat,$agama,$pekerjaan,$jk,$tempat,$hp,$email,$tglJoin,$tglKeluar,$akses,$status];
    fputcsv($fh, $row);
}

fclose($fh);
echo "Generated: $target\n";
