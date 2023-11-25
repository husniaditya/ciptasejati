<?php
require_once ("../../../../module/connection/conn.php");

$EVENT_ID = $_POST["EVENT_ID"];

$GetPhoto = GetQuery("select IDSERTIFIKAT_IDFILE,IDSERTIFIKAT_IDNAMA from m_idsertifikat where IDSERTIFIKAT_ID = '$EVENT_ID'");
while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPhoto);
    ?>
    <div class="col-md-12">
        <a href="<?= $IDSERTIFIKAT_IDFILE; ?>" target="_blank">
        <img src="<?= $IDSERTIFIKAT_IDFILE; ?>" alt="Image" width="50%" height="100" /><br>
        <p><?= $IDSERTIFIKAT_IDNAMA; ?></p>
    </div>
    <?php
}
?>
