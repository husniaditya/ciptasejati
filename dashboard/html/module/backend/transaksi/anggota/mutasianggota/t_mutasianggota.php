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
        $MUTASI_DESKRIPSI = $_POST["MUTASI_DESKRIPSI"];
        $MUTASI_TANGGAL = $_POST["MUTASI_TANGGAL"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $MUTASI_FILE = "";
        if ($USER_AKSES == "Administrator") {
            $CABANG_AWAL = $_POST["CABANG_AWAL"];
        } else {
            $CABANG_AWAL = $USER_CABANG;
        }
        
        $getNamaAnggota = GetQuery("select ANGGOTA_NAMA from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("insert into t_mutasi select '$MUTASI_ID', '$CABANG_AWAL', '$CABANG_TUJUAN', '$ANGGOTA_KEY', '$MUTASI_DESKRIPSI', '$MUTASI_TANGGAL', 0, now(), null, null, '$MUTASI_FILE',0, '$USER_ID', now()");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'I', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$MUTASI_ID','$CABANG_AWAL','$CABANG_TUJUAN','Mutasi','Mutasi Anggota Approval','Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', 0, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$CABANG_AWAL','') AND ANGGOTA_AKSES = 'Koordinator') AND ANGGOTA_STATUS = 0");

        $response="Success";
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
        $MUTASI_DESKRIPSI = $_POST["MUTASI_DESKRIPSI"];
        $MUTASI_TANGGAL = $_POST["MUTASI_TANGGAL"];
        $CABANG_DESKRIPSI = $_POST["CABANG_DESKRIPSI"];
        $MUTASI_FILE = "";
        if ($USER_AKSES == "Administrator") {
            $CABANG_AWAL = $_POST["CABANG_AWAL"];
        } else {
            $CABANG_AWAL = $USER_CABANG;
        }
        
        $getNamaAnggota = GetQuery("select ANGGOTA_NAMA from m_anggota where ANGGOTA_KEY = '$ANGGOTA_KEY'");
        while ($rowNamaAnggota = $getNamaAnggota->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaAnggota);
        }

        GetQuery("update t_mutasi set CABANG_AWAL = '$CABANG_AWAL', CABANG_TUJUAN = '$CABANG_TUJUAN', ANGGOTA_KEY = '$ANGGOTA_KEY', MUTASI_DESKRIPSI = '$MUTASI_DESKRIPSI', MUTASI_TANGGAL = '$MUTASI_TANGGAL', MUTASI_STATUS = 0, MUTASI_STATUS_TANGGAL = now(), MUTASI_APPROVE_BY = null, MUTASI_APPROVE_TANGGAL = null, MUTASI_FILE = '$MUTASI_FILE', DELETION_STATUS = 0, INPUT_BY = '$USER_ID', INPUT_DATE = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("update t_notifikasi set CABANG_AWAL = '$CABANG_AWAL', CABANG_TUJUAN = '$CABANG_TUJUAN', BODY = 'Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', INPUT_BY = '$USER_ID', INPUT_DATE = now() where DOKUMEN_ID = '$MUTASI_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["approvemutasianggota"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];

        GetQuery("update t_mutasi set MUTASI_STATUS = 1, MUTASI_APPROVE_BY = '$USER_ID', MUTASI_APPROVE_TANGGAL = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("update t_notifikasi set READ_STATUS = 1 where DOKUMEN_ID = '$MUTASI_ID'");

        $getDataMutasi =  GetQuery("SELECT m.CABANG_AWAL,m.CABANG_TUJUAN,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$MUTASI_ID','$CABANG_AWAL','$CABANG_TUJUAN','Mutasi','Mutasi Anggota Approval','Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', 1, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$CABANG_AWAL','$CABANG_TUJUAN') AND ANGGOTA_AKSES = 'Koordinator') AND ANGGOTA_STATUS = 0");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["rejectmutasianggota"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];

        GetQuery("update t_mutasi set MUTASI_STATUS = 2, MUTASI_APPROVE_BY = '$USER_ID', MUTASI_APPROVE_TANGGAL = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("update t_notifikasi set READ_STATUS = 1 where DOKUMEN_ID = '$MUTASI_ID'");

        $getDataMutasi =  GetQuery("SELECT m.CABANG_AWAL,m.CABANG_TUJUAN,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$MUTASI_ID','$CABANG_AWAL','$CABANG_TUJUAN','Mutasi','Mutasi Anggota Approval','Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', 2, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$CABANG_AWAL','$CABANG_TUJUAN') AND ANGGOTA_AKSES = 'Koordinator') AND ANGGOTA_STATUS = 0");

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
        $EVENT_ACTION = $_POST["EVENT_ACTION"];
        $MUTASI_ID = $_POST["MUTASI_ID"];

        if ($EVENT_ACTION == "reset") {
            GetQuery("update t_mutasi set MUTASI_STATUS = 0, MUTASI_APPROVE_BY = null, MUTASI_APPROVE_TANGGAL = null where MUTASI_ID = '$MUTASI_ID'");

            GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

            GetQuery("update t_notifikasi set APPROVE_STATUS = 0, READ_STATUS = 0 where DOKUMEN_ID = '$MUTASI_ID'");
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