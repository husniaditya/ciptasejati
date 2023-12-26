<?php
require_once ("../../connection/conn.php");

if (!empty($_POST["NOTIFIKASI_ID"])) {
    try {
        $NOTIFIKASI_ID = $_POST["NOTIFIKASI_ID"];
        GetQuery("UPDATE t_notifikasi SET READ_STATUS = 1 WHERE NOTIFIKASI_ID = '$NOTIFIKASI_ID'");
    } catch (\Throwable $th) {
        //throw $th;
    }
}