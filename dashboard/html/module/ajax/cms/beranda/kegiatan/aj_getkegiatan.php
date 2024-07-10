<?php
require_once ("../../../../../module/connection/conn.php");


$getKegiatan = GetQuery("SELECT k.*,case when k.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END KEGIATAN_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_kegiatan k 
LEFT JOIN m_anggota a ON k.INPUT_BY = a.ANGGOTA_ID");

while ($rowKegiatan = $getKegiatan->fetch(PDO::FETCH_ASSOC)) {
    extract($rowKegiatan);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewKegiatan" class="open-ViewKegiatan" style="color:#222222;" data-id="<?= $KEGIATAN_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditKegiatan" class="open-EditKegiatan" style="color:cornflowerblue;" data-id="<?= $KEGIATAN_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteKegiatan('<?= $KEGIATAN_ID;?>','deletekegiatan')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><?= $KEGIATAN_ID; ?></td>
        <td align="center"><?= $KEGIATAN_JUDUL; ?></td>
        <td><?= $KEGIATAN_DESKRIPSI; ?></td>
        <td align="center"><img src="<?= $KEGIATAN_IMAGE; ?>" alt="<?= $KEGIATAN_IMAGE_NAMA; ?>" width="100" height="100"></td>
        <td align="center"><?= $KEGIATAN_STATUS; ?></td>
        <td align="center"><?= $INPUT_BY; ?></td>
        <td align="center"><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>