<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$PUSAT_DESKRIPSI="";
$PUSAT_SEKRETARIAT="";
$PUSAT_KEPENGURUSAN="";
$PUSAT_MAP="";
$PUSAT_LAT="";
$PUSAT_LONG="";


if (isset($_POST["savepusat"])) {

    try {
        $PUSAT_ID = autoInc("m_pusat","PUSAT_ID",3);
        $PUSAT_DESKRIPSI = $_POST["PUSAT_DESKRIPSI"];
        $PUSAT_SEKRETARIAT = $_POST["PUSAT_SEKRETARIAT"];
        $PUSAT_KEPENGURUSAN = $_POST["PUSAT_KEPENGURUSAN"];
        $PUSAT_MAP = $_POST["PUSAT_MAP"];
        $PUSAT_LAT = $_POST["PUSAT_LAT"];
        $PUSAT_LONG = $_POST["PUSAT_LONG"];

        GetQuery("insert into m_pusat select uuid(),'$PUSAT_ID','$PUSAT_DESKRIPSI','$PUSAT_SEKRETARIAT','$PUSAT_KEPENGURUSAN','$PUSAT_MAP','$PUSAT_LAT','$PUSAT_LONG',0,'$USER_ID','$localDateTime'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editpusat"])) {

    try {
        $PUSAT_KEY = $_POST["PUSAT_KEY"];
        $PUSAT_DESKRIPSI = $_POST["PUSAT_DESKRIPSI"];
        $PUSAT_SEKRETARIAT = $_POST["PUSAT_SEKRETARIAT"];
        $PUSAT_KEPENGURUSAN = $_POST["PUSAT_KEPENGURUSAN"];
        $PUSAT_MAP = $_POST["PUSAT_MAP"];
        $PUSAT_LAT = $_POST["PUSAT_LAT"];
        $PUSAT_LONG = $_POST["PUSAT_LONG"];

        $response = GetQuery("update m_pusat set PUSAT_DESKRIPSI = '$PUSAT_DESKRIPSI', PUSAT_SEKRETARIAT = '$PUSAT_SEKRETARIAT', PUSAT_KEPENGURUSAN = '$PUSAT_KEPENGURUSAN', PUSAT_MAP = '$PUSAT_MAP', PUSAT_LAT = '$PUSAT_LAT', PUSAT_LONG = '$PUSAT_LONG', INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' where PUSAT_KEY = '$PUSAT_KEY'");

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
        $PUSAT_KEY = $_POST["PUSAT_KEY"];
    
        GetQuery("delete from m_pusat where PUSAT_KEY = '$PUSAT_KEY'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>