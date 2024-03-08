<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];

$MENU_KEY=$_POST["id"];

$getMenu = GetQuery("SELECT m.*,u.MENU_NAMA FROM m_menu u
LEFT JOIN m_menuakses m ON u.MENU_ID = m.MENU_ID
WHERE m.MENU_KEY = '$MENU_KEY'"); 

$data = array();
while ($rowMenu = $getMenu->fetch(PDO::FETCH_ASSOC)) {
    $data['MENU_KEY'] = $rowMenu["MENU_KEY"];
    $data['MENU_ID'] = $rowMenu["MENU_ID"];
    $data['MENU_NAMA'] = $rowMenu["MENU_NAMA"];
    $data['USER_AKSES'] = $rowMenu["USER_AKSES"];
    $data['VIEW'] = $rowMenu["VIEW"];
    $data['ADD'] = $rowMenu["ADD"];
    $data['EDIT'] = $rowMenu["EDIT"];
    $data['DELETE'] = $rowMenu["DELETE"];
    $data['APPROVE'] = $rowMenu["APPROVE"];
    $data['PRINT'] = $rowMenu["PRINT"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
