<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["updatemenu"])) {

    try {
        $MENU_KEY = $_POST["MENU_KEY"];
        $VIEW = $_POST["VIEW"];
        $ADD = $_POST["ADD"];
        $EDIT = $_POST["EDIT"];
        $DELETE = $_POST["DELETE"];
        $APPROVE = $_POST["APPROVE"];
        $PRINT = $_POST["PRINT"];

        GetQuery("update m_menuakses set `VIEW` = '$VIEW', `ADD` = '$ADD', `EDIT` = '$EDIT', `DELETE` = '$DELETE', `APPROVE` = '$APPROVE', `PRINT` = '$PRINT' where MENU_KEY = '$MENU_KEY'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

?>