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
        $TINGKATAN_ID_BARU = $_POST["TINGKATAN_ID"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        $PPD_JENIS = $_POST["PPD_JENIS"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];
        
        if ($PPD_JENIS == 0) {
            $PPD_JENIS_DESKRIPSI = "Kenaikan";
        } else {
            $PPD_JENIS_DESKRIPSI = "Ulang";
        }
        

        $getDataAnggota = GetQuery("SELECT a.ANGGOTA_NAMA,a.TINGKATAN_ID TINGKATAN_ID_LAMA 
        FROM m_anggota a
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE a.ANGGOTA_ID = '$ANGGOTA_ID' AND a.CABANG_KEY = '$CABANG_KEY' AND a.ANGGOTA_STATUS = 0");
        while ($rowAnggota = $getDataAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowAnggota);
        }

        GetQuery("INSERT INTO `t_ppd` (`PPD_ID`, `CABANG_KEY`, `ANGGOTA_ID`, `TINGKATAN_ID_LAMA`, `TINGKATAN_ID_BARU`, `PPD_JENIS`, `PPD_LOKASI`, `PPD_TANGGAL`, `PPD_DESKRIPSI`, `PPD_FILE`, `PPD_APPROVE_PELATIH`, `PPD_APPROVE_PELATIH_TGL`, `PPD_APPROVE_GURU`, `PPD_APPROVE_GURU_TGL`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES ('$PPD_ID', '$CABANG_KEY', '$ANGGOTA_ID', '$TINGKATAN_ID_LAMA', '$TINGKATAN_ID_BARU', '$PPD_JENIS', '$PPD_LOKASI', '$PPD_TANGGAL', '$PPD_DESKRIPSI', '', '0', NULL, '0', NULL, '0', '$USER_ID', '$localDateTime')");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'I', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifPPD'
            ELSE
            'ViewNotifPPD'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifPPD'
            ELSE
            'open-ViewNotifPPD'
        END AS TOGGLE,
        CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' else 'Ulang' END, t.TINGKATAN_NAMA),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.ANGGOTA_AKSES,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

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
        $TINGKATAN_ID_BARU = $_POST["TINGKATAN_ID"];
        $PPD_TANGGAL = $_POST["PPD_TANGGAL"];
        $CABANG_KEY = $USER_CABANG;
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        }
        $PPD_JENIS = $_POST["PPD_JENIS"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $PPD_LOKASI = $_POST["PPD_LOKASI"];
        $PPD_DESKRIPSI = $_POST["PPD_DESKRIPSI"];
        
        if ($PPD_JENIS == 0) {
            $PPD_JENIS_DESKRIPSI = "Kenaikan";
        } else {
            $PPD_JENIS_DESKRIPSI = "Ulang";
        }
        

        $getDataAnggota = GetQuery("SELECT a.ANGGOTA_NAMA,a.TINGKATAN_ID TINGKATAN_ID_LAMA 
        FROM m_anggota a
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE a.ANGGOTA_ID = '$ANGGOTA_ID' AND a.CABANG_KEY = '$CABANG_KEY' AND a.ANGGOTA_STATUS = 0");
        while ($rowAnggota = $getDataAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowAnggota);
        }

        GetQuery("update t_ppd set TINGKATAN_ID_LAMA = '$TINGKATAN_ID_LAMA', TINGKATAN_ID_BARU = '$TINGKATAN_ID_BARU', PPD_TANGGAL = '$PPD_TANGGAL', CABANG_KEY = '$CABANG_KEY', ANGGOTA_ID = '$ANGGOTA_ID', PPD_JENIS = '$PPD_JENIS', PPD_LOKASI = '$PPD_LOKASI', PPD_DESKRIPSI = '$PPD_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");

        // DELETE NOTIFIKASI BEFORE INSERT
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifPPDKoordinator'
            ELSE
            'ViewNotifPPD'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifPPDKoordinator'
            ELSE
            'open-ViewNotifPPD'
        END AS TOGGLE,
        CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan ' else 'Ulang ' END, t.TINGKATAN_NAMA),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.ANGGOTA_AKSES,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

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

        $incNum = autoIncCert("t_ppd","PPD_FILE_NAME",5);
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
        '/', DATE_FORMAT(p.PPD_TANGGAL, '%Y')) AS PPD_FILE_NAME,
        a.ANGGOTA_ID,
        a.ANGGOTA_NAMA,
        CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' WHEN p.PPD_JENIS = 1 THEN 'Ulang' END AS PPD_JENIS_DESKRIPSI,
        p.CABANG_KEY
        FROM t_ppd p
        LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
        WHERE p.PPD_ID = '$PPD_ID'");
        while ($rowSertifikat = $getSertifikat->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSertifikat);
        }

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 1, PPD_APPROVE_PELATIH_BY = '$USER_ID', PPD_APPROVE_PELATIH_TGL = '$localDateTime', PPD_FILE_NAME = '$PPD_FILE_NAME' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");
        
        // DELETE NOTIFIKASI BEFORE INSERT
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD','ViewNotifPPD','open-ViewNotifPPD',CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' else 'Ulang' END, ' ', t.TINGKATAN_NAMA,' - ', t.TINGKATAN_SEBUTAN),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),1,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

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
        
        $getDetailPPD = GetQuery("SELECT * FROM t_ppd WHERE PPD_ID = '$PPD_ID'");
        while ($rowPPD = $getDetailPPD->fetch(PDO::FETCH_ASSOC)) {
            extract($rowPPD);
        }

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 2, PPD_APPROVE_PELATIH_BY = '$USER_ID', PPD_APPROVE_PELATIH_TGL = '$localDateTime' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");

        // DELETE NOTIFIKASI BEFORE INSERT
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifPPDKoordinator'
            ELSE
            'ViewNotifPPD'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifPPDKoordinator'
            ELSE
            'open-ViewNotifPPD'
        END AS TOGGLE,
        CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan ' else 'Ulang ' END, t.TINGKATAN_NAMA),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),2,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.ANGGOTA_AKSES,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approveNotifPPDKoordinator"])) {
    try {
        $PPD_ID = $_POST["PPD_ID"];

        $incNum = autoIncCert("t_ppd","PPD_FILE_NAME",5);
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
        '/', DATE_FORMAT(p.PPD_TANGGAL, '%Y')) AS PPD_FILE_NAME,
        a.ANGGOTA_ID,
        a.ANGGOTA_NAMA,
        CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' WHEN p.PPD_JENIS = 1 THEN 'Ulang' END AS PPD_JENIS_DESKRIPSI,
        p.CABANG_KEY
        FROM t_ppd p
        LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
        WHERE p.PPD_ID = '$PPD_ID'");
        while ($rowSertifikat = $getSertifikat->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSertifikat);
        }

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 1, PPD_APPROVE_PELATIH_BY = '$USER_ID', PPD_APPROVE_PELATIH_TGL = '$localDateTime', PPD_FILE_NAME = '$PPD_FILE_NAME' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");
        
        // DELETE NOTIFIKASI BEFORE INSERT
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD','ViewNotifPPD','open-ViewNotifPPD',CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan' else 'Ulang' END, ' ', t.TINGKATAN_NAMA,' - ', t.TINGKATAN_SEBUTAN),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),1,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

        $response="Success,$PPD_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectNotifPPDKoordinator"])) {
    try {
        $PPD_ID = $_POST["PPD_ID"];
        
        $getDetailPPD = GetQuery("SELECT * FROM t_ppd WHERE PPD_ID = '$PPD_ID'");
        while ($rowPPD = $getDetailPPD->fetch(PDO::FETCH_ASSOC)) {
            extract($rowPPD);
        }

        GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 2, PPD_APPROVE_PELATIH_BY = '$USER_ID', PPD_APPROVE_PELATIH_TGL = '$localDateTime' where PPD_ID = '$PPD_ID'");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");

        // DELETE NOTIFIKASI BEFORE INSERT
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifPPDKoordinator'
            ELSE
            'ViewNotifPPD'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifPPDKoordinator'
            ELSE
            'open-ViewNotifPPD'
        END AS TOGGLE,
        CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan ' else 'Ulang ' END, t.TINGKATAN_NAMA),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),2,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.ANGGOTA_AKSES,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    p.PPD_ID = '$PPD_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE p.PPD_ID = '$PPD_ID'");

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

        GetQuery("update t_ppd set PPD_APPROVE_GURU = 1, PPD_APPROVE_GURU_BY = '$USER_ID', PPD_APPROVE_GURU_TGL = '$localDateTime' where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL' and DELETION_STATUS = 0 and PPD_APPROVE_PELATIH = 1 and PPD_APPROVE_GURU = 0");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL'");

        GetQuery("UPDATE m_anggota
                JOIN t_ppd 
                ON m_anggota.ANGGOTA_ID = t_ppd.ANGGOTA_ID 
                AND m_anggota.CABANG_KEY = t_ppd.CABANG_KEY 
                AND m_anggota.ANGGOTA_STATUS = 0
                SET m_anggota.TINGKATAN_ID = t_ppd.TINGKATAN_ID_BARU
                WHERE t_ppd.PPD_LOKASI = '$PPD_LOKASI' 
                AND t_ppd.PPD_TANGGAL = '$PPD_TANGGAL' 
                AND t_ppd.DELETION_STATUS = 0 
                AND t_ppd.PPD_APPROVE_PELATIH = 1 
                AND t_ppd.PPD_APPROVE_GURU = 1");

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

        GetQuery("update t_ppd set PPD_APPROVE_GURU = 2, PPD_APPROVE_GURU_BY = '$USER_ID', PPD_APPROVE_GURU_TGL = '$localDateTime' where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL' and DELETION_STATUS = 0 and PPD_APPROVE_PELATIH = 1 and PPD_APPROVE_GURU = 0");

        GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_LOKASI = '$PPD_LOKASI' and PPD_TANGGAL = '$PPD_TANGGAL'");

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
        $PPD_APPROVAL = $_POST["EVENT_ACTION"];

        $getDetailPPD = GetQuery("SELECT * FROM t_ppd WHERE PPD_ID = '$PPD_ID'");
        while ($rowPPD = $getDetailPPD->fetch(PDO::FETCH_ASSOC)) {
            extract($rowPPD);
        }

        if ($PPD_APPROVAL == "cancel") {
            
            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
            GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 0, PPD_APPROVE_PELATIH_BY = null, PPD_APPROVE_PELATIH_TGL= null, PPD_APPROVE_GURU = 0, PPD_APPROVE_GURU_BY = null, PPD_APPROVE_GURU_TGL= null where PPD_ID = '$PPD_ID'");

            GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");
          
            // DELETE NOTIFIKASI BEFORE INSERT
            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");

            // INSERT NOTIFIKASI
            GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$PPD_ID',p.CABANG_KEY,p.PPD_LOKASI,'PPD',
            CASE
                WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'ApproveNotifPPDKoordinator'
                ELSE
                'ViewNotifPPD'
            END AS HREF,
            CASE
                WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'open-ApproveNotifPPDKoordinator'
                ELSE
                'open-ViewNotifPPD'
            END AS TOGGLE,
            CONCAT('PPD ', CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan ' else 'Ulang ' END, t.TINGKATAN_NAMA),CONCAT(p.ANGGOTA_ID, ' - ', a.ANGGOTA_NAMA),0,0,'$USER_ID','$localDateTime'
            FROM m_anggota a
            LEFT JOIN t_ppd p ON a.ANGGOTA_ID = p.ANGGOTA_ID AND a.CABANG_KEY = p.CABANG_KEY
            LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID 
            LEFT JOIN 
                (
                    SELECT 
                        a.ANGGOTA_KEY,
                        a.ANGGOTA_AKSES,
                        a.CABANG_KEY
                    FROM 
                        m_anggota a
                    LEFT JOIN 
                        t_ppd p ON a.CABANG_KEY = p.CABANG_KEY
                    WHERE 
                        (a.ANGGOTA_AKSES = 'Administrator' OR 
                        a.CABANG_KEY IN (p.CABANG_KEY, p.PPD_LOKASI) AND 
                        (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                        a.CABANG_KEY = '$CABANG_KEY') OR 
                        a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                        a.ANGGOTA_STATUS = 0 AND 
                        p.PPD_ID = '$PPD_ID'
                ) n ON a.CABANG_KEY = n.CABANG_KEY
            WHERE p.PPD_ID = '$PPD_ID'");
        } else {
            
            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
        
            GetQuery("update t_ppd set PPD_APPROVE_PELATIH = 0, PPD_APPROVE_PELATIH_BY = NULL, PPD_APPROVE_PELATIH_TGL = NULL,DELETION_STATUS = 1 where PPD_ID = '$PPD_ID'");

            GetQuery("insert into t_ppd_log select uuid(), PPD_ID, CABANG_KEY, ANGGOTA_ID, TINGKATAN_ID_LAMA, TINGKATAN_ID_BARU, PPD_JENIS, PPD_LOKASI, PPD_TANGGAL, PPD_DESKRIPSI, PPD_FILE, PPD_FILE_NAME, PPD_APPROVE_PELATIH, PPD_APPROVE_PELATIH_TGL, PPD_APPROVE_GURU, PPD_APPROVE_GURU_TGL, DELETION_STATUS, 'D', '$USER_ID', '$localDateTime' from t_ppd where PPD_ID = '$PPD_ID'");

            // DELETE NOTIFIKASI BEFORE INSERT
            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$PPD_ID'");
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