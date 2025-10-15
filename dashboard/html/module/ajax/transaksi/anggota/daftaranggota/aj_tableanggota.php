<?php
require_once("../../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_RANTING"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["ANGGOTA_AKSES"]) || isset($_POST["ANGGOTA_STATUS"])) {

    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
    $ANGGOTA_RANTING = $_POST["ANGGOTA_RANTING"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
    $ANGGOTA_AKSES = $_POST["ANGGOTA_AKSES"];
    $ANGGOTA_STATUS = $_POST["ANGGOTA_STATUS"];

    if ($USER_AKSES == "Administrator") {
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
        
        $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,t.TINGKATAN_LEVEL,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
        LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE (a.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (a.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_RANTING LIKE CONCAT('%','$ANGGOTA_RANTING','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (a.ANGGOTA_AKSES LIKE CONCAT('%','$ANGGOTA_AKSES','%')) AND (a.ANGGOTA_STATUS LIKE CONCAT('%','$ANGGOTA_STATUS','%')) and (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) and (c.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%'))");
    } else {
        $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,t.TINGKATAN_LEVEL,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
        LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE a.CABANG_KEY = '$USER_CABANG' AND (a.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_RANTING LIKE CONCAT('%','$ANGGOTA_RANTING','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (a.ANGGOTA_AKSES LIKE CONCAT('%','$ANGGOTA_AKSES','%')) AND (a.ANGGOTA_STATUS LIKE CONCAT('%','$ANGGOTA_STATUS','%')) and (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%'))");
    }
} else {
    if ($USER_AKSES == "Administrator") {
        $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,t.TINGKATAN_LEVEL,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
        LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID");
    } else {
        $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,t.TINGKATAN_LEVEL,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
        LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE a.CABANG_KEY = '$USER_CABANG'");
    }
}

while ($rowAnggota = $getAnggotadata->fetch(PDO::FETCH_ASSOC)) {
    extract($rowAnggota);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        if ($_SESSION['VIEW_DaftarAnggota'] == "Y") {
                            ?>
                            <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:#222222;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-agama="<?= $ANGGOTA_AGAMA; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>" data-akses="<?= $ANGGOTA_AKSES; ?>" data-status="<?= $ANGGOTA_STATUS; ?>" data-statusdes="<?= $STATUS_DES; ?>" data-ranting="<?= $ANGGOTA_RANTING; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                            <?php
                        }
                        if ($_SESSION['EDIT_DaftarAnggota'] == "Y") {
                            ?>
                            <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-agama="<?= $ANGGOTA_AGAMA; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>" data-akses="<?= $ANGGOTA_AKSES; ?>" data-status="<?= $ANGGOTA_STATUS; ?>" data-statusdes="<?= $STATUS_DES; ?>" data-ranting="<?= $ANGGOTA_RANTING; ?>"><span class="ico-edit"></span> Ubah</a></li>
                            <?php
                        }
                        if ($TINGKATAN_LEVEL > 1) {
                            ?>
                            <li><a data-toggle="modal" href="#CardId" class="open-CardId" style="color:darkgoldenrod;" data-key="<?= encodeIdToBase64($ANGGOTA_KEY); ?>" data-id="<?= $ANGGOTA_KEY; ?>" data-id2="<?= $ANGGOTA_ID; ?>" data-kta="<?= htmlspecialchars($ANGGOTA_KTA ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-nama="<?= htmlspecialchars($ANGGOTA_NAMA, ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-regular fa-id-card"></i> Kartu ID Anggota</a></li>
                            <?php
                        }
                        if ($_SESSION['DELETE_DaftarAnggota'] == "Y") {
                            ?>
                            <li class="divider"></li>
                            <li><a href="#" onclick="deletedaftaranggota('<?= $ANGGOTA_KEY;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $ANGGOTA_ID; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $ANGGOTA_TEMPAT_LAHIR; ?> <br> <?= $TGL_LAHIR; ?></td>
        <td align="center"><?= $ANGGOTA_KELAMIN; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center"><?= $TINGKATAN_GELAR; ?></td>
        <td align="center"><?= $ANGGOTA_KTP; ?></td>
        <td><?= $ANGGOTA_HP; ?></td>
        <td><?= $ANGGOTA_EMAIL; ?></td>
        <td align="center"><?= $ANGGOTA_RANTING; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $TGL_JOIN; ?></td>
        <td><?= $STATUS_DES; ?></td>
        <td><?= $TGL_RESIGN; ?></td>
    </tr>
    <?php
}
?>