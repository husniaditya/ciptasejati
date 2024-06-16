<?php
require_once("../../../../../module/connection/conn.php");


if (isset($_POST['id'])) {
    $ANGGOTA_ID = $_POST['id'];

    $getMutasi = GetQuery("SELECT t.MUTASI_ID,daeawal.DAERAH_KEY AS DAERAH_AWAL_KEY,daeawal.DAERAH_DESKRIPSI AS DAERAH_AWAL_DES,t.CABANG_AWAL,cabawal.CABANG_DESKRIPSI AS CABANG_AWAL_DES,daetujuan.DAERAH_KEY AS DAERAH_TUJUAN_KEY,daetujuan.DAERAH_DESKRIPSI AS DAERAH_TUJUAN_DES,t.CABANG_TUJUAN,cabtujuan.CABANG_DESKRIPSI AS CABANG_TUJUAN_DES,a.ANGGOTA_KEY,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.MUTASI_DESKRIPSI,t.MUTASI_TANGGAL,t.MUTASI_STATUS,t.MUTASI_STATUS_TANGGAL,t.MUTASI_APPROVE_TANGGAL,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,t.MUTASI_FILE,
    CASE 
        WHEN t.MUTASI_STATUS = '0' THEN 'Menunggu' 
        WHEN t.MUTASI_STATUS = '1' THEN 'Disetujui' 
        ELSE 'Ditolak' 
    END AS MUTASI_STATUS_DES,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'badge badge-inverse'
        WHEN t.MUTASI_STATUS = '1' THEN 'badge badge-success' 
        ELSE 'badge badge-danger' 
    END AS MUTASI_BADGE,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'fa-solid fa-spinner fa-spin'
        WHEN t.MUTASI_STATUS = '1' THEN 'fa-solid fa-check' 
        ELSE 'fa-solid fa-xmark' 
    END AS MUTASI_CLASS
    FROM t_mutasi t
    LEFT JOIN m_anggota a ON t.ANGGOTA_ID = a.ANGGOTA_ID
    LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
    LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
    LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
    LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
    left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE t.DELETION_STATUS = 0 and t.ANGGOTA_ID = '$ANGGOTA_ID'
    ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

    while ($rowMutasi = $getMutasi->fetch(PDO::FETCH_ASSOC)) {
        extract($rowMutasi);
        ?>
        <tr>
            <td align="center"><a href="assets/print/transaksi/mutasi/print_mutasi.php?id=<?=$MUTASI_ID; ?>" target="_blank" style="color: #00b1e1;"><i class="fa-solid fa-print"></i> Cetak</a></td>
            <td><?= $MUTASI_ID; ?> <br> <span class="<?= $MUTASI_BADGE; ?>"><i class="<?= $MUTASI_CLASS; ?>"></i> <?= $MUTASI_STATUS_DES; ?></span></td>
            <td align="center"><?= $DAERAH_AWAL_DES; ?></td>
            <td align="center"><?= $CABANG_AWAL_DES; ?></td>
            <td align="center"><?= $DAERAH_TUJUAN_DES; ?></td>
            <td align="center"><?= $CABANG_TUJUAN_DES; ?></td>
            <td><?= $MUTASI_DESKRIPSI; ?></td>
            <td align="center"><?= $TANGGAL_EFEKTIF; ?></td>
            <td><?= $INPUT_BY; ?></td>
            <td><?= $INPUT_DATE; ?></td>
        </tr>
        <?php
    }
}

?>