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
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];

        GetQuery("INSERT INTO `t_ppd` (`PPD_ID`, `CABANG_KEY`, `ANGGOTA_ID`, `TINGKATAN_ID`, `PPD_JENIS`, `PPD_LOKASI`, `PPD_TANGGAL`, `PPD_DESKRIPSI`, `PPD_FILE`, `PPD_APPROVE_PELATIH`, `PPD_APPROVE_PELATIH_TGL`, `PPD_APPROVE_GURU`, `PPD_APPROVE_GURU_TGL`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES ('$PPD_ID', '$CABANG_KEY', '$ANGGOTA_ID', '$TINGKATAN_ID', '$PPD_JENIS', '$PPD_LOKASI', '$PPD_TANGGAL', '$PPD_DESKRIPSI', '', '0', NULL, '0', NULL, '0', '$USER_ID', now())");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'I', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

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
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];

        GetQuery("update t_ppd set TINGKATAN_ID = '$TINGKATAN_ID', PPD_TANGGAL = '$PPD_TANGGAL', CABANG_KEY = '$CABANG_KEY', ANGGOTA_ID = '$ANGGOTA_ID', PPD_JENIS = '$PPD_JENIS', PPD_LOKASI = '$PPD_LOKASI', PPD_DESKRIPSI = '$PPD_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = NOW() where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveKoordinator"])) {
    try {
        $PPD_ID = $_POST["PPD_ID"];

        $incNum = autoIncCert("t_ppd","PPD_ID",5);
        $getSertifikat = GetQuery("SELECT CONCAT('$incNum', '/', LEFT(a.ANGGOTA_ID, 7), '/', t.TINGKATAN_SERTIFIKAT, '/', 'ISBDS-CS/', 
        CASE MONTH(p.PPD_TANGGAL)
          WHEN 1 THEN 'I'
          WHEN 2 THEN 'II'
          WHEN 3 THEN 'III'
          WHEN 4 THEN 'IV'
          WHEN 5 THEN 'V'
          WHEN 6 THEN 'VI'
          WHEN 7 THEN 'VII'
          WHEN 8 THEN 'VIII'
          WHEN 9 THEN 'IX'
          WHEN 10 THEN 'X'
          WHEN 11 THEN 'XI'
          WHEN 12 THEN 'XII'
        END, 
        '/', DATE_FORMAT(p.PPD_TANGGAL, '%Y')) AS PPD_FILE_NAME
        FROM t_ppd p
        LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE p.PPD_ID = '$PPD_ID'");
        while ($rowSertifikat = $getSertifikat->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSertifikat);
        }

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 1, PPD_APPROVE_PELATIH_TGL = NOW(), PPD_FILE_NAME = '$PPD_FILE_NAME' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectKoordinator"])) {
    try {
        $PPD_ID = $_POST["PPD_ID"];

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 2, PPD_APPROVE_PELATIH_TGL = NOW() where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveGuru"])) {
    try {
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];

        GetQuery("update t_ppd set PPD_APPROVE_GURU = 1, PPD_APPROVE_GURU_TGL = NOW() where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL' and DELETION_STATUS = 0 and PPD_APPROVE_PELATIH = 1 and PPD_APPROVE_GURU = 0");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL'");

        $response="Success,null";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectGuru"])) {
    try {
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];

        GetQuery("update t_ppd set PPD_APPROVE_GURU = 2, PPD_APPROVE_GURU_TGL = NOW() where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL' and DELETION_STATUS = 0 and PPD_APPROVE_PELATIH = 1 and PPD_APPROVE_GURU = 0");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', now() from t_ppd where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL'");

        $response="Success,null";
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

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'D', '$USER_ID', now() from t_ppd where PPD_ID = '$PPD_ID'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>