<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];


if (isset($_POST["updateuser"])) {
    try {
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $USER_PASSWORD = $_POST["USER_PASSWORD"];
        $ANGGOTA_AKSES = $_POST["ANGGOTA_AKSES"];

        $options = [
            'cost' => 12,
        ];

        $PASSWORD = password_hash($USER_PASSWORD, PASSWORD_BCRYPT, $options);

        if ($USER_PASSWORD = "") {
            GetQuery("update m_user set INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_ID = '$ANGGOTA_ID'");

            GetQuery("update m_anggota set ANGGOTA_AKSES = '$ANGGOTA_AKSES', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_ID = '$ANGGOTA_ID' and ANGGOTA_STATUS = 0 and DELETION_STATUS = 0");
        } else {
            GetQuery("update m_user set USER_PASSWORD = '$PASSWORD', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_ID = '$ANGGOTA_ID'");

            GetQuery("update m_anggota set ANGGOTA_AKSES = '$ANGGOTA_AKSES', INPUT_BY = '$USER_ID', INPUT_DATE = now() where ANGGOTA_ID = '$ANGGOTA_ID' and ANGGOTA_STATUS = 0 and DELETION_STATUS = 0");
        }

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) {

    try {
        $ANGGOTA_ID = $_POST["id"];
    
        GetQuery("update m_user set USER_STATUS = 2 where ANGGOTA_ID = '$ANGGOTA_ID'");

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>