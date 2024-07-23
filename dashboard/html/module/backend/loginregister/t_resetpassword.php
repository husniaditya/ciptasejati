<?php
require_once ("../../../module/connection/conn.php");

if (isset($_POST["sendemail"])) {
    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $NEWPASSWORD = $_POST["randomPassword"];
        
        $options = [
            'cost' => 12,
        ];

        $PASSWORD = password_hash($NEWPASSWORD, PASSWORD_BCRYPT, $options);

        $getEmail = GetQuery("SELECT a.ANGGOTA_EMAIL
        FROM m_anggota a
        INNER JOIN m_user u on a.ANGGOTA_ID = u.ANGGOTA_ID
        WHERE a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 and u.USER_STATUS = 0 AND a.ANGGOTA_ID = '$ANGGOTA_ID'");

        if ($getEmail->rowCount() > 0) {
            while ($dataEmail = $getEmail->fetch(PDO::FETCH_ASSOC)) {
                GetQuery("update m_user set USER_PASSWORD = '$PASSWORD', INPUT_BY = 'Reset', INPUT_DATE = '$localDateTime' WHERE  ANGGOTA_ID = '$ANGGOTA_ID'");
                GetQuery("insert into m_user_log select UUID(), ANGGOTA_KEY, ANGGOTA_ID, '$PASSWORD', '$NEWPASSWORD', USER_STATUS, 'Reset', '$localDateTime', 'U' from m_user where ANGGOTA_ID = '$ANGGOTA_ID'");

                $response="Success,$ANGGOTA_ID,$NEWPASSWORD";
                echo $response;
            }
        } else {
            $response="User tidak terdaftar!";
            echo $response;
        }
    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

?>