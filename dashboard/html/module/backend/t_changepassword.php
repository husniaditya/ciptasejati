<?php
require_once ("../../module/connection/conn.php");

$OLDPASSWORD = "";
$NEWPASSWORD = "";
$CONFIRMPASSWORD = "";
$PASSWORD = "";

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

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

    $rows = $CheckPassword = GetQuery("call sp_user('GET','CheckPassword','$USER_ID','$USERNAME','$USER_PASSWORD','$NAMA','$USER_STATUS','$PROFILE_PIC','$USER_ID',now(),'$PROJECT_OWNER')");
    while ($rowCheckPassword = $CheckPassword->fetch(PDO::FETCH_ASSOC)) {
        extract($rowCheckPassword);
    }
    if ($NEWPASSWORD != $CONFIRMPASSWORD) {
        $response="New Password doesn't match with Confirm Password";
        echo $response;
    }
    elseif (password_verify($OLDPASSWORD, $USER_PASSWORD)) {
        
        $PASSWORD = password_hash($CONFIRMPASSWORD, PASSWORD_BCRYPT, $options);
        GetQuery("call sp_user('Update','ChangePassword','$USER_ID','$USERNAME','$PASSWORD','$NAMA','$USER_STATUS','$PROFILE_PIC','$USER_ID',now(),'$PROJECT_OWNER')");

        $response="Success";
        echo $response;
    } else {
        $response="Your old password is incorrect";
        echo $response;
    }
}
?>