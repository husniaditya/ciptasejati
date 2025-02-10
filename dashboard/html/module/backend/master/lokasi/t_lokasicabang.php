<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];


if (isset($_POST["savecabang"])) {

    try {
        $CABANG_ID = $_POST["CABANG_ID"];
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $CABANG_PENGURUS = $_POST["CABANG_PENGURUS"];
        $CABANG_SEKRETARIAT = $_POST["CABANG_SEKRETARIAT"];
        $CABANG_MAP = $_POST["CABANG_MAP"];
        $CABANG_LAT = $_POST["CABANG_LAT"];
        $CABANG_LONG = $_POST["CABANG_LONG"];
        
        // GET DAERAH
        $params = array_fill(0, 14, '');
        $params[0] = 'GET';
        $params[2] = $DAERAH_KEY;
        $getDaerah = GetQueryParam("zsp_m_cabang", $params);
        foreach ($getDaerah as $rowDaerah) {
            extract($rowDaerah);
        }

        // INSERT
        $params = array_fill(0, 14, '');
        $params[0] = 'INSERT';
        $params[2] = $DAERAH_KEY;
        $params[3] = $DAERAH_ID;
        $params[5] = $CABANG_ID;
        $params[6] = $CABANG_DESKRIPSI;
        $params[7] = $CABANG_PENGURUS;
        $params[8] = $CABANG_SEKRETARIAT;
        $params[9] = $CABANG_MAP;
        $params[10] = $CABANG_LAT;
        $params[11] = $CABANG_LONG;
        $params[12] = $USER_ID;
        $params[13] = $localDateTime;
        GetQueryParam("zsp_m_cabang", $params);

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
        $CABANG_KEY = $_POST["CABANG_KEY"];
        $CABANG_ID = $_POST["CABANG_ID"];
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $CABANG_PENGURUS = $_POST["CABANG_PENGURUS"];
        $CABANG_SEKRETARIAT = $_POST["CABANG_SEKRETARIAT"];
        $CABANG_MAP = $_POST["CABANG_MAP"];
        $CABANG_LAT = $_POST["CABANG_LAT"];
        $CABANG_LONG = $_POST["CABANG_LONG"];

        // GET DAERAH
        $params = array_fill(0, 14, '');
        $params[0] = 'GET';
        $params[2] = $DAERAH_KEY;
        $getDaerah = GetQueryParam("zsp_m_cabang", $params);
        foreach ($getDaerah as $rowDaerah) {
            extract($rowDaerah);
        }

        // UPDATE
        $params = array_fill(0, 14, '');
        $params[0] = 'UPDATE';
        $params[2] = $DAERAH_KEY;
        $params[3] = $DAERAH_ID;
        $params[4] = $CABANG_KEY;
        $params[5] = $CABANG_ID;
        $params[6] = $CABANG_DESKRIPSI;
        $params[7] = $CABANG_SEKRETARIAT;
        $params[8] = $CABANG_PENGURUS;
        $params[9] = $CABANG_MAP;
        $params[10] = $CABANG_LAT;
        $params[11] = $CABANG_LONG;
        $params[12] = $USER_ID;
        $params[13] = $localDateTime;
        GetQueryParam("zsp_m_cabang", $params);

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
        $CABANG_KEY = $_POST["ID"];
        
        // DELETE
        $params = array_fill(0, 14, '');
        $params[0] = 'DELETE';
        $params[4] = $CABANG_KEY;
        GetQueryParam("zsp_m_cabang", $params);
        
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>