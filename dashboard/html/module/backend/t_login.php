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

    $GetUser = GetQuery("SELECT u.*,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.CABANG_ID,c.CABANG_DESKRIPSI,a.TINGKATAN_ID,t.TINGKATAN_NAMA,a.ANGGOTA_PIC 
    from m_user u 
    left join m_anggota a on u.ANGGOTA_KEY = a.ANGGOTA_KEY 
    LEFT JOIN m_cabang c ON a.CABANG_ID = c.CABANG_ID
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    where a.ANGGOTA_ID='$USERNAME'");
    while ($rowUser = $GetUser->fetch(PDO::FETCH_ASSOC)) {
        extract($rowUser);
    }

    if ($USER_STATUS == 0 && password_verify($PASSWORD, $USER_PASSWORD)) {
        $_SESSION["LOGINIDUS_CS"] = $ANGGOTA_ID;
        $_SESSION["LOGINNAME_CS"] = $ANGGOTA_NAMA;
        $_SESSION["LOGINCAB_CS"] = $CABANG_ID;
        $_SESSION["LOGINPP_CS"] = $ANGGOTA_PIC;

        ?><script>document.location.href='dashboard.php';</script><?php
        die(0);
    } else {
        ?><script>alert('ID Anggota atau password salah');</script><?php
        ?><script>document.location.href='index.php';</script><?php
        die(0);
    }

}
?>