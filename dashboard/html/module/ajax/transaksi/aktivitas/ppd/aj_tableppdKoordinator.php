<?php

require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["PPD_LOKASI"]) || isset($_POST["PPD_ID"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["PPD_JENIS"]) || isset($_POST["PPD_TANGGAL"])) {

    if ($USER_AKSES == "Administrator") {
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
    } else {
        $DAERAH_KEY = "";
        $CABANG_KEY = "";
    }
    $PPD_LOKASI = $_POST["PPD_LOKASI"];
    $PPD_ID = $_POST["PPD_ID"];
    $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $PPD_JENIS = $_POST["PPD_JENIS"];
    $PPD_TANGGAL = $_POST["PPD_TANGGAL"];

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
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 0 AND (d.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%')) AND (p.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (p.PPD_LOKASI LIKE CONCAT('%','$PPD_LOKASI','%')) AND (t2.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (p.PPD_ID LIKE CONCAT('%','$PPD_ID','%')) AND (p.PPD_JENIS LIKE CONCAT('%','$PPD_JENIS','%')) AND (p.PPD_TANGGAL LIKE CONCAT('%','$PPD_TANGGAL','%'))
    ORDER BY p.PPD_ID DESC");
} else {
    if ($USER_AKSES == "Administrator") {
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
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
        LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
        WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 0
        ORDER BY p.PPD_ID DESC");
    } else {
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
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
        LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
        WHERE p.DELETION_STATUS = 0 AND p.CABANG_KEY = '$USER_CABANG' AND p.PPD_APPROVE_PELATIH = 0
        ORDER BY p.PPD_ID DESC");
    }
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
                        <li><a data-toggle="modal" href="#ApprovePPDKoordinator" class="open-ApprovePPDKoordinator" style="color:#00a5d2;" data-id="<?= $PPD_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><span class="ico-edit"></span> Persetujuan</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td>
            <?= $PPD_ID; ?><br> 
            <span class="<?= $PELATIH_BADGE; ?>"><i class="<?= $PELATIH_CLASS; ?>"></i> Koordinator </span><br> 
            <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
        </td>
        <td align="center"><?= $ANGGOTA_ID; ?></td>
        <td align="center"><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $ANGGOTA_RANTING; ?></td>
        <td align="center"><?= $PPD_JENIS; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center"><?= $PPD_TINGKATAN; ?> - <?= $PPD_SEBUTAN; ?></td>
        <td align="center"><?= $PPD_CABANG; ?></td>
        <td align="center"><?= $PPD_TANGGAL; ?></td>
        <td><?= $PPD_DESKRIPSI; ?></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"><?= $PPD_FILE; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}

?>