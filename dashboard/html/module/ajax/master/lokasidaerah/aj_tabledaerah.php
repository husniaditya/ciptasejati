<?php
require_once ("../../../../module/connection/conn.php");


$getDaerah = GetQuery("select d.*,p.PUSAT_DESKRIPSI,a.ANGGOTA_NAMA,case when d.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END DAERAH_STATUS,DATE_FORMAT(d.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,RIGHT(DAERAH_ID,3) SHORT_ID from m_daerah d left join m_pusat p on d.PUSAT_KEY = p.PUSAT_KEY left join m_anggota a on d.INPUT_BY = a.ANGGOTA_ID order by d.PUSAT_KEY asc");

while ($rowDaerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
    extract($rowDaerah);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-ViewDaerah" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>"  data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-EditDaerah" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletedaerah('<?= $DAERAH_KEY;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
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