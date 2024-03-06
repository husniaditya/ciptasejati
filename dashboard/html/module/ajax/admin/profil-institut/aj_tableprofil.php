<?php
require_once("../../../../module/connection/conn.php");

$getProfil = GetQuery("select * from c_profil");

while ($rowProfile = $getProfil->fetch(PDO::FETCH_ASSOC)) {
    extract($rowProfile);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditProfil" class="open-EditProfil" data-id="<?= $PROFIL_ID; ?>" style="color:forestgreen;"><i class="fa-regular fa-address-card"></i> Ubah profil</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><img src="<?= $PROFIL_LOGO; ?>" alt="A sample image" width="100" height="100"></td>
        <td><?= $PROFIL_NAMA; ?></td>
        <td><?= substr_replace($PROFIL_SEJARAH, '...', 100); ?></td>
        <td align="center"><i class="fa-solid fa-phone"></i> <br> <?= $PROFIL_TELP_1; ?></td>
        <td align="center"><i class="fa-solid fa-phone"></i> <br> <?= $PROFIL_TELP_2; ?></td>
        <td align="center"><i class="fa-solid fa-envelope"></i> <br> <?= $PROFIL_EMAIL_1; ?></td>
        <td align="center"><i class="fa-solid fa-envelope"></i> <br> <?= $PROFIL_EMAIL_2; ?></td>
    </tr>
    <?php
}
?>