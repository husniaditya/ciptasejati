<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$TINGKATAN_NAMA="";
$TINGKATAN_SEBUTAN="";
$TINGKATAN_GELAR="";
$TINGKATAN_LEVEL="";


if (isset($_POST["savetingkatan"])) {

    try {
        $TINGKATAN_NAMA = $_POST["TINGKATAN_NAMA"];
        $TINGKATAN_SEBUTAN = $_POST["TINGKATAN_SEBUTAN"];
        $TINGKATAN_GELAR = $_POST["TINGKATAN_GELAR"];
        $TINGKATAN_LEVEL = $_POST["TINGKATAN_LEVEL"];

        GetQuery("insert into m_tingkatan select uuid(),'$TINGKATAN_NAMA','$TINGKATAN_GELAR','$TINGKATAN_SEBUTAN','$TINGKATAN_LEVEL',null,0,'$USER_ID',now()");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["edittingkatan"])) {

    try {
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];

        $TINGKATAN_NAMA = $_POST["TINGKATAN_NAMA"];
        $TINGKATAN_SEBUTAN = $_POST["TINGKATAN_SEBUTAN"];
        $TINGKATAN_GELAR = $_POST["TINGKATAN_GELAR"];
        $TINGKATAN_LEVEL = $_POST["TINGKATAN_LEVEL"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        $response = GetQuery("update m_tingkatan set TINGKATAN_NAMA = '$TINGKATAN_NAMA', TINGKATAN_SEBUTAN = '$TINGKATAN_SEBUTAN', TINGKATAN_GELAR = '$TINGKATAN_GELAR', TINGKATAN_LEVEL = '$TINGKATAN_LEVEL', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() where TINGKATAN_ID = '$TINGKATAN_ID'");

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
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    
        GetQuery("update m_tingkatan set DELETION_STATUS = 1 where TINGKATAN_ID = '$TINGKATAN_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>