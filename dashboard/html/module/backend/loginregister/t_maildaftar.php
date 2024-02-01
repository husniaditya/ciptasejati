<?php
require_once ("../../../module/connection/conn.php");

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["id"])) {

    try {
        $ANGGOTA_ID = $_POST["id"];

        $getMail =  GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_STATUS = 0 AND DELETION_STATUS = 0 AND ANGGOTA_ID = '$ANGGOTA_ID'");
        while ($rowAnggota = $getMail->fetch(PDO::FETCH_ASSOC)) {
            extract($rowAnggota);
        }

        // Arrays to store CC addresses
        $toAddress = '';
        $toName = '';
        $ccAddresses = [];
        $ccNames = [];

        // To address
        $toAddress = $ANGGOTA_EMAIL;
        $toName = $ANGGOTA_NAMA;
        // CC addresses
        $ccAddresses[] = 'adityahusni90@gmail.com';
        $ccNames[] = 'Husni Aditya A';

        $subject = 'Verifikasi Pendaftaran Anggota Cipta Sejati Indonesia';
        // Pass $MUTASI_ID to the mutasimail.php
        ob_start();
        include('daftarmail.php');
        $body = ob_get_clean();

        sendEmail($toAddress, $toName, $ccAddresses, $ccNames, $subject, $body);

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>