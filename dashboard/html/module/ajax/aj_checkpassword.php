<?php

$NEWPASSWORD = $_POST["NEWPASSWORD"];
$CONFIRMPASSWORD = $_POST["CONFIRMPASSWORD"];

if ($NEWPASSWORD != $CONFIRMPASSWORD) {
    $response = "Password doesn't match";
    ?><p style="color:darkred"><?= $response; ?></p><?php
}
?>