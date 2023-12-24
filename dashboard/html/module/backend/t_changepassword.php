<?php
require_once ("../../module/connection/conn.php");

$OLDPASSWORD = "";
$NEWPASSWORD = "";
$CONFIRMPASSWORD = "";
$PASSWORD = "";

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_KEY = $_SESSION["LOGINKEY_CS"];

$USERNAME = "";
$USER_PASSWORD = "";
$NAMA = "";
$USER_STATUS = "";
$PROFILE_PIC = "";



if (isset($_POST["changepassword"])) {
    $OLDPASSWORD = $_POST["OLDPASSWORD"];
    $NEWPASSWORD = $_POST["NEWPASSWORD"];
    $CONFIRMPASSWORD = $_POST["CONFIRMPASSWORD"];

    $options = [
        'cost' => 12,
    ];

    $rows = $CheckPassword = GetQuery("select * from m_user where ANGGOTA_KEY = '$USER_KEY'");
    while ($rowCheckPassword = $CheckPassword->fetch(PDO::FETCH_ASSOC)) {
        extract($rowCheckPassword);
    }
    if ($NEWPASSWORD != $CONFIRMPASSWORD) {
        $response="Inputan password baru dan konfirmasi password tidak sama";
        echo $response;
    }
    elseif (password_verify($OLDPASSWORD, $USER_PASSWORD)) {
        
        $PASSWORD = password_hash($CONFIRMPASSWORD, PASSWORD_BCRYPT, $options);
        GetQuery("update m_user set USER_PASSWORD = '$PASSWORD' where ANGGOTA_KEY = '$USER_KEY'");

        $response="Success";
        echo $response;
    } else {
        $response="Password lama yang diinputkan tidak sesuai";
        echo $response;
    }
}
?>