<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$UKT_ID = $_POST["id"];

$getPenguji = GetQuery("SELECT p.*, a.ANGGOTA_ID, a.ANGGOTA_NAMA, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN,ROW_NUMBER() OVER (ORDER BY p._key) AS row_num
FROM t_ukt_penguji p
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE p.UKT_ID = '$UKT_ID' AND p.DELETION_STATUS = 0");

while ($rowPenguji = $getPenguji->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPenguji);
    ?>
    <tr>
        <td align="center"><?= $row_num; ?>.</td>
        <td><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
    </tr>
    <?php
}

?>