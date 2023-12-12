<?php
require_once("../../../../../module/connection/conn.php");

$ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];

$GetPhoto = GetQuery("select ANGGOTA_PIC from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");
while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPhoto);
    ?>
    <a href="<?= $ANGGOTA_PIC; ?>" target="_blank">
        <img src="<?= $ANGGOTA_PIC; ?>" alt="Image" style="max-width: 75%; height: auto;" />
    </a>

    <?php
}
?>
