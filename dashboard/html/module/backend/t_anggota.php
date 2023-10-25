<?php
require_once ("../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$GUEST_NAME="";
$GUEST_ADDRESS="";
$GUEST_PHONE="";
$GUEST_RELATION="";


if (isset($_POST["saveguest"])) {

    try {
        $GUEST_NAME = $_POST["GUEST_NAME"];
        $GUEST_ADDRESS = $_POST["GUEST_ADDRESS"];
        $GUEST_PHONE = $_POST["GUEST_PHONE"];
        $GUEST_RELATION = $_POST["GUEST_RELATION"];

        GetQuery("insert into m_guest (GUEST_ID,GUEST_NAME, GUEST_ADDRESS, GUEST_PHONE, GUEST_RELATION, INPUT_BY, INPUT_DATE, PROJECT_OWNER) values (uuid(),'$GUEST_NAME', '$GUEST_ADDRESS', '$GUEST_PHONE', '$GUEST_RELATION', '$USER_ID', now(), '$PROJECT_OWNER')");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editguest"])) {

    try {
        $GUEST_ID = $_POST["GUEST_ID"];

        $GUEST_NAME = $_POST["GUEST_NAME"];
        $GUEST_ADDRESS = $_POST["GUEST_ADDRESS"];
        $GUEST_PHONE = $_POST["GUEST_PHONE"];
        $GUEST_RELATION = $_POST["GUEST_RELATION"];

        GetQuery("update m_guest set GUEST_NAME = '$GUEST_NAME', GUEST_ADDRESS = '$GUEST_ADDRESS', GUEST_PHONE = '$GUEST_PHONE', GUEST_RELATION = '$GUEST_RELATION', INPUT_BY = '$USER_ID', INPUT_DATE = now() where GUEST_ID = '$GUEST_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) {

    try {
        $GUEST_ID = $_POST["GUEST_ID"];
    
        GetQuery("update m_guest set GUEST_STATUS = 1 where GUEST_ID = '$GUEST_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>