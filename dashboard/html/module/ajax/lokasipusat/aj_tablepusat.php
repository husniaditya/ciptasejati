<?php
require_once ("../../../module/connection/conn.php");


$getPusat = GetQuery("select * from m_pusat p left join m_anggota a on p.INPUT_BY = a.ANGGOTA_ID");

while ($rowPusat = $getPusat->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPusat);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= $PUSAT_ID; ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td hidden><?= $PUSAT_ID; ?></td>
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