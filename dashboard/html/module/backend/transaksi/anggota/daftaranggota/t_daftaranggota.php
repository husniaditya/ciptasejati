<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];


if (isset($_POST["savedaftaranggota"])) {

    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $CABANG_ID = $_POST["CABANG_ID"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $ANGGOTA_KTP = $_POST["ANGGOTA_KTP"];
        $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
        $ANGGOTA_ALAMAT = $_POST["ANGGOTA_ALAMAT"];
        $ANGGOTA_PEKERJAAN = $_POST["ANGGOTA_PEKERJAAN"];
        $ANGGOTA_KELAMIN = $_POST["ANGGOTA_KELAMIN"];
        $ANGGOTA_TEMPAT_LAHIR = $_POST["ANGGOTA_TEMPAT_LAHIR"];
        $ANGGOTA_TANGGAL_LAHIR = $_POST["ANGGOTA_TANGGAL_LAHIR"];
        $ANGGOTA_HP = $_POST["ANGGOTA_HP"];
        $ANGGOTA_EMAIL = $_POST["ANGGOTA_EMAIL"];
        $ANGGOTA_JOIN = $_POST["ANGGOTA_JOIN"];

        // Create a DateTime object from the date string
        $date = new DateTime($ANGGOTA_JOIN);
        // Get the year from the DateTime object
        $year = $date->format("Y");

        // Split the string into an array using dot as the delimiter
        $parts = explode('.', $CABANG_ID);
        // Remove the middle part (index 1)
        unset($parts[0]);
        // Join the remaining parts back into a string using dot as the separator
        $result = implode('.', $parts);

        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_ID = '$CABANG_ID'");
        while ($rowNamaCabang = $getNamaCabang->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaCabang);
        }
        $lastAnggotaId = substr($ANGGOTA_ID, -4);

        // Create directory if not exists
        if (!file_exists("../../../../../assets/images/daftaranggota/".$CABANG_DESKRIPSI."/".$ANGGOTA_NAMA."_".$lastAnggotaId)) {
            mkdir("../../../../../assets/images/daftaranggota/".$CABANG_DESKRIPSI."/".$ANGGOTA_NAMA."_".$lastAnggotaId, 0777, true);
        }

        // Directory to store files
        $directory = "../../../../../assets/images/daftaranggota/".$CABANG_DESKRIPSI."/".$ANGGOTA_NAMA."_".$lastAnggotaId."/";

        // Handle ANGGOTA_PIC files
        if (!empty($_FILES['ANGGOTA_PIC']['tmp_name'][0])) {
            foreach ($_FILES['ANGGOTA_PIC']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ANGGOTA_PIC']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/daftaranggota/".$CABANG_DESKRIPSI."/".$ANGGOTA_NAMA."_".$lastAnggotaId."/" . $idCardFileName;
            }
        }
        else {
            $idCardFileDestination = "";
        }

        GetQuery("insert into m_anggota select uuid(), '$result.$year.$ANGGOTA_ID', '$CABANG_ID', '$TINGKATAN_ID', '$ANGGOTA_KTP', '$ANGGOTA_NAMA', '$ANGGOTA_ALAMAT', '$ANGGOTA_PEKERJAAN', '$ANGGOTA_KELAMIN', '$ANGGOTA_TEMPAT_LAHIR', '$ANGGOTA_TANGGAL_LAHIR', '$ANGGOTA_HP', '$ANGGOTA_EMAIL', '$idCardFileDestination', '$ANGGOTA_JOIN', null, '0', '$USER_ID', now()");

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

        // Handle ID_CARD files
        if (!empty($_FILES['ID_CARD']['tmp_name'][0])) {
            foreach ($_FILES['ID_CARD']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ID_CARD']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./images/idsertifikat/" . $idCardFileName;

                GetQuery("update m_idsertifikat set IDSERTIFIKAT_IDFILE = '$idCardFileDestination' where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");
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

        GetQuery("update m_idsertifikat set TINGKATAN_ID = '$TINGKATAN_ID', IDSERTIFIKAT_DESKRIPSI = '$IDSERTIFIKAT_DESKRIPSI', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() where IDSERTIFIKAT_ID = '$IDSERTIFIKAT_ID'");

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