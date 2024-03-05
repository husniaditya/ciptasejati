<?php
require_once("../../../../module/connection/conn.php");

$getMedia = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END MEDIA_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_mediasosial c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID");

while ($rowMedia = $getMedia->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMedia);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= $MEDIA_ID; ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditMedia" class="open-EditMedia" style="color:cornflowerblue;" data-id="<?= $MEDIA_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletemedia('<?= $MEDIA_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $MEDIA_ID; ?></td>
        <td><i class="<?= $MEDIA_ICON; ?>"></i> <?= $MEDIA_DESKRIPSI; ?></td>
        <td><a href="<?= $MEDIA_LINK; ?>" target="_blank"><?= $MEDIA_LINK; ?></td>
        <td><?= $MEDIA_STATUS; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>