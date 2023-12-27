<?php
require_once ("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["MUTASI_ID"])) {

    try {
        $MUTASI_ID = $_POST["MUTASI_ID"];

        $getDataMutasi =  GetQuery("SELECT m.*,a.ANGGOTA_ID,m.CABANG_AWAL,m.CABANG_TUJUAN,m.ANGGOTA_KEY,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI CABANG_AWAL,d.DAERAH_DESKRIPSI DAERAH_AWAL,c2.CABANG_DESKRIPSI CABANG_TUJUAN,d2.DAERAH_DESKRIPSI DAERAH_TUJUAN,DATE_FORMAT(m.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,
        CASE 
            WHEN m.MUTASI_STATUS = '0' THEN 'Menunggu' 
            WHEN m.MUTASI_STATUS = '1' THEN 'Disetujui' 
            ELSE 'Ditolak' 
        END AS MUTASI_STATUS_DES
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

        // Example usage with attachment
        $attachmentPath = '../../../../.'.$MUTASI_FILE;
        $attachmentName = 'Formulir Mutasi Anggota '.$MUTASI_ID.'.pdf';

        $toAddress = 'adityahusni90@gmail.com';
        $toName = 'Husni Aditya';
        $ccAddresses = ['adityahusni90@yahoo.com'];
        $ccNames = ['Husni Aditya'];
        $subject = 'Persetujuan Mutasi Anggota '. $MUTASI_ID;
        $body = "
            <html>
            <body>
                <p><b>STATUS DOKUMEN : $MUTASI_STATUS_DES</b></p>
                </br>
                <table border='1'>
                    <tr>
                        <th>ID Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Cabang Asal</th>
                        <th>Cabang Tujuan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Efektif</th>
                    </tr>
                    <tr>
                        <td>$ANGGOTA_ID</td>
                        <td>$ANGGOTA_NAMA</td>
                        <td>$CABANG_AWAL, $DAERAH_AWAL</td>
                        <td>$CABANG_TUJUAN, $DAERAH_TUJUAN</td>
                        <td>$MUTASI_DESKRIPSI</td>
                        <td>$TANGGAL_EFEKTIF</td>
                    </tr>
                    <!-- Add more rows as needed -->
                </table>
            </body>
            </html>
        ";

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