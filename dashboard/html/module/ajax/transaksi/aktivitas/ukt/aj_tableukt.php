<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_DAERAH = $_SESSION["LOGINDAR_CS"];

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["UKT_LOKASI"]) || isset($_POST["UKT_ID"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["UKT_TANGGAL"])) {

    if ($USER_AKSES == "Administrator" || $USER_AKSES == "Pengurus Daerah") {
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
    } else {
        $DAERAH_KEY = "";
        $CABANG_KEY = "";
    }
    $UKT_LOKASI = $_POST["UKT_LOKASI"];
    $UKT_ID = $_POST["UKT_ID"];
    $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $UKT_TANGGAL = $_POST["UKT_TANGGAL"];

    $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
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
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
    LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY AND a2.ANGGOTA_STATUS = 0
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    WHERE u.DELETION_STATUS = 0 AND (d.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%')) AND (u.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (u.UKT_LOKASI LIKE CONCAT('%','$UKT_LOKASI','%')) AND (t.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (u.UKT_ID LIKE CONCAT('%','$UKT_ID','%')) AND (u.UKT_TANGGAL LIKE CONCAT('%','$UKT_TANGGAL','%'))
    ORDER BY u.UKT_ID DESC");
} else {
    if ($USER_AKSES == "Administrator") {
        $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
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
        LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY AND a2.ANGGOTA_STATUS = 0
        LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
        WHERE u.DELETION_STATUS = 0
        ORDER BY u.UKT_ID DESC");
    } elseif ($USER_AKSES == "Pengurus Daerah") {
        $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
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
        LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY AND a2.ANGGOTA_STATUS = 0
        LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
        WHERE u.DELETION_STATUS = 0 AND d.DAERAH_KEY = '$USER_DAERAH'
        ORDER BY u.UKT_ID DESC");
    } else {
        $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
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
        LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY AND a2.ANGGOTA_STATUS = 0
        LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
        WHERE u.DELETION_STATUS = 0 AND u.CABANG_KEY = '$USER_CABANG'
        ORDER BY u.UKT_ID DESC");
    }
}

while ($rowUKT = $getUKT->fetch(PDO::FETCH_ASSOC)) {
    extract($rowUKT);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewUKT" class="open-ViewUKT" style="color:#222222;" data-id="<?= $UKT_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <?php
                        if ($UKT_APP_KOOR == 0) {
                            ?>
                            <li><a data-toggle="modal" href="#EditUKT" class="open-EditUKT" style="color:#00a5d2;" data-id="<?= $UKT_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><span class="ico-edit"></span> Ubah</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onclick="eventukt('<?= $UKT_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                            <?php
                        } else {
                            ?>
                            <li class="divider"></li>
                            <li><a href="#" onclick="eventukt('<?= $UKT_ID;?>','cancel')"><i class="fa-solid fa-rotate-left"></i> Batal Persetujuan</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </form>
        </td>
        <td>
            <?= $UKT_ID; ?><br> 
            <span class="<?= $KOOR_BADGE; ?>"><i class="<?= $KOOR_CLASS; ?>"></i> Koordinator </span>
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

?>