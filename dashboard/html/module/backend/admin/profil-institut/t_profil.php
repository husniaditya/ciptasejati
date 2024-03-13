<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["editprofil"])) {

    try {
        $PROFIL_ID = $_POST["PROFIL_ID"];
        $PROFIL_NAMA = $_POST["PROFIL_NAMA"];
        $PROFIL_SEJARAH = $_POST["PROFIL_SEJARAH"];
        $PROFIL_TELP_1 = $_POST["PROFIL_TELP_1"];
        $PROFIL_TELP_2 = $_POST["PROFIL_TELP_2"];
        $PROFIL_EMAIL_1 = $_POST["PROFIL_EMAIL_1"];
        $PROFIL_EMAIL_2 = $_POST["PROFIL_EMAIL_2"];

        // Directory to store files
        $directory = "../../../../assets/images/logo/";

        // Handle Logo files
        if (!empty($_FILES['PROFIL_LOGO']['tmp_name'][0])) {
            foreach ($_FILES['PROFIL_LOGO']['tmp_name'] as $key => $logoFileTmp) {
                // Get the original file extension
                $logoFileExtension = pathinfo($_FILES['PROFIL_LOGO']['name'][$key], PATHINFO_EXTENSION);

                // Use 'Logo' as the filename with its original extension
                $logoFileDestination = $directory . "/" . 'Logo.' . $logoFileExtension;
                move_uploaded_file($logoFileTmp, $logoFileDestination);

                // Re-initialize the variable for database
                $logoFileDestination = "./assets/images/logo/Logo." . $logoFileExtension;
                $logoFileDestinationWeb = "./dashboard/html/assets/images/logo." . $logoFileExtension;

                GetQuery("UPDATE c_profil SET PROFIL_LOGO = '$logoFileDestination', PROFIL_LOGO_WEB = '$logoFileDestinationWeb' WHERE PROFIL_ID = '$PROFIL_ID'");
            }
        }




        GetQuery("update c_profil set PROFIL_NAMA = '$PROFIL_NAMA', PROFIL_SEJARAH = '$PROFIL_SEJARAH', PROFIL_TELP_1 = '$PROFIL_TELP_1', PROFIL_TELP_2 = '$PROFIL_TELP_2', PROFIL_EMAIL_1 = '$PROFIL_EMAIL_1', PROFIL_EMAIL_2 = '$PROFIL_EMAIL_2' where PROFIL_ID = '$PROFIL_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

?>