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
        // Prepare the query
        $query = "INSERT INTO t_ukt SELECT ?, ?, ?, ?, ?, ?, ?, ?, ?, null, null, 0, null, null, 0, null, null, 0, '$USER_ID', now()";

        // Execute the query with parameters
        GetQuery2($query, [$UKT_ID, $CABANG_KEY, $TINGKATAN_ID, $ANGGOTA_ID, $UKT_LOKASI, $UKT_TANGGAL, $UKT_TOTAL, $UKT_NILAI, $UKT_DESKRIPSI]);

        // UPDATE DATA PENGUJI
        GetQuery("UPDATE t_ukt_penguji set UKT_ID = '$UKT_ID' WHERE UKT_ID = 'Temp_$USER_ID'");

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
            // Prepare the query
            $query = "INSERT INTO t_ukt_detail SELECT ?, ?, ?, ?, ?, ?,0";
    
            // Execute the query with parameters
            GetQuery2($query, [$UKT_ID, $materivalue, $key, $bobotvalue, $nilaiValue, $remarkValue]);
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
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'I', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
       
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$UKT_ID',u.CABANG_KEY,u.UKT_LOKASI,'UKT',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifUKTKoordinator'
            ELSE
            'ViewNotifUKT'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifUKTKoordinator'
            ELSE
            'open-ViewNotifUKT'
        END AS TOGGLE,
        CONCAT('UKT ', t.TINGKATAN_NAMA, ' - ', t.TINGKATAN_SEBUTAN),CONCAT(u.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_ukt u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.CABANG_KEY = u.CABANG_KEY
        LEFT JOIN m_tingkatan t ON u.TINGKATAN_ID = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ukt u ON a.CABANG_KEY = u.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (u.CABANG_KEY, u.UKT_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    u.UKT_ID = '$UKT_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE u.UKT_ID = '$UKT_ID'");

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
        // Prepare the query
        $query = "UPDATE t_ukt SET TINGKATAN_ID = ?, ANGGOTA_ID = ?, UKT_LOKASI = ?, UKT_TANGGAL = ?, UKT_TOTAL = ?, UKT_NILAI = ?, UKT_DESKRIPSI = ?, CABANG_KEY = ?, INPUT_BY = ?, INPUT_DATE = now() WHERE UKT_ID = ?";

        // Execute the query with parameters
        GetQuery2($query, [$TINGKATAN_ID, $ANGGOTA_ID, $UKT_LOKASI, $UKT_TANGGAL, $UKT_TOTAL, $UKT_NILAI, $UKT_DESKRIPSI, $CABANG_KEY, $USER_ID, $UKT_ID]);
        
        // Delete all detail
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
            // Prepare the query
            $query = "INSERT INTO t_ukt_detail SELECT ?, ?, ?, ?, ?, ?,0";
    
            // Execute the query with parameters
            GetQuery2($query, [$UKT_ID, $materivalue, $key, $bobotvalue, $nilaiValue, $remarkValue]);
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
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        // DELETE NOTIFIKASI BEFORE RE-INSERT
        GetQuery("DELETE FROM t_notifikasi WHERE DOKUMEN_ID = '$UKT_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$UKT_ID',u.CABANG_KEY,u.UKT_LOKASI,'UKT',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifUKTKoordinator'
            ELSE
            'ViewNotifUKT'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifUKTKoordinator'
            ELSE
            'open-ViewNotifUKT'
        END AS TOGGLE,
        CONCAT('UKT ', t.TINGKATAN_NAMA, ' - ', t.TINGKATAN_SEBUTAN),CONCAT(u.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_ukt u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.CABANG_KEY = u.CABANG_KEY
        LEFT JOIN m_tingkatan t ON u.TINGKATAN_ID = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ukt u ON a.CABANG_KEY = u.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (u.CABANG_KEY, u.UKT_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    u.UKT_ID = '$UKT_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE u.UKT_ID = '$UKT_ID'");

        $response="Success,$UKT_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["ADD_MODAL_PENGUJI"])) { // Add a new detail Penguji ADD MODAL

    try {
        $UKT_ID = $USER_ID;
        $ANGGOTA_ID = $_POST["anggota"];

        // Prepare the query
        $query = "INSERT INTO t_ukt_penguji SELECT UUID(), ?, ?, '0'";

        // Execute the query with parameters
        GetQuery2($query, ['Temp_' . $UKT_ID, $ANGGOTA_ID]);

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["EDIT_MODAL_PENGUJI"])) { // Add a new detail Penguji ADD MODAL

    try {
        $UKT_ID = $_POST["id"];
        $ANGGOTA_ID = $_POST["anggota"];

        // Prepare the query
        $query = "INSERT INTO t_ukt_penguji SELECT UUID(), ?, ?, '0'";

        // Execute the query with parameters
        GetQuery2($query, [$UKT_ID, $ANGGOTA_ID]);

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["HIDE_ADD_MODAL"])) { // Delete Penguji HIDE ADD MODAL

    try {
        $UKT_ID = $USER_ID;
        
        GetQuery("DELETE FROM t_ukt_penguji WHERE UKT_ID = 'Temp_$USER_ID'");

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["DELETE_MODAL_PENGUJI"])) { // Delete Penguji MODAL

    try {
        $_key = $_POST["id"];
        
        GetQuery("DELETE FROM t_ukt_penguji WHERE _key = '$_key'");

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveKoordinator"])) {
    try {
        $UKT_ID = $_POST["UKT_ID"];

        $getDetailUKT = GetQuery("SELECT * FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
        while ($rowUKT = $getDetailUKT->fetch(PDO::FETCH_ASSOC)) {
            extract($rowUKT);
        }

        // UPDATE APPROVAL KOORDINATOR STATUS
        GetQuery("update t_ukt set UKT_APP_KOOR = 1, UKT_APP_KOOR_BY = '$USER_ID', UKT_APP_KOOR_DATE = NOW() where UKT_ID = '$UKT_ID'");

        // INSERT LOG
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$UKT_ID',u.CABANG_KEY,u.UKT_LOKASI,'UKT','ViewNotifUKT','open-ViewNotifUKT',CONCAT('UKT ', t.TINGKATAN_NAMA, ' - ', t.TINGKATAN_SEBUTAN),CONCAT(u.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),1,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_ukt u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.CABANG_KEY = u.CABANG_KEY
        LEFT JOIN m_tingkatan t ON u.TINGKATAN_ID = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ukt u ON a.CABANG_KEY = u.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (u.CABANG_KEY, u.UKT_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    u.UKT_ID = '$UKT_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE u.UKT_ID = '$UKT_ID'");

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
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$UKT_ID',u.CABANG_KEY,u.UKT_LOKASI,'UKT','ViewNotifUKT','open-ViewNotifUKT',CONCAT('UKT ', t.TINGKATAN_NAMA, ' - ', t.TINGKATAN_SEBUTAN),CONCAT(u.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),2,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_ukt u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.CABANG_KEY = u.CABANG_KEY
        LEFT JOIN m_tingkatan t ON u.TINGKATAN_ID = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ukt u ON a.CABANG_KEY = u.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (u.CABANG_KEY, u.UKT_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    u.UKT_ID = '$UKT_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE u.UKT_ID = '$UKT_ID'");

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
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

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
        GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

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
        $UKT_APPROVAL = $_POST["EVENT_ACTION"];

        $getDetailUKT = GetQuery("SELECT * FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
        while ($rowUKT = $getDetailUKT->fetch(PDO::FETCH_ASSOC)) {
            extract($rowUKT);
        }

        if ($UKT_APPROVAL == "cancel") {
            GetQuery("update t_ukt set UKT_APP_KOOR = 0, UKT_APP_KOOR_BY = null, UKT_APP_KOOR_DATE = null, UKT_APP_GURU = 0, UKT_APP_GURU_BY = null, UKT_APP_GURU_DATE = null where UKT_ID = '$UKT_ID'");
    
            // INSERT LOG
            GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'U', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");

            // DELETE NOTIFIKASI BEFORE RE-INSERT
            GetQuery("DELETE FROM t_notifikasi WHERE DOKUMEN_ID = '$UKT_ID'");
            
            // INSERT NOTIFIKASI
            GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$UKT_ID',u.CABANG_KEY,u.UKT_LOKASI,'UKT',
            CASE
                WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'ApproveNotifUKTKoordinator'
                ELSE
                'ViewNotifUKT'
            END AS HREF,
            CASE
                WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'open-ApproveNotifUKTKoordinator'
                ELSE
                'open-ViewNotifUKT'
            END AS TOGGLE,
            CONCAT('UKT ', t.TINGKATAN_NAMA, ' - ', t.TINGKATAN_SEBUTAN),CONCAT(u.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID',NOW()
            FROM m_anggota a
            LEFT JOIN t_ukt u ON a.ANGGOTA_ID = u.ANGGOTA_ID AND a.CABANG_KEY = u.CABANG_KEY
            LEFT JOIN m_tingkatan t ON u.TINGKATAN_ID = t.TINGKATAN_ID 
            LEFT JOIN 
                (
                    SELECT 
                        a.ANGGOTA_KEY,
                        a.CABANG_KEY,
                        a.ANGGOTA_AKSES
                    FROM 
                        m_anggota a
                    LEFT JOIN 
                        t_ukt u ON a.CABANG_KEY = u.CABANG_KEY
                    WHERE 
                        (a.ANGGOTA_AKSES = 'Administrator' OR 
                        a.CABANG_KEY IN (u.CABANG_KEY, u.UKT_LOKASI) AND 
                        (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                        a.CABANG_KEY = '$CABANG_KEY') OR 
                        a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                        a.ANGGOTA_STATUS = 0 AND 
                        u.UKT_ID = '$UKT_ID'
                ) n ON a.CABANG_KEY = n.CABANG_KEY
            WHERE u.UKT_ID = '$UKT_ID'");
        } else {
            GetQuery("update t_ukt set UKT_APP_KOOR = 0, UKT_APP_KOOR_BY = null, UKT_APP_KOOR_DATE = null, UKT_APP_GURU = 0, UKT_APP_GURU_BY = null, UKT_APP_GURU_DATE = null, DELETION_STATUS = 1 where UKT_ID = '$UKT_ID'");
            GetQuery("update t_ukt_detail set DELETION_STATUS = 1 where UKT_ID = '$UKT_ID'");
    
            // INSERT LOG
            GetQuery("INSERT INTO t_ukt_log SELECT uuid(), UKT_ID, CABANG_KEY, TINGKATAN_ID, ANGGOTA_ID, UKT_LOKASI, UKT_TANGGAL, UKT_TOTAL, UKT_NILAI, UKT_DESKRIPSI, UKT_FILE, UKT_FILE_NAME, UKT_APP_KOOR, UKT_APP_KOOR_BY, UKT_APP_KOOR_DATE, UKT_APP_GURU, UKT_APP_GURU_BY, UKT_APP_GURU_DATE, DELETION_STATUS, 'D', '$USER_ID', now() FROM t_ukt WHERE UKT_ID = '$UKT_ID'");
        }
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>