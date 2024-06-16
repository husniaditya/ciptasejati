<?php
require_once("../../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$ANGGOTA_ID = $_POST["ANGGOTA_KEY"];
if ($USER_AKSES == "Administrator") {
    $CABANG_KEY = $_POST["CABANG_KEY"];
} else {
    $CABANG_KEY = $USER_CABANG;
}

$GetPhoto = GetQuery("select ANGGOTA_PIC from m_anggota where ANGGOTA_ID = '$ANGGOTA_ID' AND CABANG_KEY = '$CABANG_KEY' AND DELETION_STATUS = 0 AND ANGGOTA_STATUS = 0");
while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPhoto);
    ?>
    <a href="<?= $ANGGOTA_PIC; ?>" target="_blank">
        <img src="<?= $ANGGOTA_PIC; ?>" alt="Image" style="width: 300px; height: 300px;text-align: center;overflow: hidden;position: relative; object-fit:contain" />
    </a>

    <?php
}
?>
