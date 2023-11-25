<?php
require_once ("../../../../module/connection/conn.php");


$getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
LEFT JOIN m_cabang c ON p.CABANG_ID = c.CABANG_ID
LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID");

while ($rowPusatdata = $getPusatdata->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPusatdata);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_ID;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-ViewPusatdata" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_ID;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-EditPusatdata" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletepusatdata('<?= $PUSATDATA_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $PUSATDATA_ID; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $PUSATDATA_KATEGORI; ?></td>
        <td><?= $PUSATDATA_JUDUL; ?></td>
        <td><?= $PUSATDATA_DESKRIPSI; ?></td>
        <td align="center"><a href="<?= $PUSATDATA_FILE; ?>" target="_blank"><i class="fa-solid fa-file-circle-check"></i><br> Lihat File</a>
        </td>
        <td align="center"><?= $PUSATDATA_STATUS; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>