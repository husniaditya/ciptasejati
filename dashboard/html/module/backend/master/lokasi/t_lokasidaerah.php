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
        
        // GET PUSAT
        $params = array_fill(0, 10, '');
        $params[0] = 'GET';
        $params[2] = $PUSAT_KEY;
        $getPusat = GetQueryParam("zsp_m_daerah", $params);
        foreach ($getPusat as $rowPusat) {
            extract($rowPusat);
        }

        // INSERT
        $params = array_fill(0, 10, '');
        $params[0] = 'INSERT';
        $params[2] = $PUSAT_KEY;
        $params[4] = $PUSAT_ID;
        $params[5] = $DAERAH_ID;
        $params[6] = $DAERAH_DESKRIPSI;
        $params[8] = $USER_ID;
        $params[9] = $localDateTime;
        GetQueryParam("zsp_m_daerah", $params);

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

        // GET PUSAT
        $params = array_fill(0, 10, '');
        $params[0] = 'GET';
        $params[2] = $PUSAT_KEY;
        $getPusat = GetQueryParam("zsp_m_daerah", $params);
        foreach ($getPusat as $rowPusat) {
            extract($rowPusat);
        }
        
        // UPDATE
        $params = array_fill(0, 10, '');
        $params[0] = 'UPDATE';
        $params[2] = $PUSAT_KEY;
        $params[3] = $DAERAH_KEY;
        $params[4] = $PUSAT_ID;
        $params[5] = $DAERAH_ID;
        $params[6] = $DAERAH_DESKRIPSI;
        $params[7] = $DELETION_STATUS;
        $params[8] = $USER_ID;
        $params[9] = $localDateTime;
        $asd = GetQueryParam("zsp_m_daerah", $params);

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
    
        // DELETE
        $params = array_fill(0, 10, '');
        $params[0] = 'DELETE';
        $params[3] = $DAERAH_KEY;
        GetQueryParam("zsp_m_daerah", $params);

        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>