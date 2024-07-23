<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["savewarnalambang"])) {

    try {
        $WLAMBANG_KATEGORI = $_POST["WLAMBANG_KATEGORI"];
        $WLAMBANG_DESKRIPSI = $_POST["WLAMBANG_DESKRIPSI"];


        GetQuery("INSERT INTO c_warnalambang (WLAMBANG_ID,WLAMBANG_KATEGORI, WLAMBANG_DESKRIPSI, DELETION_STATUS, INPUT_BY, INPUT_DATE) VALUES (UUID(),'$WLAMBANG_KATEGORI', '$WLAMBANG_DESKRIPSI', 0, '$USER_ID', '$localDateTime')");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["editwarnalambang"])) {

    try {
        $WLAMBANG_ID = $_POST["WLAMBANG_ID"];
        $WLAMBANG_KATEGORI = $_POST["WLAMBANG_KATEGORI"];
        $WLAMBANG_DESKRIPSI = $_POST["WLAMBANG_DESKRIPSI"];


        GetQuery("UPDATE c_warnalambang SET WLAMBANG_KATEGORI = '$WLAMBANG_KATEGORI', WLAMBANG_DESKRIPSI = '$WLAMBANG_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' WHERE WLAMBANG_ID = '$WLAMBANG_ID'");

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
        $WLAMBANG_ID = $_POST["id"];
    
        GetQuery("update c_warnalambang set DELETION_STATUS = 1, INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime'  where WLAMBANG_ID = '$WLAMBANG_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>