<?php
require_once ("../../../../module/connection/conn.php");


$getCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI,RIGHT(c.CABANG_ID,3) SHORT_ID FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE c.DELETION_STATUS = 0
ORDER BY c.CABANG_ID");

while ($rowCabang = $getCabang->fetch(PDO::FETCH_ASSOC)) {
    extract($rowCabang);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewCabang" class="open-ViewCabang" style="color:forestgreen;"   data-key="<?=$CABANG_KEY;?>" data-daerahid="<?=$DAERAH_KEY;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-cabangid="<?=$CABANG_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-desk="<?=$CABANG_DESKRIPSI;?>" data-pengurus="<?=$CABANG_PENGURUS;?>" data-sekre="<?=$CABANG_SEKRETARIAT;?>" data-map="<?=$CABANG_MAP;?>" data-lat="<?=$CABANG_LAT;?>" data-long="<?=$CABANG_LONG;?>"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditCabang" class="open-EditCabang" style="color:cornflowerblue;"  data-key="<?=$CABANG_KEY;?>" data-daerahid="<?=$DAERAH_KEY;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-cabangid="<?=$CABANG_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-desk="<?=$CABANG_DESKRIPSI;?>" data-pengurus="<?=$CABANG_PENGURUS;?>" data-sekre="<?=$CABANG_SEKRETARIAT;?>" data-map="<?=$CABANG_MAP;?>" data-lat="<?=$CABANG_LAT;?>" data-long="<?=$CABANG_LONG;?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteCabang('<?= $CABANG_KEY;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $CABANG_ID; ?></td>
        <td><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $CABANG_DESKRIPSI; ?></td>
        <td><?= $CABANG_SEKRETARIAT; ?></td>
        <td><?= $CABANG_PENGURUS; ?></td>
        <td><?= $CABANG_LAT; ?></td>
        <td><?= $CABANG_LONG; ?></td>
        <td>
            <iframe src="<?= $CABANG_MAP; ?>" width="250" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </td>
    </tr>
    <?php
}
?>