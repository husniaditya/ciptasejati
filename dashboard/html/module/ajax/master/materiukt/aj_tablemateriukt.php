<?php
require_once ("../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["MATERI_ID"]) || isset($_POST["MATERI_DESKRIPSI"])) {

    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $MATERI_ID = $_POST["MATERI_ID"];
    $MATERI_DESKRIPSI = $_POST["MATERI_DESKRIPSI"];

    if ($USER_AKSES == "Administrator") {
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_KEY = $_POST["CABANG_KEY"];

        $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
        FROM m_materi m
        LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
        LEFT JOIN m_tingkatan t ON m.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE m.DELETION_STATUS = 0 AND (d.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%')) AND (m.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (m.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (m.MATERI_ID LIKE CONCAT('%','$MATERI_ID','%')) AND (m.MATERI_DESKRIPSI LIKE CONCAT('%','$MATERI_DESKRIPSI','%'))
        ORDER BY t.TINGKATAN_LEVEL");
    } else {
        $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
        FROM m_materi m
        LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
        LEFT JOIN m_tingkatan t ON m.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE m.DELETION_STATUS = 0 AND m.CABANG_KEY = '$USER_CABANG' AND (m.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (m.MATERI_ID LIKE CONCAT('%','$MATERI_ID','%')) AND (m.MATERI_DESKRIPSI LIKE CONCAT('%','$MATERI_DESKRIPSI','%'))
        ORDER BY t.TINGKATAN_LEVEL");
    }
    
} else {
    if ($USER_AKSES == "Administrator") {
        $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
        FROM m_materi m
        LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
        LEFT JOIN m_tingkatan t ON m.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE m.DELETION_STATUS = 0
        ORDER BY t.TINGKATAN_LEVEL");
    } else {
        $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE FROM m_materi m
        LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
        LEFT JOIN m_tingkatan t ON m.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE m.DELETION_STATUS = 0 AND m.CABANG_KEY = '$USER_CABANG'
        ORDER BY t.TINGKATAN_LEVEL");
    }
}

while ($rowMateri = $getMateri->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMateri);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        if ($_SESSION["VIEW_MateriUKT"] == "Y") {
                            ?>
                            <li><a data-toggle="modal" href="#ViewMateri" data-id="<?=$MATERI_ID;?>" class="open-ViewMateri" style="color:#222222;"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                            <?php
                        }
                        if ($_SESSION["EDIT_MateriUKT"] == "Y") {
                            ?>
                            <li><a data-toggle="modal" href="#EditMateri" data-id="<?=$MATERI_ID;?>" class="open-EditMateri" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                            <?php
                        }
                        if ($_SESSION["DELETE_MateriUKT"] == "Y") {
                            ?>
                            <li class="divider"></li>
                            <li><a href="#" onclick="deletemateri('<?= $MATERI_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><?= $MATERI_ID; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
        <td><?= $MATERI_DESKRIPSI; ?></td>
        <td align="center"><?= $MATERI_BOBOT; ?>%</td>
        <td align="center"><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>