<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["savepusatdata"])) {

    try {
        $CABANG_ID = $_POST["CABANG_ID"];
        $PUSATDATA_KATEGORI = $_POST["PUSATDATA_KATEGORI"];
        $PUSATDATA_JUDUL = $_POST["PUSATDATA_JUDUL"];
        $PUSATDATA_DESKRIPSI = $_POST["PUSATDATA_DESKRIPSI"];

        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_ID = '$CABANG_ID'");
        while ($rowNamaCabang = $getNamaCabang->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaCabang);
        }

        // Create directory if not exists
        if (!file_exists("../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI)) {
            mkdir("../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI, 0777, true);
        }

        // Directory to store files
        $directory = "../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI."/";

        // Handle PUSATDATA_FILE files
        if (!empty($_FILES['PUSATDATA_FILE']['tmp_name'][0])) {
            foreach ($_FILES['PUSATDATA_FILE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['PUSATDATA_FILE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI."/" . $idCardFileName;
            }
        }
        else {
            $idCardFileDestination = "";
        }

        GetQuery("insert into m_pusatdata select uuid(), '$CABANG_ID', '$PUSATDATA_KATEGORI', '$PUSATDATA_JUDUL', '$PUSATDATA_DESKRIPSI', '$idCardFileDestination','$idCardFileName', '0', '$USER_ID', now()");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editpusatdata"])) {

    try {
        $PUSATDATA_ID = $_POST["PUSATDATA_ID"];
        $CABANG_ID = $_POST["CABANG_ID"];
        $PUSATDATA_KATEGORI = $_POST["PUSATDATA_KATEGORI"];
        $PUSATDATA_JUDUL = $_POST["PUSATDATA_JUDUL"];
        $PUSATDATA_DESKRIPSI = $_POST["PUSATDATA_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_ID = '$CABANG_ID'");
        while ($rowNamaCabang = $getNamaCabang->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaCabang);
        }

        // Create directory if not exists
        if (!file_exists("../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI)) {
            mkdir("../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI, 0777, true);
        }

        // Directory to store files
        $directory = "../../../../assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI."/";

        // Handle PUSATDATA_FILE files
        if (!empty($_FILES['PUSATDATA_FILE']['tmp_name'][0])) {
            foreach ($_FILES['PUSATDATA_FILE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['PUSATDATA_FILE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/dataterpusat/".$CABANG_DESKRIPSI."/".$PUSATDATA_KATEGORI."/" . $idCardFileName;

                GetQuery("update m_pusatdata set PUSATDATA_FILE = '$idCardFileDestination', PUSATDATA_FILENAMA = '$idCardFileName' where PUSATDATA_ID = '$PUSATDATA_ID'");
            }
        }

        GetQuery("update m_pusatdata set CABANG_ID = '$CABANG_ID', PUSATDATA_KATEGORI = '$PUSATDATA_KATEGORI', PUSATDATA_JUDUL = '$PUSATDATA_JUDUL', PUSATDATA_DESKRIPSI = '$PUSATDATA_DESKRIPSI', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() where PUSATDATA_ID = '$PUSATDATA_ID'");

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
        $PUSATDATA_ID = $_POST["PUSATDATA_ID"];
    
        GetQuery("delete from m_pusatdata where PUSATDATA_ID = '$PUSATDATA_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>