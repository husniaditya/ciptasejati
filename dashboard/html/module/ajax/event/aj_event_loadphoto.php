<?php
require_once ("../../../module/connection/conn.php");

$EVENT_ID = $_POST["EVENT_ID"];

$GetPhoto = GetQuery("select *,concat(substring(PHOTO_NAME,1,25),'...') PHOTO_NAME from d_eventphoto where EVENT_ID = '$EVENT_ID' and PHOTO_STATUS = '0'");
while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPhoto);
    ?>
    <div class="col-md-2">
        <a href="./../../<?= $PHOTO_PIC; ?>" target="_blank">
        <img src="./../../<?= $PHOTO_PIC; ?>" alt="Image" width="100%" height="100" /><br>
        <p><?= $PHOTO_NAME; ?></p>
        </a>
    </div>
    <?php
}
?>