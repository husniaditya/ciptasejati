<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["savekasanggota"])) {
    try {
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        } else {
            $CABANG_KEY = $USER_CABANG;
        }
        
        $KAS_ID = createKode("t_kas","KAS_ID","KAS-$YEAR$MONTH-",3);
        $KAS_JENIS = $_POST["KAS_JENIS"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $KAS_DK = $_POST["KAS_DK"];
        $rawKAS_JUMLAH = $_POST["KAS_JUMLAH"];
        $KAS_DESKRIPSI = $_POST["KAS_DESKRIPSI"];
        $KAS_FILE = "";

        $getSaldoAwal = GetQuery("SELECT 
        CASE
            WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', SUM(KAS_JUMLAH), ')')
            ELSE SUM(KAS_JUMLAH)
        END AS SALDO_AWAL
        FROM 
            t_kas
        WHERE 
            DELETION_STATUS = 0 
            AND ANGGOTA_KEY = '$ANGGOTA_KEY' 
            AND KAS_ID < '$KAS_ID' 
            AND KAS_JENIS = '$KAS_JENIS'");
        while ($rowSaldoAwal = $getSaldoAwal->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSaldoAwal);
        }
        
        // Remove commas from the input
        $cleanedKAS_JUMLAH = str_replace(',', '', $rawKAS_JUMLAH);
        // Convert the cleaned value to an integer or float as needed
        $KAS_JUMLAH = ($KAS_DK == "D") ? (float)$cleanedKAS_JUMLAH : -(float)$cleanedKAS_JUMLAH;
        $KAS_SALDO = ($KAS_DK == "D") ? (float)$SALDO_AWAL + (float)$cleanedKAS_JUMLAH : (float)$SALDO_AWAL -(float)$cleanedKAS_JUMLAH;
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA, a.CABANG_KEY, c.CABANG_DESKRIPSI from m_anggota a left join m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("insert into t_kas select '$KAS_ID','$CABANG_KEY','$ANGGOTA_KEY','$ANGGOTA_ID', '$KAS_JENIS', '$localDateTime', '$KAS_DK','$KAS_JUMLAH', '$KAS_SALDO','$KAS_DESKRIPSI', null, 0, '$USER_ID', '$localDateTime'");

        GetQuery("insert into t_kas_log select uuid(), KAS_ID, CABANG_KEY, ANGGOTA_KEY, KAS_JENIS, KAS_TANGGAL, KAS_DK,KAS_JUMLAH, KAS_SALDO, KAS_DESKRIPSI, KAS_FILE, DELETION_STATUS, 'I', '$USER_ID', '$localDateTime' from t_kas where KAS_ID = '$KAS_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$KAS_ID',k.CABANG_KEY,k.CABANG_KEY,'Kas','ViewNotifKas','open-ViewNotifKas','Kas $KAS_JENIS',CONCAT('[$KAS_DK] Kas a.n ',a.ANGGOTA_NAMA,' dengan jumlah Rp $rawKAS_JUMLAH'),0,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_kas k ON a.ANGGOTA_ID = k.ANGGOTA_ID AND a.CABANG_KEY = k.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_kas k ON a.CABANG_KEY = k.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (k.CABANG_KEY) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    k.KAS_ID = '$KAS_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE k.KAS_ID = '$KAS_ID'");

        $response="Success,$KAS_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["editkasanggota"])) {
    try {
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        } else {
            $CABANG_KEY = $USER_CABANG;
        }
        
        $KAS_ID = $_POST["KAS_ID"];
        $KAS_JENIS = $_POST["KAS_JENIS"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $KAS_DK = $_POST["KAS_DK"];
        $rawKAS_JUMLAH = $_POST["KAS_JUMLAH"];
        $KAS_DESKRIPSI = $_POST["KAS_DESKRIPSI"];
        $KAS_FILE = "";

        $getSaldoAwal = GetQuery("SELECT 
        CASE
            WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', SUM(KAS_JUMLAH), ')')
            ELSE SUM(KAS_JUMLAH)
        END AS SALDO_AWAL
        FROM 
            t_kas
        WHERE 
            DELETION_STATUS = 0 
            AND ANGGOTA_KEY = '$ANGGOTA_KEY' 
            AND KAS_ID < '$KAS_ID' 
            AND KAS_JENIS = '$KAS_JENIS'");
        while ($rowSaldoAwal = $getSaldoAwal->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSaldoAwal);
        }
        
        // Remove commas from the input
        $cleanedKAS_JUMLAH = str_replace(',', '', $rawKAS_JUMLAH);
        // Convert the cleaned value to an integer or float as needed
        $KAS_JUMLAH = ($KAS_DK == "D") ? (float)$cleanedKAS_JUMLAH : -(float)$cleanedKAS_JUMLAH;
        $KAS_SALDO = ($KAS_DK == "D") ? (float)$SALDO_AWAL + (float)$cleanedKAS_JUMLAH : (float)$SALDO_AWAL -(float)$cleanedKAS_JUMLAH;
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA, a.CABANG_KEY, c.CABANG_DESKRIPSI from m_anggota a left join m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("update t_kas set CABANG_KEY = '$CABANG_KEY', ANGGOTA_KEY = '$ANGGOTA_KEY', ANGGOTA_ID = '$ANGGOTA_ID', KAS_DK = '$KAS_DK', KAS_JUMLAH = '$KAS_JUMLAH', KAS_SALDO = '$KAS_SALDO', KAS_DESKRIPSI = '$KAS_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = '$localDateTime' where KAS_ID = '$KAS_ID'");

        GetQuery("insert into t_kas_log select uuid(), KAS_ID, CABANG_KEY, ANGGOTA_KEY, KAS_JENIS, KAS_TANGGAL, KAS_DK,KAS_JUMLAH, KAS_SALDO, KAS_DESKRIPSI, KAS_FILE, DELETION_STATUS, 'U', '$USER_ID', '$localDateTime' from t_kas where KAS_ID = '$KAS_ID'");

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$KAS_ID'");

        // INSERT NOTIFIKASI
        GetQuery("INSERT into t_notifikasi SELECT UUID(),n.ANGGOTA_KEY,'$KAS_ID',k.CABANG_KEY,k.CABANG_KEY,'Kas','ViewNotifKas','open-ViewNotifKas','Kas $KAS_JENIS',CONCAT('[$KAS_DK] Kas a.n ',a.ANGGOTA_NAMA,' dengan jumlah Rp $rawKAS_JUMLAH'),0,0,'$USER_ID','$localDateTime'
        FROM m_anggota a
        LEFT JOIN t_kas k ON a.ANGGOTA_ID = k.ANGGOTA_ID AND a.CABANG_KEY = k.CABANG_KEY
        LEFT JOIN 
            (
                SELECT 
                    a.ANGGOTA_KEY,
                    a.CABANG_KEY
                FROM 
                    m_anggota a
                LEFT JOIN 
                    t_kas k ON a.CABANG_KEY = k.CABANG_KEY
                WHERE 
                    (a.ANGGOTA_AKSES = 'Administrator' OR 
                    a.CABANG_KEY IN (k.CABANG_KEY) AND 
                    (a.ANGGOTA_AKSES IN ('Koordinator', 'Pengurus') AND 
                    a.CABANG_KEY = '$CABANG_KEY') OR 
                    a.ANGGOTA_ID = '$ANGGOTA_ID') AND 
                    a.ANGGOTA_STATUS = 0 AND 
                    k.KAS_ID = '$KAS_ID'
            ) n ON a.CABANG_KEY = n.CABANG_KEY
        WHERE k.KAS_ID = '$KAS_ID'");

        $response="Success,$KAS_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) {

    try {
        $KAS_ID = $_POST["KAS_ID"];

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$KAS_ID'");
    
        GetQuery("update t_kas set DELETION_STATUS = 1 where KAS_ID = '$KAS_ID'");

        GetQuery("insert into t_kas_log select uuid(), KAS_ID, CABANG_KEY, ANGGOTA_KEY, KAS_JENIS, KAS_TANGGAL, KAS_DK,KAS_JUMLAH, KAS_SALDO, KAS_DESKRIPSI, KAS_FILE, DELETION_STATUS, 'I', '$USER_ID', '$localDateTime' from t_kas where KAS_ID = '$KAS_ID'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>