<?php
require_once ("./module/connection/conn.php");



unset($_SESSION["LOGINIDUS_CS"]);

?><script>alert('Anda berhasil logout');</script><?php
?><script>document.location.href='index.php';</script><?php
?>