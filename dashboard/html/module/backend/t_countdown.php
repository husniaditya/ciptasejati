<?php
require_once ("../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$COUNTDOWN_TEXT1="";
$COUNTDOWN_TEXT2="";
$COUNTDOWN_DATE="";


if (isset($_POST["savecountdown"])) {

    try {
        $COUNTDOWN_TEXT1 = $_POST["COUNTDOWN_TEXT1"];
        $COUNTDOWN_TEXT2 = $_POST["COUNTDOWN_TEXT2"];
        $COUNTDOWN_DATE = $_POST["COUNTDOWN_DATE"];

        GetQuery("insert into m_countdown (COUNTDOWN_ID,COUNTDOWN_TEXT1, COUNTDOWN_TEXT2, COUNTDOWN_DATE, INPUT_BY, INPUT_DATE, PROJECT_OWNER) values (uuid(),'$COUNTDOWN_TEXT1', '$COUNTDOWN_TEXT2', '$COUNTDOWN_DATE', '$USER_ID', now(), '$PROJECT_OWNER')");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editcountdown"])) {

    try {
        $COUNTDOWN_ID = $_POST["COUNTDOWN_ID"];

        $COUNTDOWN_TEXT1 = $_POST["COUNTDOWN_TEXT1"];
        $COUNTDOWN_TEXT2 = $_POST["COUNTDOWN_TEXT2"];
        $COUNTDOWN_DATE = $_POST["COUNTDOWN_DATE"];

        $response = GetQuery("update m_countdown set COUNTDOWN_TEXT1 = '$COUNTDOWN_TEXT1', COUNTDOWN_TEXT2 = '$COUNTDOWN_TEXT2', COUNTDOWN_DATE = '$COUNTDOWN_DATE', INPUT_BY = '$USER_ID', INPUT_DATE = now() where COUNTDOWN_ID = '$COUNTDOWN_ID'");

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
        $COUNTDOWN_ID = $_POST["COUNTDOWN_ID"];
    
        GetQuery("update m_countdown set COUNTDOWN_STATUS = 1 where COUNTDOWN_ID = '$COUNTDOWN_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>