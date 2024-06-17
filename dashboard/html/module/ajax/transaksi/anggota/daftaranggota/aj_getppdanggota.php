<?php
require_once("../../../../../module/connection/conn.php");


if (isset($_POST['id'])) {
    $ANGGOTA_ID = $_POST['id'];

    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA PPD_TINGKATAN,t2.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,c2.CABANG_DESKRIPSI PPD_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE,
    CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS,
    CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END PELATIH_CLASS,
    CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN p.PPD_APPROVE_GURU = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END GURU_CLASS,
    CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'badge badge-inverse'
    WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS PELATIH_BADGE,
    CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'badge badge-inverse'
    WHEN p.PPD_APPROVE_GURU = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS GURU_BADGE
    FROM t_ppd p
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0 AND p.ANGGOTA_ID = '$ANGGOTA_ID'
    ORDER BY p.PPD_ID");

    while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
        extract($rowPPD);
        ?>
        <tr>
            <td align="center"><a href="assets/print/transaksi/aktivitas/ppd/print_ppdreportanggota.php?id=<?= $PPD_ID; ?>" target="_blank" style="color: #00b1e1;"><i class="fa-solid fa-print"></i> Cetak</a></td>
            <td>
                <?= $PPD_ID; ?><br> 
                <span class="<?= $PELATIH_BADGE; ?>"><i class="<?= $PELATIH_CLASS; ?>"></i> Koordinator </span><br> 
                <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
            </td>
            <td align="center"><?= $ANGGOTA_ID; ?></td>
            <td align="center"><?= $ANGGOTA_NAMA; ?></td>
            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
            <td align="center"><?= $PPD_JENIS; ?></td>
            <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
            <td align="center"><?= $PPD_TINGKATAN; ?> - <?= $PPD_SEBUTAN; ?></td>
            <td align="center"><?= $PPD_CABANG; ?></td>
            <td align="center"><?= $PPD_TANGGAL; ?></td>
            <td><?= $PPD_DESKRIPSI; ?></td>
            <td align="center">
            <?php
            if ($PPD_APPROVE_GURU == 1) {
                ?>
                <div>
                    <a href="<?= $PPD_FILE; ?>" target="_blank"> <i class="fa-regular fa-file-lines"></i> <?= $PPD_FILE_NAME; ?>
                    </a>
                </div>
                <?php
            }
            ?>
            </td>
        </tr>
        <?php
    }
}

?>