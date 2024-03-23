<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

if (isset($_POST["saveblog"])) {

    try {
        $BLOG_ID = createKode("c_blog","BLOG_ID","BLG-$YEAR$MONTH-",3);
        $BLOG_TITLE = $_POST["BLOG_TITLE"];
        $BLOG_MESSAGE = $_POST["BLOG_MESSAGE"];

        GetQuery("insert into c_blog (BLOG_ID, BLOG_TITLE, BLOG_MESSAGE, INPUT_BY, INPUT_DATE) values ('$BLOG_ID', '$BLOG_TITLE', '$BLOG_MESSAGE', '$USER_ID', now())");

        // Directory to store files
        $directory = "../../../../assets/images/blog";

        // Handle BLOG_IMAGE files
        if (!empty($_FILES['BLOG_IMAGE']['tmp_name'][0])) {
            foreach ($_FILES['BLOG_IMAGE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['BLOG_IMAGE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/blog/" . $idCardFileName;

                GetQuery("update c_blog set BLOG_IMAGE = '$idCardFileDestination' where BLOG_ID = '$BLOG_ID'");
            }
        }

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updateblog"])) {

    try {
        $BLOG_ID = $_POST["BLOG_ID"];
        $BLOG_TITLE = $_POST["BLOG_TITLE"];
        $BLOG_MESSAGE = $_POST["BLOG_MESSAGE"];
        $DELETION_STATUS = $_POST["DELETION_STATUS"];

        GetQuery("UPDATE c_blog SET BLOG_TITLE = '$BLOG_TITLE', BLOG_MESSAGE = '$BLOG_MESSAGE', DELETION_STATUS = '$DELETION_STATUS', INPUT_BY = '$USER_ID', INPUT_DATE = now() WHERE BLOG_ID = '$BLOG_ID'");

        // Directory to store files
        $directory = "../../../../assets/images/blog";

        // Handle BLOG_IMAGE files
        if (!empty($_FILES['BLOG_IMAGE']['tmp_name'][0])) {
            foreach ($_FILES['BLOG_IMAGE']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['BLOG_IMAGE']['name'][$key];
                $idCardFileDestination = $directory . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/blog/" . $idCardFileName;

                GetQuery("update c_blog set BLOG_IMAGE = '$idCardFileDestination' where BLOG_ID = '$BLOG_ID'");
            }
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
        $BLOG_ID = $_POST["id"];
    
        GetQuery("delete from c_blog where BLOG_ID = '$BLOG_ID'");

        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

?>