<?php
require_once("../../../../../module/connection/conn.php");

$ANGGOTA_ID = $_POST["ANGGOTA_ID"];

$GetPhoto = GetQuery("select ANGGOTA_PIC from m_anggota where ANGGOTA_ID = '$ANGGOTA_ID' AND ANGGOTA_STATUS = 0");
while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPhoto);
    ?>
    <a href="<?= $ANGGOTA_PIC; ?>" target="_blank">
        <img src="<?= $ANGGOTA_PIC; ?>" alt="Image" style="width: 300px; height: 300px;text-align: center;overflow: hidden;position: relative; object-fit:contain" />
    </a>

    <?php
}
?>
