<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

if (isset($_POST["saveinformasi"])) {

    try {
        
        $INFORMASI_ID = createKode("c_informasi","INFORMASI_ID","INF-$YEAR$MONTH-",3);
        $INFORMASI_JUDUL = $_POST["INFORMASI_JUDUL"];
        $INFORMASI_KATEGORI = $_POST["INFORMASI_KATEGORI"];
        $INFORMASI_DESKRIPSI = $_POST["INFORMASI_DESKRIPSI"];
        
        // INSERT DATA
        // Prepare the query
        $query = "INSERT INTO c_informasi SELECT '$INFORMASI_ID', ?, ?, ?, 0, '$USER_ID', '$localDateTime'";

        // Execute the query with parameters
        GetQuery2($query, [$INFORMASI_KATEGORI, $INFORMASI_JUDUL, $INFORMASI_DESKRIPSI]);

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updateinformasi"])) {

    try {
        
        $INFORMASI_ID = $_POST["INFORMASI_ID"];
        $INFORMASI_KATEGORI = $_POST["INFORMASI_KATEGORI"];
        $INFORMASI_JUDUL = $_POST["INFORMASI_JUDUL"];
        $INFORMASI_DESKRIPSI = $_POST["INFORMASI_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];
        
        // INSERT DATA
        // Prepare the query
        $query = "UPDATE c_informasi SET INFORMASI_JUDUL = ?, INFORMASI_KATEGORI = ?, INFORMASI_DESKRIPSI = ?, DELETION_STATUS = ?, INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' WHERE INFORMASI_ID = '$INFORMASI_ID'";

        // Execute the query with parameters
        GetQuery2($query, [$INFORMASI_JUDUL, $INFORMASI_KATEGORI, $INFORMASI_DESKRIPSI, $DELETION_STATUS]);

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
        $INFORMASI_ID = $_POST["INFORMASI_ID"];
    
        GetQuery("UPDATE c_informasi SET DELETION_STATUS = 1 WHERE INFORMASI_ID = '$INFORMASI_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>