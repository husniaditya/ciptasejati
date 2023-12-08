<?php
require_once("../../../../../module/connection/conn.php");

if (isset($_POST["CABANG_ID"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["ANGGOTA_KTP"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["ANGGOTA_HP"]) || isset($_POST["ANGGOTA_JOIN"])) {

    $CABANG_ID = $_POST["CABANG_ID"];
    $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    $ANGGOTA_KTP = $_POST["ANGGOTA_KTP"];
    $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
    $ANGGOTA_HP = $_POST["ANGGOTA_HP"];
    $ANGGOTA_JOIN = $_POST["ANGGOTA_JOIN"];

    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_ID = c.CABANG_ID
    LEFT JOIN m_daerah d ON c.DAERAH_ID = d.DAERAH_ID
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE (a.CABANG_ID LIKE CONCAT('%','$CABANG_ID','%')) AND (a.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (a.ANGGOTA_KTP LIKE CONCAT('%','$ANGGOTA_KTP','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (a.ANGGOTA_HP LIKE CONCAT('%','$ANGGOTA_HP','%')) AND (a.ANGGOTA_JOIN LIKE CONCAT('%','$ANGGOTA_JOIN','%'))");
} else {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_ID = c.CABANG_ID
    LEFT JOIN m_daerah d ON c.DAERAH_ID = d.DAERAH_ID
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
                        <li><a data-toggle="modal" href="#ViewPusatdata" class="open-ViewPusatdata" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditPusatdata" class="open-EditPusatdata" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletepusatdata('<?= $PUSATDATA_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
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