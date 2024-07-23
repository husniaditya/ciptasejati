<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

if (isset($_POST["savekegiatan"])) {

    try {
        
        $KEGIATAN_ID = createKode("c_kegiatan","KEGIATAN_ID","KGT-$YEAR$MONTH-",3);
        $KEGIATAN_JUDUL = $_POST["KEGIATAN_JUDUL"];
        $KEGIATAN_DESKRIPSI = $_POST["KEGIATAN_DESKRIPSI"];
        
        // INSERT DATA
        // Prepare the query
        $query = "INSERT INTO c_kegiatan SELECT '$KEGIATAN_ID', ?, ?, null, null, 0, '$USER_ID', '$localDateTime'";

        // Execute the query with parameters
        GetQuery2($query, [$KEGIATAN_JUDUL, $KEGIATAN_DESKRIPSI]);

        // Directory to store files
        $directory = "../../../../../assets/images/cms/kegiatan";

        // Handle KEGIATAN_IMAGE files
        if (!empty($_FILES['KEGIATAN_IMAGE']['tmp_name'][0])) {
            foreach ($_FILES['KEGIATAN_IMAGE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['KEGIATAN_IMAGE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/cms/kegiatan/" . $idCardFileName;

                GetQuery("update c_kegiatan set KEGIATAN_IMAGE = '$idCardFileDestination', KEGIATAN_IMAGE_NAMA = '$idCardFileName' where KEGIATAN_ID = '$KEGIATAN_ID'");
            }
        }
        else {
            $idCardFileDestination = "";
            $idCardFileName="";
        }

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updatekegiatan"])) {

    try {
        
        $KEGIATAN_ID = $_POST["KEGIATAN_ID"];
        $KEGIATAN_JUDUL = $_POST["KEGIATAN_JUDUL"];
        $KEGIATAN_DESKRIPSI = $_POST["KEGIATAN_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];
        
        // INSERT DATA
        // Prepare the query
        $query = "UPDATE c_kegiatan SET KEGIATAN_JUDUL = ?, KEGIATAN_DESKRIPSI = ?, DELETION_STATUS = ? WHERE KEGIATAN_ID = '$KEGIATAN_ID'";

        // Execute the query with parameters
        GetQuery2($query, [$KEGIATAN_JUDUL, $KEGIATAN_DESKRIPSI, $DELETION_STATUS]);

        // Directory to store files
        $directory = "../../../../../assets/images/cms/kegiatan";

        // Handle KEGIATAN_IMAGE files
        if (!empty($_FILES['KEGIATAN_IMAGE']['tmp_name'][0])) {
            foreach ($_FILES['KEGIATAN_IMAGE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['KEGIATAN_IMAGE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/cms/kegiatan/" . $idCardFileName;

                GetQuery("update c_kegiatan set KEGIATAN_IMAGE = '$idCardFileDestination', KEGIATAN_IMAGE_NAMA = '$idCardFileName' where KEGIATAN_ID = '$KEGIATAN_ID'");
            }
        }
        else {
            $idCardFileDestination = "";
            $idCardFileName="";
        }

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
        $KEGIATAN_ID = $_POST["KEGIATAN_ID"];
    
        GetQuery("UPDATE c_kegiatan SET DELETION_STATUS = 1 WHERE KEGIATAN_ID = '$KEGIATAN_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>