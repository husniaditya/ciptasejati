<?php
require_once ("../../../module/connection/conn.php");

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["id"])) {

    try {
        $ANGGOTA_ID = $_POST["id"];
        $string = $_POST["pass"];

        $resetMail =  GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_STATUS = 0 AND DELETION_STATUS = 0 AND ANGGOTA_ID = '$ANGGOTA_ID'");
        while ($rowReset = $resetMail->fetch(PDO::FETCH_ASSOC)) {
            extract($rowReset);
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
        $ccAddresses[] = 'agus.riyanto.ckp@gmail.com';
        $ccNames[] = 'AGUS RIYANTO';

        $subject = 'Reset Password Anggota Cipta Sejati Indonesia';
        // Pass $MUTASI_ID to the mutasimail.php
        ob_start();
        include('resetpass.php');
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