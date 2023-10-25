<?php
require_once ("../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];


if (isset($_POST["GUEST_NAME"]) || isset($_POST["GUEST_ADDRESS"]) || isset($_POST["GUEST_PHONE"]) || isset($_POST["GUEST_RELATION"])) {
    $GUEST_NAME = $_POST["GUEST_NAME"];
    $GUEST_ADDRESS = $_POST["GUEST_ADDRESS"];
    $GUEST_PHONE = $_POST["GUEST_PHONE"];
    $GUEST_RELATION = $_POST["GUEST_RELATION"];

    $GetGuest = GetQuery("select * from m_guest where PROJECT_OWNER = '$PROJECT_OWNER' and GUEST_STATUS = '0' AND (GUEST_NAME LIKE CONCAT('%', '$GUEST_NAME', '%')) and  (GUEST_ADDRESS LIKE CONCAT('%', '$GUEST_ADDRESS', '%')) and (GUEST_PHONE LIKE CONCAT('%', '$GUEST_PHONE', '%')) and (GUEST_RELATION LIKE CONCAT('%', '$GUEST_RELATION', '%')) order by GUEST_NAME");

} else {
    $GetGuest = GetQuery("select * from m_guest where PROJECT_OWNER = '$PROJECT_OWNER' and GUEST_STATUS = '0' order by GUEST_NAME");
}

while ($rowGuest = $GetGuest->fetch(PDO::FETCH_ASSOC)) {
    extract($rowGuest);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewGuest" class="open-ViewGuest" data-guestid="<?= $GUEST_ID; ?>" data-name="<?= $GUEST_NAME; ?>" data-address="<?= $GUEST_ADDRESS; ?>" data-phone="<?= $GUEST_PHONE; ?>" data-relation="<?= $GUEST_RELATION; ?>" style="color:forestgreen;"><span class="ico-check"></span> View</a></li>
                        <li><a data-toggle="modal" href="#EditGuest" class="open-EditGuest editButtons" data-guestid="<?= $GUEST_ID; ?>" data-name="<?= $GUEST_NAME; ?>" data-address="<?= $GUEST_ADDRESS; ?>" data-phone="<?= $GUEST_PHONE; ?>" data-relation="<?= $GUEST_RELATION; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Edit</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="GuestconfirmAndPost('<?= $GUEST_ID;?>','deleteguest')" style="color:firebrick;"><span class="ico-trash"></span> Delete</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $GUEST_ID;?></td>
        <td><?= $GUEST_NAME;?></td>
        <td><?= $GUEST_ADDRESS;?></td>
        <td><?= $GUEST_PHONE;?></td>
        <td><?= $GUEST_RELATION;?></td>
    </tr>
    <?php
}
?>