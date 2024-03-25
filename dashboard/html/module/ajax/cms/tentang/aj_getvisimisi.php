<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$CMS_VISIMISI_ID=$_POST["id"];

$getVisiMisi = GetQuery("select * from cms_visimisi where CMS_VISIMISI_ID = '$CMS_VISIMISI_ID'");

$data = array();
while ($dataVisiMisi = $getVisiMisi->fetch(PDO::FETCH_ASSOC)) {
    $data['CMS_VISIMISI_ID'] = $dataVisiMisi["CMS_VISIMISI_ID"];
    $data['CMS_VISIMISI_KATEGORI'] = $dataVisiMisi["CMS_VISIMISI_KATEGORI"];
    $data['CMS_VISIMISI_PIC'] = $dataVisiMisi["CMS_VISIMISI_PIC"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>