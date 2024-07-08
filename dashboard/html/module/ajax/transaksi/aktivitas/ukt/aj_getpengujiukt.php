<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$CABANG_KEY = $_POST["cabang"];

$getPenguji = GetQuery("SELECT p.*, a.ANGGOTA_ID, a.ANGGOTA_NAMA, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN,ROW_NUMBER() OVER (ORDER BY p._key) AS row_num
FROM t_ukt_penguji p
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE p.UKT_ID = 'Temp_$USER_ID' AND a.CABANG_KEY = '$CABANG_KEY' AND p.DELETION_STATUS = 0");

while ($rowPenguji = $getPenguji->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPenguji);
    ?>
    <tr>
        <td align="center"><?= $row_num; ?>.</td>
        <td><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center">
            <button type="button" id="addhapuspenguji" data-id="<?= $_key; ?>" data-cabang="<?= $CABANG_KEY; ?>" class="addhapuspenguji btn btn-danger mb5"><span class="ico-trash"></span> </button>
        </td>
    </tr>
    <?php
}

?>