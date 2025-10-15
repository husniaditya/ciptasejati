<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["saveidsertifikat"])) {

    try {
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $IDSERTIFIKAT_DESKRIPSI = $_POST["IDSERTIFIKAT_DESKRIPSI"];

        // Directory to store files
        $directory = "../../../../images/idsertifikat/";

        // Handle ID_CARD_FRONT files
        if (!empty($_FILES['ID_CARD_FRONT']['tmp_name'][0])) {
            foreach ($_FILES['ID_CARD_FRONT']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ID_CARD_FRONT']['name'][$key];
                $idCardFileDestinationFront = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestinationFront);

                // Re-initialize the variable for database
                $idCardFileDestinationFront = "./images/idsertifikat/" . $idCardFileName;
            }
        }
        else {
            $idCardFileDestinationFront = "";
        }

        // Handle ID_CARD_BACK files
        if (!empty($_FILES['ID_CARD_BACK']['tmp_name'][0])) {
            foreach ($_FILES['ID_CARD_BACK']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ID_CARD_BACK']['name'][$key];
                $idCardFileDestinationBack = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestinationBack);

                // Re-initialize the variable for database
                $idCardFileDestinationBack = "./images/idsertifikat/" . $idCardFileName;
            }
        }
        else {
            $idCardFileDestinationBack = "";
        }

        // Handle SERTIFIKAT files
        if (!empty($_FILES['SERTIFIKAT']['tmp_name'][0])) {
            foreach ($_FILES['SERTIFIKAT']['tmp_name'] as $key => $sertifikatFileTmp) {
                $sertifikatFileName = $_FILES['SERTIFIKAT']['name'][$key];
                $sertifikatFileDestination = $directory . "/" . $sertifikatFileName;
                move_uploaded_file($sertifikatFileTmp, $sertifikatFileDestination);

                // Re-initialize the variable for database
                $sertifikatFileDestination = "./images/idsertifikat/" . $sertifikatFileName;
            }
        }
        else {
            $sertifikatFileDestination = "";
        }

        GetQuery("insert into m_idsertifikat select uuid(), '$TINGKATAN_ID', '$IDSERTIFIKAT_DESKRIPSI', '$idCardFileDestinationFront','$idCardFileDestinationBack','$idCardFileName','$sertifikatFileDestination','$sertifikatFileName', '0', '$USER_ID', '$localDateTime'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editsertifikat"])) {

    try {
        $IDSERTIFIKAT_ID = $_POST["IDSERTIFIKAT_ID"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $IDSERTIFIKAT_DESKRIPSI = $_POST["IDSERTIFIKAT_DESKRIPSI"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        // Directory to store files
        $directory = "../../../../images/idsertifikat/";

        // Handle ID_CARD_FRONT files
        if (!empty($_FILES['ID_CARD_FRONT']['tmp_name'][0])) {
            foreach ($_FILES['ID_CARD_FRONT']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ID_CARD_FRONT']['name'][$key];
                $idCardFileDestinationFront = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestinationFront);

                // Re-initialize the variable for database
                $idCardFileDestinationFront = "./images/idsertifikat/" . $idCardFileName;

                GetQuery("update m_idsertifikat set IDSERTIFIKAT_IDFILE_FRONT = '$idCardFileDestinationFront' where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");
            }
        }

        // Handle ID_CARD_BACK files
        if (!empty($_FILES['ID_CARD_BACK']['tmp_name'][0])) {
            foreach ($_FILES['ID_CARD_BACK']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ID_CARD_BACK']['name'][$key];
                $idCardFileDestinationBack = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestinationBack);

                // Re-initialize the variable for database
                $idCardFileDestinationBack = "./images/idsertifikat/" . $idCardFileName;

                GetQuery("update m_idsertifikat set IDSERTIFIKAT_IDFILE_BACK = '$idCardFileDestinationBack' where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");
            }
        }

        // Handle SERTIFIKAT files
        if (!empty($_FILES['SERTIFIKAT']['tmp_name'][0])) {
            foreach ($_FILES['SERTIFIKAT']['tmp_name'] as $key => $sertifikatFileTmp) {
                $sertifikatFileName = $_FILES['SERTIFIKAT']['name'][$key];
                $sertifikatFileDestination = $directory . "/" . $sertifikatFileName;
                move_uploaded_file($sertifikatFileTmp, $sertifikatFileDestination);

                // Re-initialize the variable for database
                $sertifikatFileDestination = "./images/idsertifikat/" . $sertifikatFileName;

                GetQuery("update m_idsertifikat set IDSERTIFIKAT_SERTIFIKATFILE = '$sertifikatFileDestination' where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");
            }
        }

        GetQuery("update m_idsertifikat set TINGKATAN_ID = '$TINGKATAN_ID', IDSERTIFIKAT_DESKRIPSI = '$IDSERTIFIKAT_DESKRIPSI', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");

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
        $IDSERTIFIKAT_ID = $_POST["IDSERTIFIKAT_ID"];
    
        GetQuery("delete from m_idsertifikat where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>