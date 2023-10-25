<?php
require_once ("../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];



$GetGuest = GetQuery("select *,date_format(COUNTDOWN_DATE,'%Y-%m-%d %H:%m') COUNTDOWN_DATE from m_countdown where PROJECT_OWNER = '$PROJECT_OWNER' and COUNTDOWN_STATUS = 0");

while ($rowGuest = $GetGuest->fetch(PDO::FETCH_ASSOC)) {
    extract($rowGuest);
    ?>
    <tr>
        <td align="center">
            <form id="countdown-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewCountdown" class="open-ViewCountdown" data-countid="<?= $COUNTDOWN_ID; ?>" data-text1="<?= $COUNTDOWN_TEXT1; ?>" data-text2="<?= $COUNTDOWN_TEXT2; ?>" data-date="<?= $COUNTDOWN_DATE; ?>" style="color:forestgreen;"><span class="ico-check"></span> View</a></li>
                        <li><a data-toggle="modal" href="#EditCountdown" class="open-EditCountdown editButtons" data-countid="<?= $COUNTDOWN_ID; ?>" data-text1="<?= $COUNTDOWN_TEXT1; ?>" data-text2="<?= $COUNTDOWN_TEXT2; ?>" data-date="<?= $COUNTDOWN_DATE; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Edit</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="CountconfirmAndPost('<?= $COUNTDOWN_ID;?>','deletecount')" style="color:firebrick;"><span class="ico-trash"></span> Delete</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $COUNTDOWN_ID;?></td>
        <td><?= $COUNTDOWN_TEXT1;?></td>
        <td><?= $COUNTDOWN_TEXT2;?></td>
        <td><?= $COUNTDOWN_DATE;?></td>
    </tr>
    <?php
}
?>