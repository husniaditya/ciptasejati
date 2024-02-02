<?php
require_once ("../../../module/connection/conn.php");

if (isset($_POST["savedaftaruser"])) {
    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $NEWPASSWORD = $_POST["NEWPASSWORD"];
        $CONFIRMPASSWORD = $_POST["CONFIRMPASSWORD"];
        
        $options = [
            'cost' => 12,
        ];

        $PASSWORD = password_hash($CONFIRMPASSWORD, PASSWORD_BCRYPT, $options);

        if ($NEWPASSWORD != $CONFIRMPASSWORD) {
            $response="Password baru dan konfirmasi password tidak sama!";
            echo $response;
        } else {
            $userCheck = GetQuery("SELECT COUNT(*) COUNTS FROM m_user u
            LEFT JOIN m_anggota a ON u.ANGGOTA_KEY = a.ANGGOTA_KEY
            WHERE a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 AND u.ANGGOTA_ID = '$ANGGOTA_ID'");
    
            while ($rowCheck = $userCheck->fetch(PDO::FETCH_ASSOC)) {
                extract($rowCheck);
                if ($COUNTS == 1) {
                    $response="User sudah diaktivasi!";
                    echo $response;
                } else {
                    $anggotaCheck = GetQuery("SELECT COUNT(*) C_ANGGOTA,ANGGOTA_KEY,ANGGOTA_ID FROM m_anggota WHERE ANGGOTA_ID = '$ANGGOTA_ID' AND ANGGOTA_STATUS = 0 AND DELETION_STATUS = 0");
                    while ($rowAnggota = $anggotaCheck->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowAnggota);
                        if ($C_ANGGOTA == 0) {
                            $response="User tidak terdaftar!";
                            echo $response;
                        } else {
                            GetQuery("insert into m_user select '$ANGGOTA_KEY', '$ANGGOTA_ID', '$PASSWORD', '1', 'System', NOW()");
                            GetQuery("insert into m_user_log select UUID(), ANGGOTA_KEY, ANGGOTA_ID, '$PASSWORD', '$CONFIRMPASSWORD', USER_STATUS, 'System', NOW(), 'I' from m_user where ANGGOTA_KEY = '$ANGGOTA_KEY' and ANGGOTA_ID = '$ANGGOTA_ID'");
                            
                            $response="Success,$ANGGOTA_ID";
                            echo $response;
                        }
                    }
                }
                
            }
        }

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["resendemail"])) {
    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $NEWPASSWORD = $_POST["NEWPASSWORD"];
        $CONFIRMPASSWORD = $_POST["CONFIRMPASSWORD"];
        
        $options = [
            'cost' => 12,
        ];

        $PASSWORD = password_hash($CONFIRMPASSWORD, PASSWORD_BCRYPT, $options);

        if ($NEWPASSWORD != $CONFIRMPASSWORD) {
            $response="Password baru dan konfirmasi password tidak sama!";
            echo $response;
        } else {
            $userCheck = GetQuery("SELECT COUNT(*) COUNTS, u.USER_STATUS FROM m_user u
            LEFT JOIN m_anggota a ON u.ANGGOTA_KEY = a.ANGGOTA_KEY
            WHERE a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0 AND u.ANGGOTA_ID = '$ANGGOTA_ID'");
    
            while ($rowCheck = $userCheck->fetch(PDO::FETCH_ASSOC)) {
                extract($rowCheck);
                if ($COUNTS == 1 && $USER_STATUS == 0) {
                    $response="User sudah diaktivasi!";
                    echo $response;
                } else {
                    $anggotaCheck = GetQuery("SELECT COUNT(*) C_ANGGOTA,ANGGOTA_KEY,ANGGOTA_ID FROM m_anggota WHERE ANGGOTA_ID = '$ANGGOTA_ID' AND ANGGOTA_STATUS = 0 AND DELETION_STATUS = 0");
                    while ($rowAnggota = $anggotaCheck->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowAnggota);
                        if ($C_ANGGOTA == 0) {
                            $response="User tidak terdaftar!";
                            echo $response;
                        } else {
                            GetQuery("update m_user set USER_PASSWORD = '$PASSWORD' where ANGGOTA_ID = '$ANGGOTA_ID'");
                            GetQuery("insert into m_user_log select UUID(), ANGGOTA_KEY, ANGGOTA_ID, '$PASSWORD', '$CONFIRMPASSWORD', USER_STATUS, 'System', NOW(), 'U' from m_user where ANGGOTA_KEY = '$ANGGOTA_KEY' and ANGGOTA_ID = '$ANGGOTA_ID'");

                            $response="Success,$ANGGOTA_ID";
                            echo $response;
                        }
                    }
                }
                
            }
        }

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>