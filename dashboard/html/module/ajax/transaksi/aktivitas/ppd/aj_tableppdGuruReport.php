<?php

require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if (isset($_POST["PPD_LOKASI"]) || isset($_POST["PPD_TANGGAL"])) {

    $PPD_LOKASI = $_POST["PPD_LOKASI"];
    $PPD_TANGGAL = $_POST["PPD_TANGGAL"];

    $getPPD = GetQuery("SELECT c2.CABANG_DESKRIPSI PPD_CABANG,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL_FORMAT,PPD_LOKASI,PPD_TANGGAL,
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
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 1 AND (p.PPD_LOKASI LIKE CONCAT('%','$PPD_LOKASI','%')) AND (p.PPD_TANGGAL LIKE CONCAT('%','$PPD_TANGGAL','%'))
    GROUP BY p.PPD_TANGGAL,p.PPD_LOKASI
    ORDER BY p.PPD_TANGGAL DESC");
} else {
    $getPPD = GetQuery("SELECT c2.CABANG_DESKRIPSI PPD_CABANG,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL_FORMAT,PPD_LOKASI,PPD_TANGGAL,
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
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 1
    GROUP BY p.PPD_TANGGAL,p.PPD_LOKASI
    ORDER BY p.PPD_TANGGAL DESC");
}

while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPPD);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPPDGuru" class="open-ViewPPDGuru" style="color:#00a5d2;" data-tanggal="<?= $PPD_TANGGAL; ?>" data-cabangppd="<?= $PPD_LOKASI; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li class="divider"></li>
                        <li><a href="assets/print/transaksi/aktivitas/ppd/print_ppdsummaryreport.php?tgl=<?= $PPD_TANGGAL; ?>&cbg=<?= $PPD_LOKASI;?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center">
            <span class="<?= $PELATIH_BADGE; ?>"><i class="<?= $PELATIH_CLASS; ?>"></i> Koordinator </span><br> 
            <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
        </td>
        <td align="center"><?= $PPD_CABANG; ?></td>
        <td align="center"><?= $PPD_TANGGAL_FORMAT; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}

?>