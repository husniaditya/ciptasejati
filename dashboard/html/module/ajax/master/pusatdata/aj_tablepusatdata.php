<?php
require_once ("../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

if (isset($_POST["CABANG_KEY"]) || isset($_POST["PUSATDATA_KATEGORI"]) || isset($_POST["PUSATDATA_JUDUL"]) || isset($_POST["PUSATDATA_DESKRIPSI"]) || isset($_POST["DELETION_STATUS"])) {

    $PUSATDATA_KATEGORI = $_POST["PUSATDATA_KATEGORI"];
    $PUSATDATA_JUDUL = $_POST["PUSATDATA_JUDUL"];
    $PUSATDATA_DESKRIPSI = $_POST["PUSATDATA_DESKRIPSI"];
    $DELETION_STATUS = $_POST["DELETION_STATUS"];

    if ($USER_AKSES == "Administrator") {
        $CABANG_KEY = $_POST["CABANG_KEY"];

        $getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
        LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID
        WHERE (p.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (p.PUSATDATA_KATEGORI LIKE CONCAT('%','$PUSATDATA_KATEGORI','%')) AND (p.PUSATDATA_JUDUL LIKE CONCAT('%','$PUSATDATA_JUDUL','%')) AND (p.PUSATDATA_DESKRIPSI LIKE CONCAT('%','$PUSATDATA_DESKRIPSI','%')) AND (p.DELETION_STATUS LIKE CONCAT('%','$DELETION_STATUS','%'))");
    } else {
        $getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
        LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID
        WHERE p.CABANG_KEY = '$USER_CABANG' AND (p.PUSATDATA_KATEGORI LIKE CONCAT('%','$PUSATDATA_KATEGORI','%')) AND (p.PUSATDATA_JUDUL LIKE CONCAT('%','$PUSATDATA_JUDUL','%')) AND (p.PUSATDATA_DESKRIPSI LIKE CONCAT('%','$PUSATDATA_DESKRIPSI','%')) AND (p.DELETION_STATUS LIKE CONCAT('%','$DELETION_STATUS','%'))");
    }
    
} else {
    if ($USER_AKSES == "Administrator") {
        $getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
        LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID");
    } else {
        $getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
        LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID
        WHERE p.CABANG_KEY = '$USER_CABANG'");
    }
}

while ($rowPusatdata = $getPusatdata->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPusatdata);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_KEY;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-ViewPusatdata" style="color:#222222;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_KEY;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-EditPusatdata" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletepusatdata('<?= $PUSATDATA_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $PUSATDATA_ID; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $PUSATDATA_KATEGORI; ?></td>
        <td><?= $PUSATDATA_JUDUL; ?></td>
        <td><?= $PUSATDATA_DESKRIPSI; ?></td>
        <td align="center"><a href="<?= $PUSATDATA_FILE; ?>" target="_blank"><i class="fa-solid fa-file-circle-check fa-xl"></i><br> Lihat File</a>
        </td>
        <td align="center"><?= $PUSATDATA_STATUS; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>