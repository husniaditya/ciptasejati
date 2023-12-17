<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];


if (isset($_POST["savedaftaranggota"])) {

    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
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
        $ANGGOTA_AGAMA = $_POST["ANGGOTA_AGAMA"];
        $ANGGOTA_AKSES = $_POST["ANGGOTA_AKSES"];

        $getCabangID = GetQuery("select CABANG_ID from m_cabang where CABANG_KEY = '$CABANG_KEY'");
        while ($rowCabangID = $getCabangID->fetch(PDO::FETCH_ASSOC)) {
            extract($rowCabangID);
        }

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

        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_KEY = '$CABANG_KEY'");
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
            $idCardFileDestination = "./assets/images/daftaranggota/default/avatar.png";
        }

        GetQuery("insert into m_anggota select uuid(), '$result.$year.$ANGGOTA_ID', '$CABANG_KEY', '$TINGKATAN_ID', '$ANGGOTA_KTP', '$ANGGOTA_NAMA', '$ANGGOTA_ALAMAT', '$ANGGOTA_AGAMA', '$ANGGOTA_PEKERJAAN', '$ANGGOTA_KELAMIN', '$ANGGOTA_TEMPAT_LAHIR', '$ANGGOTA_TANGGAL_LAHIR', '$ANGGOTA_HP', '$ANGGOTA_EMAIL', '$idCardFileDestination', '$ANGGOTA_JOIN', null, '$ANGGOTA_AKSES', '0', '0', '$USER_ID', now()");

        GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'I', '$USER_ID', now() from m_anggota where ANGGOTA_ID = '$result.$year.$ANGGOTA_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editdaftaranggota"])) {

    try {
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
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
        $ANGGOTA_STATUS = $_POST["ANGGOTA_STATUS"];
        $ANGGOTA_AKSES = $_POST["ANGGOTA_AKSES"];

        $getCabangID = GetQuery("select CABANG_ID from m_cabang where CABANG_KEY = '$CABANG_KEY'");
        while ($rowCabangID = $getCabangID->fetch(PDO::FETCH_ASSOC)) {
            extract($rowCabangID);
        }

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

        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_KEY = '$CABANG_KEY'");
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

                GetQuery("update m_anggota set ANGGOTA_PIC = '$idCardFileDestination' where ANGGOTA_KEY = '$ANGGOTA_KEY'");
            }
        }

        if ($ANGGOTA_STATUS == 0) {
            GetQuery("update m_anggota set CABANG_KEY = '$CABANG_KEY', TINGKATAN_ID = '$TINGKATAN_ID', ANGGOTA_KTP = '$ANGGOTA_KTP', ANGGOTA_NAMA = '$ANGGOTA_NAMA', ANGGOTA_ALAMAT = '$ANGGOTA_ALAMAT', ANGGOTA_PEKERJAAN = '$ANGGOTA_PEKERJAAN', ANGGOTA_KELAMIN = '$ANGGOTA_KELAMIN', ANGGOTA_TEMPAT_LAHIR = '$ANGGOTA_TEMPAT_LAHIR', ANGGOTA_TANGGAL_LAHIR = '$ANGGOTA_TANGGAL_LAHIR', ANGGOTA_HP = '$ANGGOTA_HP', ANGGOTA_EMAIL = '$ANGGOTA_EMAIL', ANGGOTA_JOIN = '$ANGGOTA_JOIN', ANGGOTA_RESIGN = null, ANGGOTA_STATUS = '$ANGGOTA_STATUS',  ANGGOTA_AKSES = '$ANGGOTA_AKSES', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_KEY = '$ANGGOTA_KEY'");
        } else {
            GetQuery("update m_anggota set CABANG_KEY = '$CABANG_KEY', TINGKATAN_ID = '$TINGKATAN_ID', ANGGOTA_KTP = '$ANGGOTA_KTP', ANGGOTA_NAMA = '$ANGGOTA_NAMA', ANGGOTA_ALAMAT = '$ANGGOTA_ALAMAT', ANGGOTA_PEKERJAAN = '$ANGGOTA_PEKERJAAN', ANGGOTA_KELAMIN = '$ANGGOTA_KELAMIN', ANGGOTA_TEMPAT_LAHIR = '$ANGGOTA_TEMPAT_LAHIR', ANGGOTA_TANGGAL_LAHIR = '$ANGGOTA_TANGGAL_LAHIR', ANGGOTA_HP = '$ANGGOTA_HP', ANGGOTA_EMAIL = '$ANGGOTA_EMAIL', ANGGOTA_JOIN = '$ANGGOTA_JOIN', ANGGOTA_RESIGN = now(), ANGGOTA_STATUS = '$ANGGOTA_STATUS', ANGGOTA_AKSES = '$ANGGOTA_AKSES', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_KEY = '$ANGGOTA_KEY'");
        }

        GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'U', '$USER_ID', now() from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");


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
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];

        GetQuery("insert into m_anggota_log select uuid(), ANGGOTA_KEY, ANGGOTA_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_KTP, ANGGOTA_NAMA, ANGGOTA_ALAMAT, ANGGOTA_AGAMA, ANGGOTA_PEKERJAAN, ANGGOTA_KELAMIN, ANGGOTA_TEMPAT_LAHIR, ANGGOTA_TANGGAL_LAHIR, ANGGOTA_HP, ANGGOTA_EMAIL, ANGGOTA_PIC, ANGGOTA_JOIN, ANGGOTA_RESIGN, DELETION_STATUS,'U', '$USER_ID', now() from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");
    
        GetQuery("delete from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>