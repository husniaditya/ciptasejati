<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$BLOG_ID=$_POST["id"];

$getBlog = GetQuery("select * from c_blog where BLOG_ID = '$BLOG_ID'");

$data = array();
while ($dataBlog = $getBlog->fetch(PDO::FETCH_ASSOC)) {
    $data['BLOG_ID'] = $dataBlog["BLOG_ID"];
    $data['BLOG_TITLE'] = $dataBlog["BLOG_TITLE"];
    $data['BLOG_MESSAGE'] = $dataBlog["BLOG_MESSAGE"];
    $data['BLOG_IMAGE'] = $dataBlog["BLOG_IMAGE"];
    $data['DELETION_STATUS'] = $dataBlog["DELETION_STATUS"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>