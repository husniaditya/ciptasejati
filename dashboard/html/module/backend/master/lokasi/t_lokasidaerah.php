<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$PUSAT_KEY = "";
$DAERAH_DESKRIPSI = "";
$DAERAH_SEKRETARIAT = "";
$DAERAH_PENGURUS = "";

if (isset($_POST["savedaerah"])) {

    try {
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $PUSAT_KEY = $_POST["PUSAT_KEY"];
        $DAERAH_DESKRIPSI = $_POST["DAERAH_DESKRIPSI"];

        $GetPusatID = GetQuery("select PUSAT_ID from m_pusat where PUSAT_KEY = '$PUSAT_KEY'");
        while ($rowPusatID = $GetPusatID->fetch(PDO::FETCH_ASSOC)) {
            extract($rowPusatID);
        }

        GetQuery("insert into m_daerah select uuid(),'$PUSAT_KEY','$PUSAT_ID.$DAERAH_ID','$DAERAH_DESKRIPSI',null,null,null,0,'$USER_ID',now()");

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
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        
        $DAERAH_ID = $_POST["DAERAH_ID"];
        $PUSAT_KEY = $_POST["PUSAT_KEY"];
        $DAERAH_DESKRIPSI = $_POST["DAERAH_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        $GetPusatID = GetQuery("select PUSAT_ID from m_pusat where PUSAT_KEY = '$PUSAT_KEY'");
        while ($rowPusatID = $GetPusatID->fetch(PDO::FETCH_ASSOC)) {
            extract($rowPusatID);
        }

        GetQuery("update m_daerah set DAERAH_ID= '$PUSAT_ID.$DAERAH_ID', PUSAT_KEY = '$PUSAT_KEY', DAERAH_DESKRIPSI = '$DAERAH_DESKRIPSI', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() where DAERAH_KEY = '$DAERAH_KEY'");

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
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
    
        GetQuery("delete from m_daerah where DAERAH_KEY = '$DAERAH_KEY'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>