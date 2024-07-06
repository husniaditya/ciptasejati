<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["saveukt"])) {
    try {
        // DECLARE VARIABLE
        $UKT_ID = createKode("t_ukt","UKT_ID","UKT-$YEAR$MONTH-",3);
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $UKT_LOKASI = $_POST["UKT_LOKASI"];
        $UKT_TANGGAL = $_POST["UKT_TANGGAL"];
        $UKT_TOTAL = null;
        $UKT_NILAI = '';
        $UKT_DESKRIPSI = $_POST["UKT_DESKRIPSI"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        // INSERT DATA
        GetQuery("INSERT INTO t_ukt SELECT '$UKT_ID', '$CABANG_KEY', '$TINGKATAN_ID', '$ANGGOTA_ID', '$UKT_LOKASI', '$UKT_TANGGAL', '$UKT_TOTAL', '$UKT_NILAI', '$UKT_DESKRIPSI', 0, null, null, 0, null, null, 0, '$USER_ID', now()");

        // DECLARE DETAIL VARIABLE
        $keys = $_POST['_key'];
        $materi = $_POST['MATERI_ID'];
        $bobot = $_POST['UKT_BOBOT'];
        $nilai = $_POST['UKT_DETAIL_NILAI'];
        $remarks = $_POST['UKT_DETAIL_REMARK'];
        
        foreach ($keys as $index => $key) {
            $materivalue = isset($materi[$index]) ? $materi[$index] : '';
            $bobotvalue = isset($bobot[$index]) ? $bobot[$index] : '';
            $nilaiValue = isset($nilai[$index]) ? $nilai[$index] : '';
            $remarkValue = isset($remarks[$index]) ? $remarks[$index] : '';
            // INSERT NEW DETAIL
            GetQuery("INSERT INTO t_ukt_detail SELECT '$UKT_ID', '$materivalue', '$key', '$bobotvalue', '$nilaiValue', '$remarkValue',0");
        }
        // UPDATE TOTAL SCORE
        GetQuery("UPDATE t_ukt
        SET UKT_TOTAL = (
            SELECT SUM(UKT_DETAIL_NILAI * (UKT_BOBOT / 100)) AS NILAI_BOBOT
            FROM t_ukt_detail
            WHERE UKT_ID = '$UKT_ID'
        )
        WHERE UKT_ID = '$UKT_ID'");
        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'I', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        $response="Success,$UKT_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updateukt"])) {
    try {
        // DECLARE VARIABLE
        $UKT_ID = $_POST["UKT_ID"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $UKT_LOKASI = $_POST["UKT_LOKASI"];
        $UKT_TANGGAL = $_POST["UKT_TANGGAL"];
        $UKT_TOTAL = null;
        $UKT_NILAI = '';
        $UKT_DESKRIPSI = $_POST["UKT_DESKRIPSI"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        // INSERT DATA
        GetQuery("UPDATE t_ukt SET TINGKATAN_ID = '$TINGKATAN_ID', ANGGOTA_ID = '$ANGGOTA_ID', UKT_LOKASI = '$UKT_LOKASI', UKT_TANGGAL = '$UKT_TANGGAL', UKT_TOTAL = '$UKT_TOTAL', UKT_NILAI = '$UKT_NILAI', UKT_DESKRIPSI = '$UKT_DESKRIPSI', CABANG_KEY = '$CABANG_KEY', INPUT_BY = '$USER_ID', INPUT_DATE = now() WHERE UKT_ID = '$UKT_ID'");
        // DELETE DETAIL BEFORE INSERT
        GetQuery("DELETE FROM t_ukt_detail WHERE UKT_ID = '$UKT_ID'");

        $keys = $_POST['_key'];
        $materi = $_POST['MATERI_ID'];
        $bobot = $_POST['UKT_BOBOT'];
        $nilai = $_POST['UKT_DETAIL_NILAI'];
        $remarks = $_POST['UKT_DETAIL_REMARK'];
        
        foreach ($keys as $index => $key) {
            $materivalue = isset($materi[$index]) ? $materi[$index] : '';
            $bobotvalue = isset($bobot[$index]) ? $bobot[$index] : '';
            $nilaiValue = isset($nilai[$index]) ? $nilai[$index] : '';
            $remarkValue = isset($remarks[$index]) ? $remarks[$index] : '';
            // INSERT NEW DETAIL
            GetQuery("INSERT INTO t_ukt_detail SELECT '$UKT_ID', '$materivalue', '$key', '$bobotvalue', '$nilaiValue', '$remarkValue',0");
        }
        // UPDATE TOTAL SCORE
        GetQuery("UPDATE t_ukt
        SET UKT_TOTAL = (
            SELECT SUM(UKT_DETAIL_NILAI * (UKT_BOBOT / 100)) AS NILAI_BOBOT
            FROM t_ukt_detail
            WHERE UKT_ID = '$UKT_ID'
        )
        WHERE UKT_ID = '$UKT_ID'");
        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        $response="Success,$UKT_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveKoordinator"])) {
    try {
        $UKT_ID = $_POST["UKT_ID"];
        // UPDATE APPROVAL KOORDINATOR STATUS
        GetQuery("update t_ukt set UKT_APP_KOOR = 1, UKT_APP_KOOR_BY = '$USER_ID', UKT_APP_KOOR_DATE = NOW() where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        $response="Success,$UKT_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectKoordinator"])) {
    try {
        $UKT_ID = $_POST["UKT_ID"];
        // UPDATE APPROVAL KOORDINATOR STATUS
        GetQuery("update t_ukt set UKT_APP_KOOR = 2, UKT_APP_KOOR_BY = '$USER_ID', UKT_APP_KOOR_DATE = NOW() where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        $response="Success,$UKT_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveGuru"])) {
    try {
        $UKT_ID = $_POST["UKT_ID"];
        // UPDATE APPROVAL GURU STATUS
        GetQuery("update t_ukt set UKT_APP_GURU = 1, UKT_APP_GURU_BY = '$USER_ID', UKT_APP_GURU_DATE = NOW() where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

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
        $UKT_ID = $_POST["UKT_ID"];
        // UPDATE APPROVAL GURU STATUS
        GetQuery("update t_ukt set UKT_APP_GURU = 2, UKT_APP_GURU_BY = '$USER_ID', UKT_APP_GURU_DATE = NOW() where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

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
        $UKT_ID = $_POST["id"];
    
        GetQuery("update t_ukt set DELETION_STATUS = 1 where UKT_ID = '$UKT_ID'");
        GetQuery("update t_ukt_detail set DELETION_STATUS = 1 where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'D', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>