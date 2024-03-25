<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["updatevisimisi"])) {

    try {
        $CMS_VISIMISI_ID = $_POST["CMS_VISIMISI_ID"];
        $CMS_VISIMISI_KATEGORI = $_POST["CMS_VISIMISI_KATEGORI"];

        // Directory to store files
        $directory = "../../../../assets/images/cms/visimisi";

        // Handle CMS_VISIMISI_PIC files
        if (!empty($_FILES['CMS_VISIMISI_PIC']['tmp_name'][0])) {
            foreach ($_FILES['CMS_VISIMISI_PIC']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['CMS_VISIMISI_PIC']['name'][$key];
                $idCardFileName = str_replace(' ', '_', $idCardFileName); // Replace spaces with underscores
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/cms/visimisi/" . $idCardFileName;

                GetQuery("update cms_visimisi set CMS_VISIMISI_PIC = '$idCardFileDestination' where CMS_VISIMISI_ID = '$CMS_VISIMISI_ID'");
            }
        }

        GetQuery("update cms_visimisi set CMS_VISIMISI_KATEGORI = '$CMS_VISIMISI_KATEGORI', INPUT_BY = '$USER_ID', INPUT_DATE = now() where CMS_VISIMISI_ID = '$CMS_VISIMISI_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

?>