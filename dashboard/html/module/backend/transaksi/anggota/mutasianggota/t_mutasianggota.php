<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");


if (isset($_POST["savemutasianggota"])) {

    try {
        $MUTASI_ID = createKode("t_mutasi","MUTASI_ID","MTS-$YEAR$MONTH-",3);
        $CABANG_TUJUAN = $_POST["CABANG_TUJUAN"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $MUTASI_DESKRIPSI = $_POST["MUTASI_DESKRIPSI"];
        $MUTASI_TANGGAL = $_POST["MUTASI_TANGGAL"];
        $MUTASI_FILE = "";
        if ($USER_AKSES == "Administrator") {
            $CABANG_AWAL = $_POST["CABANG_AWAL"];
        } else {
            $CABANG_AWAL = $USER_CABANG;
        }
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI from m_anggota a LEFT JOIN m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("insert into t_mutasi select UUID(),'$MUTASI_ID', '$CABANG_AWAL', '$CABANG_TUJUAN', '$ANGGOTA_KEY', '$ANGGOTA_ID', '$MUTASI_DESKRIPSI', '$MUTASI_TANGGAL', 0, now(), null, null, '$MUTASI_FILE',0,0, '$USER_ID', now()");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'I', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$MUTASI_ID',m.CABANG_AWAL,m.CABANG_TUJUAN,'Mutasi',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifMutasi'
            ELSE
            'ViewNotifMutasi'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifMutasi'
            ELSE
            'open-ViewNotifMutasi'
        END AS TOGGLE,
        'Persetujuan Mutasi Anggota',CONCAT('Mutasi a.n ',a.ANGGOTA_NAMA,' dari cabang ',c.CABANG_DESKRIPSI),0,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_mutasi m ON a.ANGGOTA_ID = m.ANGGOTA_ID AND a.CABANG_KEY = m.CABANG_AWAL
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_mutasi m ON a.CABANG_KEY = m.CABANG_AWAL
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (m.CABANG_AWAL,m.CABANG_TUJUAN) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_AWAL') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    m.MUTASI_ID = '$MUTASI_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE m.MUTASI_ID = '$MUTASI_ID'");

        $response="Success,$MUTASI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}


if (isset($_POST["editmutasianggota"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];
        $CABANG_TUJUAN = $_POST["CABANG_TUJUAN"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $MUTASI_DESKRIPSI = $_POST["MUTASI_DESKRIPSI"];
        $MUTASI_TANGGAL = $_POST["MUTASI_TANGGAL"];
        if ($USER_AKSES <> "Administrator") {
            $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        }
        $MUTASI_FILE = "";
        if ($USER_AKSES == "Administrator") {
            $CABANG_AWAL = $_POST["CABANG_AWAL"];
        } else {
            $CABANG_AWAL = $USER_CABANG;
        }
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI from m_anggota a LEFT JOIN m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("update t_mutasi set CABANG_AWAL = '$CABANG_AWAL', CABANG_TUJUAN = '$CABANG_TUJUAN', ANGGOTA_KEY = '$ANGGOTA_KEY', ANGGOTA_ID = '$ANGGOTA_ID', MUTASI_DESKRIPSI = '$MUTASI_DESKRIPSI', MUTASI_TANGGAL = '$MUTASI_TANGGAL', MUTASI_STATUS = 0, MUTASI_STATUS_TANGGAL = now(), MUTASI_APPROVE_BY = null, MUTASI_APPROVE_TANGGAL = null, MUTASI_FILE = '$MUTASI_FILE', DELETION_STATUS = 0, INPUT_BY = '$USER_ID', INPUT_DATE = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");
        
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$MUTASI_ID',m.CABANG_AWAL,m.CABANG_TUJUAN,'Mutasi',
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'ApproveNotifMutasi'
            ELSE
            'ViewNotifMutasi'
        END AS HREF,
        CASE
            WHEN n.ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
            'open-ApproveNotifMutasi'
            ELSE
            'open-ViewNotifMutasi'
        END AS TOGGLE,
        'Persetujuan Mutasi Anggota',CONCAT('Mutasi a.n ',a.ANGGOTA_NAMA,' dari cabang ',c.CABANG_DESKRIPSI),0,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_mutasi m ON a.ANGGOTA_ID = m.ANGGOTA_ID AND a.CABANG_KEY = m.CABANG_AWAL
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_mutasi m ON a.CABANG_KEY = m.CABANG_AWAL
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (m.CABANG_AWAL,m.CABANG_TUJUAN) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_AWAL') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    m.MUTASI_ID = '$MUTASI_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE m.MUTASI_ID = '$MUTASI_ID'");

        $response="Success,$MUTASI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approvemutasianggota"]) || isset($_POST["notifapprovemutasianggota"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];

        GetQuery("update t_mutasi set MUTASI_STATUS = 1, MUTASI_APPROVE_BY = '$USER_ID', MUTASI_APPROVE_TANGGAL = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        $getDataMutasi =  GetQuery("SELECT m.*,a.ANGGOTA_ID,m.CABANG_AWAL,m.CABANG_TUJUAN,m.ANGGOTA_KEY,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI CABANG_AWAL_DES,d.DAERAH_DESKRIPSI DAERAH_AWAL,c2.CABANG_DESKRIPSI CABANG_TUJUAN_DES,d2.DAERAH_DESKRIPSI DAERAH_TUJUAN,DATE_FORMAT(m.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF
        FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON m.CABANG_TUJUAN = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }
    
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");
     
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$MUTASI_ID',m.CABANG_AWAL,m.CABANG_TUJUAN,'Mutasi','ViewNotifMutasi','open-ViewNotifMutasi','Persetujuan Mutasi Anggota',CONCAT('Mutasi a.n ',a.ANGGOTA_NAMA,' dari cabang ',c.CABANG_DESKRIPSI),1,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_mutasi m ON a.ANGGOTA_ID = m.ANGGOTA_ID AND a.CABANG_KEY = m.CABANG_AWAL
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_mutasi m ON a.CABANG_KEY = m.CABANG_AWAL
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (m.CABANG_AWAL,m.CABANG_TUJUAN) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_AWAL') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    m.MUTASI_ID = '$MUTASI_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE m.MUTASI_ID = '$MUTASI_ID'");

        $response="Success,$MUTASI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectmutasianggota"]) || isset($_POST["notifrejectmutasianggota"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];

        GetQuery("update t_mutasi set MUTASI_STATUS = 2, MUTASI_APPROVE_BY = '$USER_ID', MUTASI_APPROVE_TANGGAL = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        $getDataMutasi =  GetQuery("SELECT m.*,a.ANGGOTA_ID,m.CABANG_AWAL,m.CABANG_TUJUAN,m.ANGGOTA_KEY,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI CABANG_AWAL_DES,d.DAERAH_DESKRIPSI DAERAH_AWAL,c2.CABANG_DESKRIPSI CABANG_TUJUAN_DES,d2.DAERAH_DESKRIPSI DAERAH_TUJUAN,DATE_FORMAT(m.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF
        FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON m.CABANG_TUJUAN = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }
    
        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");
     
        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$MUTASI_ID',m.CABANG_AWAL,m.CABANG_TUJUAN,'Mutasi','ViewNotifMutasi','open-ViewNotifMutasi','Persetujuan Mutasi Anggota',CONCAT('Mutasi a.n ',a.ANGGOTA_NAMA,' dari cabang ',c.CABANG_DESKRIPSI),2,0,'$USER_ID',NOW()
        FROM m_anggota a
        LEFT JOIN t_mutasi m ON a.ANGGOTA_ID = m.ANGGOTA_ID AND a.CABANG_KEY = m.CABANG_AWAL
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY,
                    a.ANGGOTA_AKSES
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_mutasi m ON a.CABANG_KEY = m.CABANG_AWAL
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (m.CABANG_AWAL,m.CABANG_TUJUAN) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_AWAL') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    m.MUTASI_ID = '$MUTASI_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE m.MUTASI_ID = '$MUTASI_ID'");

        $response="Success,$MUTASI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) {

    try {
        $EVENT_ACTION = $_POST["EVENT_ACTION"];
        $MUTASI_ID = $_POST["MUTASI_ID"];

        $getDataMutasi =  GetQuery("SELECT m.CABANG_AWAL,m.CABANG_TUJUAN,m.ANGGOTA_KEY,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }

        if ($EVENT_ACTION == "reset") {
            GetQuery("update t_mutasi set MUTASI_STATUS = 0, MUTASI_APPROVE_BY = null, MUTASI_APPROVE_TANGGAL = null where MUTASI_ID = '$MUTASI_ID'");

            GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");

            GetQuery("insert into t_notifikasi
            select uuid(),ANGGOTA_KEY,'$MUTASI_ID','$CABANG_AWAL','$CABANG_TUJUAN','Mutasi',
            CASE
                WHEN ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'ApproveNotifMutasi'
                ELSE
                'ViewNotifMutasi'
            END AS HREF,
            CASE
                WHEN ANGGOTA_AKSES IN ('Administrator','Koordinator') THEN
                'open-ApproveNotifMutasi'
                ELSE
                'open-ViewNotifMutasi'
            END AS TOGGLE,
            'Persetujuan Mutasi Anggota','Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', 0, 0, '$USER_ID', NOW()
            FROM m_anggota
            WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$CABANG_AWAL','$CABANG_TUJUAN') AND ANGGOTA_AKSES = 'Koordinator' OR ANGGOTA_KEY = '$ANGGOTA_KEY') AND ANGGOTA_STATUS = 0");

        } else {
            GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'D', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");
    
            GetQuery("update t_mutasi set DELETION_STATUS = 1 where MUTASI_ID = '$MUTASI_ID'");
            GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");
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