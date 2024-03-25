<?php
require_once("../../../../module/connection/conn.php");

$getVisiMisi = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END VISIMISI_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM cms_visimisi v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID");

while ($rowVisiMisi = $getVisiMisi->fetch(PDO::FETCH_ASSOC)) {
    extract($rowVisiMisi);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditVisiMisi" class="open-EditVisiMisi" data-id="<?= $CMS_VISIMISI_ID; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Data</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><?= $CMS_VISIMISI_ID; ?></td>
        <td align="center"><?= $CMS_VISIMISI_KATEGORI; ?></td>
        <td align="center"><img src="<?= $CMS_VISIMISI_PIC; ?>" alt="A sample image" width="500" height="350"></td>
        <td align="center"><?= $INPUT_BY; ?></td>
        <td align="center"><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>