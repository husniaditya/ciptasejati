<?php
require_once ("../../../../module/connection/conn.php");

$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$MATERI_ID = $_POST["materi"];

$getMateri = GetQuery("SELECT ROW_NUMBER() OVER (ORDER BY d.MATERI_ID) AS row_num,m.CABANG_KEY,d.* FROM m_materi_detail d
LEFT JOIN m_materi m ON m.MATERI_ID = d.MATERI_ID 
WHERE d.DELETION_STATUS = 0 AND d.MATERI_ID = '$MATERI_ID'");

while ($rowMateri = $getMateri->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMateri);
    ?>
    <tr>
        <td align="center">
            <button type="button" id="addhapusdetail" data-id="<?= $_key; ?>" data-mtr="<?= $MATERI_ID; ?>" data-cbg="<?= $CABANG_KEY; ?>" class="addhapusdetail btn btn-danger mb5"><span class="ico-trash"></span> </button>
        </td>
        <td><?= $DETAIL_DESKRIPSI; ?></td>
        <td align="center"><?= $DETAIL_BOBOT; ?></td>
    </tr>
    <?php
}
?>