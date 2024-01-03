<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["savekasanggota"])) {
    try {
        $KAS_ID = createKode("t_kas","KAS_ID","KAS-$YEAR$MONTH-",3);
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $KAS_DK = $_POST["KAS_DK"];
        $rawKAS_JUMLAH = $_POST["KAS_JUMLAH"];
        $KAS_DESKRIPSI = $_POST["KAS_DESKRIPSI"];
        $KAS_FILE = "";
        
        // Remove commas from the input
        $cleanedKAS_JUMLAH = str_replace(',', '', $rawKAS_JUMLAH);
        // Convert the cleaned value to an integer or float as needed
        $KAS_JUMLAH = ($KAS_DK == "D") ? (float)$cleanedKAS_JUMLAH : -(float)$cleanedKAS_JUMLAH;
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA, c.CABANG_DESKRIPSI from m_anggota a left join m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("insert into t_kas select '$KAS_ID','$ANGGOTA_KEY', now(), '$KAS_DK','$KAS_JUMLAH','$KAS_DESKRIPSI', null, 0, '$USER_ID', now()");

        GetQuery("insert into t_kas_log select uuid(),'$KAS_ID','$ANGGOTA_KEY', now(), '$KAS_DK','$KAS_JUMLAH','$KAS_DESKRIPSI',null, 0, 'I', '$USER_ID', now()");

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$KAS_ID','$USER_CABANG','$USER_CABANG','Kas','ViewNotifKas','open-ViewNotifKas','Kas Anggota', '[$KAS_DK] Kas a.n $ANGGOTA_NAMA dengan jumlah Rp $rawKAS_JUMLAH', 1, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$USER_CABANG','$USER_CABANG') AND ANGGOTA_AKSES = 'Koordinator' OR ANGGOTA_KEY = '$ANGGOTA_KEY') AND ANGGOTA_STATUS = 0");

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
        $KAS_ID = $_POST["KAS_ID"];
        $ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
        $KAS_DK = $_POST["KAS_DK"];
        $rawKAS_JUMLAH = $_POST["KAS_JUMLAH"];
        $KAS_DESKRIPSI = $_POST["KAS_DESKRIPSI"];
        $KAS_FILE = "";
        
        // Remove commas from the input
        $cleanedKAS_JUMLAH = str_replace(',', '', $rawKAS_JUMLAH);
        // Convert the cleaned value to an integer or float as needed
        $KAS_JUMLAH = ($KAS_DK == "D") ? (float)$cleanedKAS_JUMLAH : -(float)$cleanedKAS_JUMLAH;
        
        $getNamaAnggota = GetQuery("select a.ANGGOTA_NAMA, c.CABANG_DESKRIPSI from m_anggota a left join m_cabang c on a.CABANG_KEY = c.CABANG_KEY where a.ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        $test = GetQuery("update t_kas set ANGGOTA_KEY = '$ANGGOTA_KEY', KAS_DK = '$KAS_DK', KAS_JUMLAH = '$KAS_JUMLAH', KAS_DESKRIPSI = '$KAS_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = now() where KAS_ID = '$KAS_ID'");

        GetQuery("insert into t_kas_log select uuid(),'$KAS_ID','$ANGGOTA_KEY', now(), '$KAS_DK','$KAS_JUMLAH','$KAS_DESKRIPSI',null, 0, 'U', '$USER_ID', now()");

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$KAS_ID'");

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$KAS_ID','$USER_CABANG','$USER_CABANG','Kas','ViewNotifKas','open-ViewNotifKas','Kas Anggota', '[$KAS_DK] Kas a.n $ANGGOTA_NAMA dengan jumlah Rp $rawKAS_JUMLAH', 1, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$USER_CABANG','$USER_CABANG') AND ANGGOTA_AKSES = 'Koordinator' OR ANGGOTA_KEY = '$ANGGOTA_KEY') AND ANGGOTA_STATUS = 0");

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

        GetQuery("insert into t_kas_log select uuid(),KAS_ID,ANGGOTA_KEY, KAS_TANGGAL, KAS_DK, KAS_JUMLAH, KAS_DESKRIPSI,KAS_FILE, DELETION_STATUS, 'D', '$USER_ID', now() from t_kas where KAS_ID = '$KAS_ID'");
        
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>