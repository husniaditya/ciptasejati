<?php
require_once ("../../../../module/connection/conn.php");


$GetTingkatan = GetQuery("SELECT t.*,case when t.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END TINGKATAN_STATUS,a.ANGGOTA_NAMA,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE FROM m_tingkatan t LEFT JOIN m_anggota a ON t.INPUT_BY = a.ANGGOTA_ID where t.DELETION_STATUS = 0 order by t.TINGKATAN_LEVEL asc");

while ($rowTingkatan = $GetTingkatan->fetch(PDO::FETCH_ASSOC)) {
    extract($rowTingkatan);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewTingkatGelar" class="open-ViewTingkatGelar" style="color:#222222;" data-nama="<?= $TINGKATAN_NAMA; ?>" data-sebutan="<?= $TINGKATAN_SEBUTAN; ?>" data-gelar="<?= $TINGKATAN_GELAR;?>" data-level="<?= $TINGKATAN_LEVEL; ?>" data-status="<?= $TINGKATAN_STATUS; ?>""><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditTingkatGelar" class="open-EditTingkatGelar" style="color:cornflowerblue;" data-id="<?= $TINGKATAN_ID; ?>" data-nama="<?= $TINGKATAN_NAMA; ?>" data-sebutan="<?= $TINGKATAN_SEBUTAN; ?>" data-gelar="<?= $TINGKATAN_GELAR;?>" data-level="<?= $TINGKATAN_LEVEL; ?>" data-status="<?= $DELETION_STATUS; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteTingkatan('<?= $TINGKATAN_ID;?>','deletetingkatan')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $TINGKATAN_ID; ?></td>
        <td><?= $TINGKATAN_NAMA; ?></td>
        <td><?= $TINGKATAN_SEBUTAN; ?></td>
        <td><?= $TINGKATAN_GELAR; ?></td>
        <td><?= $TINGKATAN_LEVEL; ?></td>
        <td><?= $TINGKATAN_STATUS; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>