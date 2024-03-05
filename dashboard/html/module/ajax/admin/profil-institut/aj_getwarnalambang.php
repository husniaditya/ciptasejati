<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$WLAMBANG_ID=$_POST["id"];

$getVisiMisi = GetQuery("select * from c_warnalambang where WLAMBANG_ID = '$WLAMBANG_ID'"); 

$data = array();
while ($rowVisiMisi = $getVisiMisi->fetch(PDO::FETCH_ASSOC)) {
    $data['WLAMBANG_ID'] = $rowVisiMisi["WLAMBANG_ID"];
    $data['WLAMBANG_KATEGORI'] = $rowVisiMisi["WLAMBANG_KATEGORI"];
    $data['WLAMBANG_DESKRIPSI'] = $rowVisiMisi["WLAMBANG_DESKRIPSI"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
