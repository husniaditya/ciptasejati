<?php
require_once ("../../connection/conn.php");

$USER_KEY = $_SESSION["LOGINKEY_CS"];

$getCountNotif = GetQuery("SELECT COUNT(*) AS TOTAL FROM t_notifikasi WHERE NOTIFIKASI_USER = '$USER_KEY' AND READ_STATUS = 0");
while ($rowNotif = $getCountNotif->fetch(PDO::FETCH_ASSOC)) {
    extract($rowNotif);
    if ($TOTAL <= 20) {
        echo $TOTAL;
    } else {
        echo "20+";
    }
}

?>