<?php
require_once("../../../../../module/connection/conn.php");


if (isset($_POST['id'])) {
    $ANGGOTA_ID = $_POST['id'];

    $getPPD = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE
    WHEN u.UKT_TOTAL >= 85 THEN 'A'
    WHEN u.UKT_TOTAL >= 75 THEN 'B'
    WHEN u.UKT_TOTAL >= 60 THEN 'C'
    WHEN u.UKT_TOTAL >= 40 THEN 'D'
    ELSE 'E' END UKT_NILAI,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_KOOR = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END KOOR_CLASS,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_GURU = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END GURU_CLASS,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_KOOR = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS KOOR_BADGE,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_GURU = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS GURU_BADGE
    FROM t_ukt u
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    WHERE u.DELETION_STATUS = 0 AND a.ANGGOTA_ID = '$ANGGOTA_ID'
    ORDER BY u.UKT_ID DESC");

    while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
        extract($rowPPD);
        ?>
        <tr>
            <td align="center"><a href="<?= $UKT_FILE; ?>" target="_blank" style="color: #00b1e1;"><i class="fa-solid fa-print"></i> Cetak</a></td>
            <td>
                <?= $UKT_ID; ?><br> 
                <span class="<?= $KOOR_BADGE; ?>"><i class="<?= $KOOR_CLASS; ?>"></i> Koordinator </span><br> 
                <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
            </td>
            <td align="center"><?= $ANGGOTA_ID; ?></td>
            <td><?= $ANGGOTA_NAMA; ?></td>
            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
            <td align="center"><?= $ANGGOTA_RANTING; ?></td>
            <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
            <td align="center"><?= $UKT_CABANG; ?></td>
            <td align="center"><?= $UKT_TANGGAL; ?></td>
            <td align="center"><?= $UKT_TOTAL; ?></td>
            <td align="center"><?= $UKT_NILAI; ?></td>
            <td><?= $UKT_DESKRIPSI; ?></td>
            <td><?= $INPUT_BY; ?></td>
            <td><?= $INPUT_DATE; ?></td>
        </tr>
        <?php
    }
}

?>