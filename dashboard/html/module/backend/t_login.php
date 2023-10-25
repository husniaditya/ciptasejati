<?php

$USERNAME="";
$PASSWORD="";
$PASS="";
$USER_STATUS="";
$USER_PASSWORD="";

if(isset($_POST["login"]))
{
    $USERNAME = $_POST["username"];
    $PASSWORD = $_POST['password'];

    $GetUser = GetQuery("select * from m_user u left join m_anggota a on u.ANGGOTA_ID = m.ANGGOTA_ID where u.ANGGOTA_ID='$USERNAME'");
    while ($rowUser = $GetUser->fetch(PDO::FETCH_ASSOC)) {
        extract($rowUser);
    }

    if ($USER_STATUS == 0 && password_verify($PASSWORD, $USER_PASSWORD)) {
        $_SESSION["LOGINIDUS_WEDD"] = $ANGGOTA_ID;
        $_SESSION["LOGINNAME_WEDD"] = $USER_NAMA;
        $_SESSION["LOGINPP_WEDD"] = $USER_PIC;

        ?><script>document.location.href='dashboard';</script><?php
        die(0);
    } else {
        ?><script>alert('Username atau password salah');</script><?php
        ?><script>document.location.href='index';</script><?php
        die(0);
    }
    

}
?>