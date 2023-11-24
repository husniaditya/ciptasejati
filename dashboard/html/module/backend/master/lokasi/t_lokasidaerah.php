<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$PUSAT_ID = "";
$DAERAH_DESKRIPSI = "";
$DAERAH_SEKRETARIAT = "";
$DAERAH_PENGURUS = "";

if (isset($_POST["savedaerah"])) {

    try {
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $PUSAT_ID = $_POST["PUSAT_ID"];
        $DAERAH_DESKRIPSI = $_POST["DAERAH_DESKRIPSI"];

        GetQuery("insert into m_daerah select '$PUSAT_ID.$DAERAH_ID','$PUSAT_ID','$DAERAH_DESKRIPSI',null,null,null,0,'$USER_ID',now()");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editdaerah"])) {

    try {
        $EDIT_ID = $_POST["EDIT_ID"];
        
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $PUSAT_ID = $_POST["PUSAT_ID"];
        $DAERAH_DESKRIPSI = $_POST["DAERAH_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        GetQuery("update m_daerah set DAERAH_ID= '$PUSAT_ID.$DAERAH_ID', PUSAT_ID = '$PUSAT_ID', DAERAH_DESKRIPSI = '$DAERAH_DESKRIPSI', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() where DAERAH_ID = '$EDIT_ID'");

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
        $DAERAH_ID = $_POST["DAERAH_ID"];
    
        GetQuery("delete from m_daerah where DAERAH_ID = '$DAERAH_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>