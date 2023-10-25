<?php
require_once ("../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$GetGuest = GetQuery("select '',GUEST_ID,GUEST_NAME,GUEST_ADDRESS,GUEST_PHONE,GUEST_RELATION from m_guest where PROJECT_OWNER = '$PROJECT_OWNER' and GUEST_STATUS = '0' order by GUEST_NAME");

$data = array();
while ($rowGuest = $GetGuest->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array(
        "", // First column blank
        $rowGuest['GUEST_ID'],
        $rowGuest['GUEST_NAME'],
        $rowGuest['GUEST_ADDRESS'],
        $rowGuest['GUEST_PHONE'],
        $rowGuest['GUEST_RELATION']
    );
}
// Return the data as JSON
echo json_encode($data);

?>