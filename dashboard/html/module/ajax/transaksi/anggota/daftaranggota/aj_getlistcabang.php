<?php
require_once("../../../../../module/connection/conn.php");

$DAERAH_ID = $_POST["daerah_id"];
?>
<option value="">Pilih Cabang</option>
<?php

$getListCab = GetQuery("SELECT * FROM m_cabang WHERE DELETION_STATUS = 0 AND DAERAH_ID = '$DAERAH_ID' order by CABANG_DESKRIPSI asc");

while ($ListCab = $getListCab->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <option value="<?= $ListCab['CABANG_ID']; ?>"><?= $ListCab['CABANG_DESKRIPSI'];?></option>
    <?php
}
?>
