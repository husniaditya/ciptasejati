<?php
require_once("../../../../module/connection/conn.php");

$getUser = GetQuery("SELECT u.ANGGOTA_KEY,u.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_RANTING,c.CABANG_DESKRIPSI,d.DAERAH_DESKRIPSI,a.ANGGOTA_AKSES, CASE WHEN u.USER_STATUS = 1 THEN 'Verifikasi' ELSE 'Aktif' END USER_STATUS, u.INPUT_BY, DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM m_user u
LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE a.DELETION_STATUS = 0");

while ($rowUser = $getUser->fetch(PDO::FETCH_ASSOC)) {
    extract($rowUser);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= $ANGGOTA_KEY; ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditUser" class="open-EditUser" style="color:cornflowerblue;" data-id="<?= $ANGGOTA_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteuser('<?= $ANGGOTA_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $ANGGOTA_ID; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td><?= $TINGKATAN_NAMA; ?></td>
        <td><?= $TINGKATAN_SEBUTAN; ?></td>
        <td><?= $ANGGOTA_RANTING; ?></td>
        <td><?= $CABANG_DESKRIPSI; ?></td>
        <td><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $ANGGOTA_AKSES; ?></td>
        <td><?= $USER_STATUS; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>