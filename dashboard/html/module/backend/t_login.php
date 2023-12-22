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

    $GetUser = GetQuery("SELECT u.*,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.CABANG_KEY,a.ANGGOTA_AKSES,c.CABANG_DESKRIPSI,a.TINGKATAN_ID,t.TINGKATAN_NAMA,a.ANGGOTA_PIC 
    from m_user u 
    left join m_anggota a on u.ANGGOTA_KEY = a.ANGGOTA_KEY 
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    where a.ANGGOTA_ID='$USERNAME'");
    while ($rowUser = $GetUser->fetch(PDO::FETCH_ASSOC)) {
        extract($rowUser);
    }

    if ($USER_STATUS == 0 && password_verify($PASSWORD, $USER_PASSWORD)) {
        $_SESSION["LOGINKEY_CS"] = $ANGGOTA_KEY;
        $_SESSION["LOGINIDUS_CS"] = $ANGGOTA_ID;
        $_SESSION["LOGINNAME_CS"] = $ANGGOTA_NAMA;
        $_SESSION["LOGINCAB_CS"] = $CABANG_KEY;
        $_SESSION["LOGINPP_CS"] = $ANGGOTA_PIC;
        $_SESSION["LOGINAKS_CS"] = $ANGGOTA_AKSES;

        $aksesMenu = GetQuery("SELECT u.MENU_ID,u.USER_AKSES,TRIM(REPLACE(m.MENU_NAMA, ' ', '')) as MASTER,`VIEW`,`ADD`,`EDIT`,APPROVE,`DELETE`,`PRINT` 
        FROM m_menuakses u 
        LEFT JOIN m_menu m ON u.MENU_ID = m.MENU_ID
        WHERE u.USER_AKSES='$ANGGOTA_AKSES'");
        while ($rowAkses = $aksesMenu->fetch(PDO::FETCH_ASSOC)) {
            extract($rowAkses);
            $MENUUSER = $MASTER;
            
            $_SESSION["VIEW_".$MENUUSER] = $VIEW;
            $_SESSION["ADD_".$MENUUSER] = $ADD;
            $_SESSION["EDIT_".$MENUUSER] = $EDIT;
            $_SESSION["APPROVE_".$MENUUSER] = $APPROVE;
            $_SESSION["DELETE_".$MENUUSER] = $DELETE;
            $_SESSION["PRINT_".$MENUUSER] = $PRINT;
        }

        ?><script>document.location.href='dashboard.php';</script><?php
        die(0);
    } else {
        ?><script>alert('ID Anggota atau password salah');</script><?php
        ?><script>document.location.href='index.php';</script><?php
        die(0);
    }

}
?>