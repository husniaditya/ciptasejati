<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["saveppd"])) {
    try {
        $PPD_ID = createKode("t_ppd","PPD_ID","PPD-$YEAR$MONTH-",3);
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        $PPD_JENIS = $_POST["PPD_JENIS"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];

        GetQuery("INSERT INTO `t_ppd` (`PPD_ID`, `CABANG_KEY`, `ANGGOTA_KEY`, `TINGKATAN_ID`, `PPD_JENIS`, `PPD_LOKASI`, `PPD_TANGGAL`, `PPD_DESKRIPSI`, `PPD_FILE`, `PPD_APPROVE_PELATIH`, `PPD_APPROVE_PELATIH_TGL`, `PPD_APPROVE_GURU`, `PPD_APPROVE_GURU_TGL`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES ('$PPD_ID', '$CABANG_KEY', '$ANGGOTA_KEY', '$TINGKATAN_ID', '$PPD_JENIS', '$PPD_LOKASI', '$PPD_TANGGAL', '$PPD_DESKRIPSI', '', '0', NULL, '0', NULL, '0', '$USER_ID', now())");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_KEY, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'I', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updateppd"])) {
    try {
        $PPD_ID = $_POST["PPD_ID"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        $PPD_JENIS = $_POST["PPD_JENIS"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];

        GetQuery("update t_ppd set TINGKATAN_ID = '$TINGKATAN_ID', PPD_TANGGAL = '$PPD_TANGGAL', CABANG_KEY = '$CABANG_KEY', ANGGOTA_KEY = '$ANGGOTA_KEY', PPD_JENIS = '$PPD_JENIS', PPD_LOKASI = '$PPD_LOKASI', PPD_DESKRIPSI = '$PPD_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = NOW() where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_KEY, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) {

    try {
        $PPD_ID = $_POST["PPD_ID"];

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
    
        GetQuery("update t_ppd set DELETION_STATUS = 1 where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_KEY, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'D', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>