<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$MEDIA_ID=$_POST["id"];

$getMedia = GetQuery("select * from c_mediasosial where MEDIA_ID = '$MEDIA_ID'"); 

$data = array();
while ($rowMedia = $getMedia->fetch(PDO::FETCH_ASSOC)) {
    $data['MEDIA_ID'] = $rowMedia["MEDIA_ID"];
    $data['MEDIA_ICON'] = $rowMedia["MEDIA_ICON"];
    $data['MEDIA_DESKRIPSI'] = $rowMedia["MEDIA_DESKRIPSI"];
    $data['MEDIA_LINK'] = $rowMedia["MEDIA_LINK"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
