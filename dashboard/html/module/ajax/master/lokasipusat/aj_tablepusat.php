<?php
require_once ("../../../../module/connection/conn.php");


$getPusat = GetQuery("select * from m_pusat p left join m_anggota a on p.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 where p.DELETION_STATUS = 0");

while ($rowPusat = $getPusat->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPusat);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:#222222;" data-key="<?=$PUSAT_KEY;?>" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;" data-key="<?=$PUSAT_KEY;?>" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletePusat('<?= $PUSAT_KEY;?>','deletepusat')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><?= $PUSAT_ID; ?></td>
        <td><?= $PUSAT_DESKRIPSI; ?></td>
        <td><?= $PUSAT_SEKRETARIAT; ?></td>
        <td><?= $PUSAT_KEPENGURUSAN; ?></td>
        <td><?= $PUSAT_LAT; ?></td>
        <td><?= $PUSAT_LONG; ?></td>
        <td><iframe src="<?= $PUSAT_MAP; ?>" width="300" height="175" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
    </tr>
    <?php
}
?>