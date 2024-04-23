<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

GetQuery("DELETE FROM t_ppd_anggota WHERE PPD_ID = 'Temp_$USER_ID'");

echo json_encode("Success");
?>
