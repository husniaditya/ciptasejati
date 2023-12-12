<?php
require_once("../../../../../module/connection/conn.php");

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_KTP"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["ANGGOTA_HP"]) || isset($_POST["ANGGOTA_JOIN"])) {

    $DAERAH_KEY = $_POST["DAERAH_KEY"];
    $CABANG_KEY = $_POST["CABANG_KEY"];
    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
    $ANGGOTA_KTP = $_POST["ANGGOTA_KTP"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
    $ANGGOTA_HP = $_POST["ANGGOTA_HP"];
    $ANGGOTA_JOIN = $_POST["ANGGOTA_JOIN"];

    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE (a.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (a.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_KTP LIKE CONCAT('%','$ANGGOTA_KTP','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (a.ANGGOTA_HP LIKE CONCAT('%','$ANGGOTA_HP','%')) AND (a.ANGGOTA_JOIN LIKE CONCAT('%','$ANGGOTA_JOIN','%')) and (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) and (c.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%'))");
} else {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID");
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
                        <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:forestgreen;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletedaftaranggota('<?= $ANGGOTA_KEY;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $ANGGOTA_ID; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center"><?= $TINGKATAN_GELAR; ?></td>
        <td align="center"><?= $ANGGOTA_KTP; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $ANGGOTA_KELAMIN; ?></td>
        <td><?= $ANGGOTA_TEMPAT_LAHIR; ?> <br> <?= $TGL_LAHIR; ?></td>
        <td><?= $ANGGOTA_HP; ?></td>
        <td><?= $ANGGOTA_EMAIL; ?></td>
        <td><?= $TGL_JOIN; ?></td>
        <td><?= $TGL_RESIGN; ?></td>
    </tr>
    <?php
}
?>