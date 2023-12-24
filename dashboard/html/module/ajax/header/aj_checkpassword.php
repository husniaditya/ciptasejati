<?php

$NEWPASSWORD = $_POST["NEWPASSWORD"];
$CONFIRMPASSWORD = $_POST["CONFIRMPASSWORD"];

if ($NEWPASSWORD != $CONFIRMPASSWORD) {
    $response = "Password baru dengan password konfirmasi tidak sama";
    ?><p style="color:darkred"><?= $response; ?></p><?php
}
?>