<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$PUSAT_DESKRIPSI="";
$PUSAT_SEKRETARIAT="";
$PUSAT_KEPENGURUSAN="";
$PUSAT_MAP="";
$PUSAT_LAT="";
$PUSAT_LONG="";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        if (isset($_POST["savepusat"])) {
            $action = "INSERT";
        } elseif (isset($_POST["editpusat"])) {
            $action = "UPDATE";
        } elseif (isset($_POST["EVENT_ACTION"])) {
            $action = "DELETE";
        } else {
            die("Invalid action received.");
        }

        $params = array_fill(0, 12, '');
        $params[0] = $action;

        if ($action === "INSERT") {
            $params[3] = autoInc("m_pusat", "PUSAT_ID", 3);
        } elseif ($action === "UPDATE" || $action === "DELETE") {
            $params[2] = $_POST["PUSAT_KEY"] ?? '';
        }

        // Assign remaining parameters
        $params[4] = $_POST["PUSAT_DESKRIPSI"] ?? '';
        $params[5] = $_POST["PUSAT_SEKRETARIAT"] ?? '';
        $params[6] = $_POST["PUSAT_KEPENGURUSAN"] ?? '';
        $params[7] = $_POST["PUSAT_MAP"] ?? '';
        $params[8] = $_POST["PUSAT_LAT"] ?? '';
        $params[9] = $_POST["PUSAT_LONG"] ?? '';
        $params[10] = $USER_ID;
        $params[11] = $localDateTime;

        GetQueryParam("zsp_m_pusat", $params);

        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>