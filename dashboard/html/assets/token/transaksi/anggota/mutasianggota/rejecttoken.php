<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

if (isset($_GET["token"])) {
    $MUTASI_KEY = $_GET["token"];

    $STATUS = GetQuery("SELECT MUTASI_ID,MUTASI_STATUS FROM t_mutasi WHERE MUTASI_KEY = '$MUTASI_KEY'");
    while ($rowStatus = $STATUS->fetch(PDO::FETCH_ASSOC)) {
        extract($rowStatus);
    }

    if ($MUTASI_STATUS == 0) {
        GetQuery("update t_mutasi set MUTASI_STATUS = 2, MUTASI_APPROVE_BY = '$USER_ID', MUTASI_APPROVE_TANGGAL = now() where MUTASI_ID = '$MUTASI_ID'");

        GetQuery("insert into t_mutasi_log select uuid(), MUTASI_ID, CABANG_AWAL, CABANG_TUJUAN, ANGGOTA_KEY, MUTASI_DESKRIPSI, MUTASI_TANGGAL, MUTASI_STATUS, MUTASI_STATUS_TANGGAL, MUTASI_APPROVE_BY, MUTASI_APPROVE_TANGGAL, MUTASI_FILE, DELETION_STATUS, 'U', '$USER_ID', now() from t_mutasi where MUTASI_ID = '$MUTASI_ID'");

        $getDataMutasi =  GetQuery("SELECT m.CABANG_AWAL,m.CABANG_TUJUAN,m.ANGGOTA_KEY,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI FROM t_mutasi m
        LEFT JOIN m_anggota a ON m.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
        WHERE m.mutasi_id = '$MUTASI_ID'");
        while ($rowMutasi = $getDataMutasi->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
        }

        GetQuery("delete from t_notifikasi where DOKUMEN_ID = '$MUTASI_ID'");

        GetQuery("insert into t_notifikasi
        select uuid(),ANGGOTA_KEY,'$MUTASI_ID','$CABANG_AWAL','$CABANG_TUJUAN','Mutasi','ViewNotifMutasi','open-ViewNotifMutasi','Persetujuan Mutasi Anggota','Mutasi a.n $ANGGOTA_NAMA dari cabang $CABANG_DESKRIPSI', 2, 0, '$USER_ID', NOW()
        FROM m_anggota
        WHERE (ANGGOTA_AKSES = 'Administrator' or CABANG_KEY IN ('$CABANG_AWAL','$CABANG_TUJUAN') AND ANGGOTA_AKSES = 'Koordinator' OR ANGGOTA_KEY = '$ANGGOTA_KEY') AND ANGGOTA_STATUS = 0");
    }
}
?>