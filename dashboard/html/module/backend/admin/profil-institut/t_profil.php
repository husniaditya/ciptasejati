<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["editprofil"])) {

    try {
        $PROFIL_ID = $_POST["PROFIL_ID"];
        $PROFIL_NAMA = $_POST["PROFIL_NAMA"];
        $PROFIL_TELP = $_POST["PROFIL_TELP"];
        $PROFIL_SEJARAH = $_POST["PROFIL_SEJARAH"];

        // Directory to store files
        $directory = "../../../../assets/images/logo/";

        // Handle ID_CARD files
        if (!empty($_FILES['PROFIL_LOGO']['tmp_name'][0])) {
            foreach ($_FILES['PROFIL_LOGO']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['PROFIL_LOGO']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/logo/" . $idCardFileName;

                GetQuery("update c_profil set PROFIL_LOGO = '$idCardFileDestination' where PROFIL_ID = '$PROFIL_ID'");
            }
        }

        GetQuery("update c_profil set PROFIL_NAMA = '$PROFIL_NAMA', PROFIL_TELP = '$PROFIL_TELP', PROFIL_SEJARAH = '$PROFIL_SEJARAH' where PROFIL_ID = '$PROFIL_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

?>