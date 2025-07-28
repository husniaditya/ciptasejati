<?php
require_once ("../../../../module/connection/conn.php");


$PPARAM = "Daerah";
$params = ['GET', $PPARAM] + array_fill(0, 10, '');
$getDaerah = GetQueryParam("zsp_m_daerah", $params);

foreach ($getDaerah as $rowDaerah) {
    extract($rowDaerah);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-ViewDaerah" style="color:#222222;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>"  data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-EditDaerah" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletedaerah('<?= $DAERAH_KEY;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $DAERAH_ID; ?></td>
        <td><?= $PUSAT_DESKRIPSI; ?></td>
        <td><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $DAERAH_STATUS; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>