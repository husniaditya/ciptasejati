<?php
require_once ("../../../../../module/connection/conn.php");


$getInformasi = GetQuery("SELECT i.*,case when i.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END INFORMASI_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(i.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_informasi i 
LEFT JOIN m_anggota a ON i.INPUT_BY = a.ANGGOTA_ID");

while ($rowInformasi = $getInformasi->fetch(PDO::FETCH_ASSOC)) {
    extract($rowInformasi);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewInformasi" class="open-ViewInformasi" style="color:#222222;" data-id="<?= $INFORMASI_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditInformasi" class="open-EditInformasi" style="color:cornflowerblue;" data-id="<?= $INFORMASI_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteInformasi('<?= $INFORMASI_ID;?>','deleteinformasi')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><?= $INFORMASI_ID; ?></td>
        <td align="center"><?= $INFORMASI_KATEGORI; ?></td>
        <td align="center"><?= $INFORMASI_JUDUL; ?></td>
        <td><?= $INFORMASI_DESKRIPSI; ?></td>
        <td align="center"><?= $INFORMASI_STATUS; ?></td>
        <td align="center"><?= $INPUT_BY; ?></td>
        <td align="center"><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>