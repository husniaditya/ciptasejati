<?php
require_once ("../../../../module/connection/conn.php");


$getSertifikat = GetQuery("SELECT s.*,t.TINGKATAN_NAMA,a.ANGGOTA_NAMA,DATE_FORMAT(s.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when s.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END SERTIFIKAT_STATUS
FROM m_idsertifikat s
LEFT JOIN m_tingkatan t ON s.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_anggota a ON s.INPUT_BY = a.ANGGOTA_ID");

while ($rowSertifikat = $getSertifikat->fetch(PDO::FETCH_ASSOC)) {
    extract($rowSertifikat);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewSertifikat" data-id="<?=$IDSERTIFIKAT_ID;?>" data-tingkatanid="<?=$TINGKATAN_ID;?>" data-tingkatannama="<?=$TINGKATAN_NAMA;?>" data-desk="<?=$IDSERTIFIKAT_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-sertifikatstatus="<?=$SERTIFIKAT_STATUS;?>" class="open-ViewSertifikat" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditSertifikat" data-id="<?=$IDSERTIFIKAT_ID;?>" data-tingkatanid="<?=$TINGKATAN_ID;?>" data-tingkatannama="<?=$TINGKATAN_NAMA;?>" data-desk="<?=$IDSERTIFIKAT_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-sertifikatstatus="<?=$SERTIFIKAT_STATUS;?>" class="open-EditSertifikat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deletesertifikat('<?= $IDSERTIFIKAT_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $IDSERTIFIKAT_ID; ?></td>
        <td><?= $TINGKATAN_NAMA; ?></td>
        <td><?= $IDSERTIFIKAT_DESKRIPSI; ?></td>
        <td align="center">
            <div>
                <a href="<?= $IDSERTIFIKAT_IDFILE; ?>" target="_blank">
                <img src="<?= $IDSERTIFIKAT_IDFILE; ?>" alt="Image" width="100" height="75" />
                </a>
            </div>
        </td>
        <td align="center">
            <div>
                <a href="<?= $IDSERTIFIKAT_SERTIFIKATFILE; ?>" target="_blank">
                <img src="<?= $IDSERTIFIKAT_SERTIFIKATFILE; ?>" alt="Image" width="100" height="75" />
                </a>
            </div>
        </td>
        <td align="center"><?= $SERTIFIKAT_STATUS; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>