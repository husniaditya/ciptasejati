<?php
require_once ("../../../../module/connection/conn.php");

$EVENT_ID = $_POST["EVENT_ID"];

$GetFile = GetQuery("select PUSATDATA_FILE,PUSATDATA_FILENAMA from m_pusatdata where PUSATDATA_ID = '$EVENT_ID'");
while ($rowFile = $GetFile->fetch(PDO::FETCH_ASSOC)) {
    extract($rowFile);
    ?>
    <div class="col-md-12">
        <a href="<?= $PUSATDATA_FILE; ?>" target="_blank" style="font-size: large;">
        <i class="fa-solid fa-paperclip"></i> <?= $PUSATDATA_FILENAMA; ?>
        </a>
    </div>
    <?php
}
?>
