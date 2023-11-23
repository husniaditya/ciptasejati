<?php
require_once ("./module/connection/conn.php");



unset($_SESSION["LOGINIDUS_CS"]);
session_destroy();   // destroy session data in storage

?><script>alert('Anda berhasil logout');</script><?php
?><script>document.location.href='index.php';</script><?php
?>