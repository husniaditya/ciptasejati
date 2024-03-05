<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["savemedia"])) {

    try {
        $MEDIA_ID = createKode("c_mediasosial","MEDIA_ID","MED-$YEAR$MONTH-",3);
        $MEDIA_ICON = $_POST["MEDIA_ICON"];
        $MEDIA_DESKRIPSI = $_POST["MEDIA_DESKRIPSI"];
        $MEDIA_LINK = $_POST["MEDIA_LINK"];

        GetQuery("insert into c_mediasosial (MEDIA_ID, MEDIA_ICON, MEDIA_DESKRIPSI, MEDIA_LINK, DELETION_STATUS, INPUT_BY, INPUT_DATE) values ('$MEDIA_ID', '$MEDIA_ICON', '$MEDIA_DESKRIPSI', '$MEDIA_LINK', 0, '$USER_ID', now())");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updatemedia"])) {

    try {
        $MEDIA_ID = $_POST["MEDIA_ID"];
        $MEDIA_ICON = $_POST["MEDIA_ICON"];
        $MEDIA_DESKRIPSI = $_POST["MEDIA_DESKRIPSI"];
        $MEDIA_LINK = $_POST["MEDIA_LINK"];

        GetQuery("update c_mediasosial set MEDIA_ICON = '$MEDIA_ICON', MEDIA_DESKRIPSI = '$MEDIA_DESKRIPSI', MEDIA_LINK = '$MEDIA_LINK', INPUT_BY = '$USER_ID', INPUT_DATE = now() where MEDIA_ID = '$MEDIA_ID'");

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
        $MEDIA_ID = $_POST["id"];
    
        GetQuery("update c_mediasosial set DELETION_STATUS = 1, INPUT_BY = '$USER_ID', INPUT_DATE = now()  where MEDIA_ID = '$MEDIA_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>