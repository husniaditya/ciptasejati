<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if (isset($_POST["DAERAH_AWAL_KEY"]) || isset($_POST["CABANG_AWAL_KEY"]) || isset($_POST["DAERAH_TUJUAN_KEY"]) || isset($_POST["CABANG_TUJUAN_KEY"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_NAMA"])) {

    if ($USER_AKSES == "Administrator") {
        $DAERAH_AWAL_KEY = $_POST["DAERAH_AWAL_KEY"];
        $CABANG_AWAL_KEY = $_POST["CABANG_AWAL_KEY"];
    } else {
        $DAERAH_AWAL_KEY = "";
        $CABANG_AWAL_KEY = "";
    }
    $DAERAH_TUJUAN_KEY = $_POST["DAERAH_TUJUAN_KEY"];
    $CABANG_TUJUAN_KEY = $_POST["CABANG_TUJUAN_KEY"];
    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];

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
    LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
    LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
    LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
    LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
    left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE t.DELETION_STATUS = 0 AND t.MUTASI_STATUS = 0 AND (daeawal.DAERAH_KEY LIKE CONCAT('%','$DAERAH_AWAL_KEY','%')) AND (cabawal.CABANG_KEY LIKE CONCAT('%','$CABANG_AWAL_KEY','%')) AND (daetujuan.DAERAH_KEY LIKE CONCAT('%','$DAERAH_TUJUAN_KEY','%')) AND (cabtujuan.CABANG_KEY LIKE CONCAT('%','$CABANG_TUJUAN_KEY','%')) AND (a.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%'))
    GROUP BY t.MUTASI_ID
    ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");
} else {
    if ($USER_AKSES == "Administrator") {
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
        LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
        LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
        LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
        LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
        LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
        left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
        WHERE t.DELETION_STATUS = 0 AND t.MUTASI_STATUS = 0
        GROUP BY t.MUTASI_ID
        ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");
    } else {
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
        LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
        LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
        LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
        LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
        LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
        left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
        WHERE t.DELETION_STATUS = 0 and (t.CABANG_AWAL = '$USER_CABANG' or t.CABANG_TUJUAN = '$USER_CABANG') AND t.MUTASI_STATUS = 0
        GROUP BY t.MUTASI_ID
        ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");
    }
}

while ($rowMutasi = $getMutasi->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMutasi);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        if ($MUTASI_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <li><a data-toggle="modal" href="#ApproveMutasiAnggota" class="open-ApproveMutasiAnggota" style="color:forestgreen;" data-id="<?= $MUTASI_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-cabang="<?= $CABANG_KEY; ?>"><i class="fa-regular fa-circle-question"></i> Persetujuan</a></li>
                                <?php
                            }
                            if ($USER_AKSES == "Koordinator" && $USER_CABANG <> $CABANG_AWAL) {
                                ?>
                                <li><a data-toggle="modal" href="#ApproveMutasiAnggota" class="open-ApproveMutasiAnggota" style="color:forestgreen;"><i class="fa-regular fa-circle-question"></i> Persetujuan</a></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $MUTASI_ID; ?> <br> <span class="<?= $MUTASI_BADGE; ?>"><i class="<?= $MUTASI_CLASS; ?>"></i> <?= $MUTASI_STATUS_DES; ?></span></td>
        <td align="center"><?= $ANGGOTA_ID; ?></td>
        <td align="center"><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
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
?>