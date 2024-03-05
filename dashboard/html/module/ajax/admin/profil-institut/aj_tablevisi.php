<?php
require_once("../../../../module/connection/conn.php");

$getVisi = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END VISIMISI_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_visimisi v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.VISIMISI_KATEGORI = 'Visi'");

while ($rowVisi = $getVisi->fetch(PDO::FETCH_ASSOC)) {
    extract($rowVisi);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditVisiMisi" class="open-EditVisiMisi" data-id="<?= $VISIMISI_ID; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Visi</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletevisimisi('<?= $VISIMISI_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $VISIMISI_DESKRIPSI; ?></td>
        <td><?= $VISIMISI_STATUS; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>