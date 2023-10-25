<?php
require_once ("../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$ANNOUNCEMENT_TITLE="";
$ANNOUNCEMENT_COUPLE="";
$ANNOUNCEMENT_TEXTDATE="";
$ANNOUNCEMENT_DATE="";
$ANNOUNCEMENT_VIDEO="";


if (isset($_POST["saveann"])) {

    try {
        $ANNOUCEMENT_TITLE = $_POST["ANNOUCEMENT_TITLE"];
        $ANNOUNCEMENT_COUPLE = $_POST["ANNOUNCEMENT_COUPLE"];
        $ANNOUNCEMENT_TEXTDATE = $_POST["ANNOUNCEMENT_TEXTDATE"];
        $ANNOUNCEMENT_DATE = $_POST["ANNOUNCEMENT_DATE"];
        $ANNOUNCEMENT_VIDEO = $_POST["ANNOUNCEMENT_VIDEO"];

        GetQuery("insert into m_annoucement (ANNOUNCEMENT_ID,ANNOUCEMENT_TITLE, ANNOUNCEMENT_COUPLE, ANNOUNCEMENT_TEXTDATE, ANNOUNCEMENT_DATE, ANNOUNCEMENT_VIDEO, INPUT_BY, INPUT_DATE, PROJECT_OWNER) values (uuid(),'$ANNOUCEMENT_TITLE', '$ANNOUNCEMENT_COUPLE', '$ANNOUNCEMENT_TEXTDATE', '$ANNOUNCEMENT_DATE', '$ANNOUNCEMENT_VIDEO', '$USER_ID', now(), '$PROJECT_OWNER')");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editann"])) {

    try {
        $ANNOUNCEMENT_ID = $_POST["ANNOUNCEMENT_ID"];

        $ANNOUCEMENT_TITLE = $_POST["ANNOUCEMENT_TITLE"];
        $ANNOUNCEMENT_COUPLE = $_POST["ANNOUNCEMENT_COUPLE"];
        $ANNOUNCEMENT_TEXTDATE = $_POST["ANNOUNCEMENT_TEXTDATE"];
        $ANNOUNCEMENT_DATE = $_POST["ANNOUNCEMENT_DATE"];
        $ANNOUNCEMENT_VIDEO = $_POST["ANNOUNCEMENT_VIDEO"];

        GetQuery("update m_announcement set ANNOUCEMENT_TITLE = '$ANNOUCEMENT_TITLE', ANNOUNCEMENT_COUPLE = '$ANNOUNCEMENT_COUPLE', ANNOUNCEMENT_TEXTDATE = '$ANNOUNCEMENT_TEXTDATE', ANNOUNCEMENT_DATE = '$ANNOUNCEMENT_DATE', ANNOUNCEMENT_VIDEO = '$ANNOUNCEMENT_VIDEO', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANNOUNCEMENT_ID = '$ANNOUNCEMENT_ID'");

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
        $ANNOUNCEMENT_ID = $_POST["ANNOUNCEMENT_ID"];
    
        GetQuery("update m_announcement set ANNOUNCEMENT_STATUS = 1 where ANNOUNCEMENT_ID = '$ANNOUNCEMENT_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>