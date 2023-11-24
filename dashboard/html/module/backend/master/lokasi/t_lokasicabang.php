<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];


if (isset($_POST["savecabang"])) {

    try {
        $CABANG_ID = $_POST["CABANG_ID"];
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $CABANG_PENGURUS = $_POST["CABANG_PENGURUS"];
        $CABANG_SEKRETARIAT = $_POST["CABANG_SEKRETARIAT"];
        $CABANG_MAP = $_POST["CABANG_MAP"];
        $CABANG_LAT = $_POST["CABANG_LAT"];
        $CABANG_LONG = $_POST["CABANG_LONG"];

        GetQuery("insert into m_cabang select '$DAERAH_ID.$CABANG_ID','$DAERAH_ID','$CABANG_DESKRIPSI','$CABANG_SEKRETARIAT','$CABANG_PENGURUS','$CABANG_MAP','$CABANG_LAT','$CABANG_LONG',0,'$USER_ID',now()");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editcabang"])) {

    try {
        $ID = $_POST["ID"];
        $CABANG_ID = $_POST["CABANG_ID"];
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $CABANG_PENGURUS = $_POST["CABANG_PENGURUS"];
        $CABANG_SEKRETARIAT = $_POST["CABANG_SEKRETARIAT"];
        $CABANG_MAP = $_POST["CABANG_MAP"];
        $CABANG_LAT = $_POST["CABANG_LAT"];
        $CABANG_LONG = $_POST["CABANG_LONG"];

        $response = GetQuery("update m_cabang set CABANG_ID = '$DAERAH_ID.$CABANG_ID',DAERAH_ID = '$DAERAH_ID', CABANG_DESKRIPSI = '$CABANG_DESKRIPSI', CABANG_SEKRETARIAT = '$CABANG_SEKRETARIAT', CABANG_PENGURUS = '$CABANG_PENGURUS', CABANG_MAP = '$CABANG_MAP', CABANG_LAT = '$CABANG_LAT', CABANG_LONG = '$CABANG_LONG', INPUT_BY = '$USER_ID', INPUT_DATE = now() where CABANG_ID = '$ID'");

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
        $CABANG_ID = $_POST["ID"];
    
        GetQuery("delete from m_cabang where CABANG_ID = '$CABANG_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>