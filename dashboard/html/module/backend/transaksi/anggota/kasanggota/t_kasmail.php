<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["id"])) {

    try {
        $KAS_ID = $_POST["id"];

        $getMail =  GetQuery("SELECT k.*,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_AKSES,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,c.CABANG_SEKRETARIAT,a2.ANGGOTA_ID INPUT_BY_ID,a2.ANGGOTA_NAMA INPUT_BY,a2.ANGGOTA_AKSES INPUT_AKSES, DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y') INPUT_DATE,
        CASE
            WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
            ELSE FORMAT(k.KAS_JUMLAH, 0)
        END AS FKAS_JUMLAH,
        CASE 
            WHEN k.KAS_DK = 'D' THEN 'Debit'
            ELSE 'Kredit' 
        END AS KAS_DK_DES 
        FROM t_kas k
        LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
        LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        WHERE k.KAS_ID = '$KAS_ID'");
        while ($rowMutasi = $getMail->fetch(PDO::FETCH_ASSOC)) {
            extract($rowMutasi);
            
            $getSaldo = GetQuery("SELECT
                IFNULL(SALDOAWAL, 0) AS SALDOAWAL,
                IFNULL(SALDOAKHIR, 0) AS SALDOAKHIR
            FROM (
                SELECT
                    CASE
                        WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                        ELSE FORMAT(SUM(KAS_JUMLAH), 0)
                    END AS SALDOAWAL
                FROM
                    t_kas
                WHERE
                    DELETION_STATUS = 0
                    AND ANGGOTA_KEY = '$ANGGOTA_KEY'
                    AND KAS_JENIS = '$KAS_JENIS'
                    AND KAS_ID < '$KAS_ID'
            ) AS Subquery1
            CROSS JOIN (
                SELECT
                    CASE
                        WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                        ELSE FORMAT(SUM(KAS_JUMLAH), 0)
                    END AS SALDOAKHIR
                FROM
                    t_kas
                WHERE
                    DELETION_STATUS = 0
                    AND ANGGOTA_KEY = '$ANGGOTA_KEY'
                    AND KAS_JENIS = '$KAS_JENIS'
                    AND KAS_ID <= '$KAS_ID'
            ) AS Subquery2");

            while ($rowSaldo = $getSaldo->fetch(PDO::FETCH_ASSOC)) {
                extract($rowSaldo);
            }
        }
        
        $getEmailAddress = GetQuery("SELECT a.ANGGOTA_NAMA ANGGOTA_NAMA_MAIL,a.CABANG_KEY CABANG_KEY_MAIL,a.ANGGOTA_EMAIL,a.ANGGOTA_AKSES FROM t_notifikasi n
        LEFT JOIN t_mutasi m ON n.DOKUMEN_ID = m.MUTASI_ID
        LEFT JOIN m_anggota a ON n.NOTIFIKASI_USER = a.ANGGOTA_KEY
        WHERE n.DOKUMEN_ID = '$KAS_ID'");

        // Example usage with attachment
        $attachmentPath = '../../../../.'.$KAS_FILE;
        $attachmentName = 'Formulir Kas Anggota '.$KAS_ID.'.pdf';

        // Arrays to store CC addresses
        $toAddress = '';
        $toName = '';
        $ccAddresses = [];
        $ccNames = [];

        while ($rowEmailAddress = $getEmailAddress->fetch(PDO::FETCH_ASSOC)) {
            extract($rowEmailAddress);
            if ($ANGGOTA_AKSES == "User") {
                // To address
                $toAddress = $ANGGOTA_EMAIL;
                $toName = $ANGGOTA_NAMA_MAIL;
            } else {
                // CC addresses
                $ccAddresses[] = $ANGGOTA_EMAIL;
                $ccNames[] = $ANGGOTA_NAMA_MAIL;
            }
        }

        $subject = 'Informasi Kas ' . $KAS_JENIS . ' Anggota ' . $KAS_ID;
        // Pass $MUTASI_ID to the mutasimail.php
        ob_start();
        include('kasmail.php');
        $body = ob_get_clean();

        sendEmail($toAddress, $toName, $ccAddresses, $ccNames, $subject, $body, $attachmentPath, $attachmentName);

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>