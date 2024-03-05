<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$VISIMISI_ID=$_POST["id"];

$getVisiMisi = GetQuery("select * from c_visimisi where VISIMISI_ID = '$VISIMISI_ID'"); 

$data = array();
while ($rowVisiMisi = $getVisiMisi->fetch(PDO::FETCH_ASSOC)) {
    $data['VISIMISI_ID'] = $rowVisiMisi["VISIMISI_ID"];
    $data['VISIMISI_KATEGORI'] = $rowVisiMisi["VISIMISI_KATEGORI"];
    $data['VISIMISI_DESKRIPSI'] = $rowVisiMisi["VISIMISI_DESKRIPSI"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
