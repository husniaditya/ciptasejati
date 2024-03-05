<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["savevisimisi"])) {

    try {
        $VISIMISI_ID = createKode("c_visimisi","VISIMISI_ID","VMI-$YEAR$MONTH-",3);
        $VISIMISI_KATEGORI = $_POST["VISIMISI_KATEGORI"];
        $VISIMISI_DESKRIPSI = $_POST["VISIMISI_DESKRIPSI"];


        GetQuery("INSERT INTO c_visimisi (VISIMISI_ID, VISIMISI_KATEGORI, VISIMISI_DESKRIPSI, DELETION_STATUS, INPUT_BY, INPUT_DATE) VALUES ('$VISIMISI_ID', '$VISIMISI_KATEGORI', '$VISIMISI_DESKRIPSI', 0, '$USER_ID', now())");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["editvisimisi"])) {

    try {
        $VISIMISI_ID = $_POST["VISIMISI_ID"];
        $VISIMISI_KATEGORI = $_POST["VISIMISI_KATEGORI"];
        $VISIMISI_DESKRIPSI = $_POST["VISIMISI_DESKRIPSI"];


        GetQuery("UPDATE c_visimisi SET VISIMISI_KATEGORI = '$VISIMISI_KATEGORI', VISIMISI_DESKRIPSI = '$VISIMISI_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = now() WHERE VISIMISI_ID = '$VISIMISI_ID'");

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
        $VISIMISI_ID = $_POST["id"];
    
        GetQuery("update c_visimisi set DELETION_STATUS = 1, INPUT_BY = '$USER_ID', INPUT_DATE = now()  where VISIMISI_ID = '$VISIMISI_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>