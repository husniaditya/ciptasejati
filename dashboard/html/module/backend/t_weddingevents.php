<?php
require_once ("../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$EVENT_ID=createKode("m_event","EVENT_ID","EVE-$YEAR$MONTH-",3);
$PHOTO_ID=createKode("d_eventphoto","PHOTO_ID","PHO-$YEAR$MONTH-",3);


$EVENT_TITLE="";
$EVENT_LOCATION="";
$EVENT_DATE="";
$EVENT_TIME="";
$EVENT_DESC="";
$EVENT_MAP="";
$EVENT_STATUS="";
$PHOTO_PIC="";
$PHOTO_STATUS="";


$GetEvent = GetQuery("call sp_event('GET','Event','$EVENT_ID','$EVENT_TITLE','$EVENT_LOCATION','$EVENT_DATE','$EVENT_TIME','$EVENT_DESC','$EVENT_MAP','$EVENT_STATUS','$PHOTO_PIC','$PHOTO_STATUS','$PROJECT_OWNER')");

if (isset($_POST["saveevent"])) {

    try {
        // Code that may throw an exception
        $DATE_TIME = new DateTime($_POST["EVENT_DATE"]);

        $EVENT_TITLE = $_POST["EVENT_TITLE"];
        $EVENT_LOCATION = $_POST["EVENT_LOCATION"];
        $EVENT_DATE = $DATE_TIME->format('Y-m-d');
        $EVENT_TIME = $DATE_TIME->format('H:i:s');
        $EVENT_DESC = $_POST["EVENT_DESC"];
        $EVENT_MAP = $_POST["EVENT_MAP"];

        // Handle uploaded files
        if (!empty($_FILES['EVENT_FILE']['tmp_name'])) {
            $resultInc = GetQuery("select count(PHOTO_ID) as INC from d_eventphoto where EVENT_ID = '$EVENT_ID'");
            while ($rowInc = $resultInc->fetch(PDO::FETCH_ASSOC)) {
                extract($rowInc);

                $URUT = $INC + 1;
            }
            
            for ($i = 0; $i < count($_FILES['EVENT_FILE']['tmp_name']); $i++) {
            $PHOTO_ID=createKode("d_eventphoto","PHOTO_ID","PHO-$YEAR$MONTH-",3);
            
            $fileName = $_FILES['EVENT_FILE']['name'][$i];
            $fileTmp = $_FILES['EVENT_FILE']['tmp_name'][$i];

            // $ext = strtolower(pathinfo($_FILES["EVENT_FILE"]["name"][$i], PATHINFO_EXTENSION));

            if (!file_exists("../../../../images/events/".$PROJECT_OWNER)) {
                mkdir("../../../../images/events/".$PROJECT_OWNER, 0777, true);
            }

            $FILE = "../../../../images/events/".$PROJECT_OWNER."/" .$URUT. " " . $fileName;
            $FILESAVE = "images/events/".$PROJECT_OWNER."/" .$URUT. " " . $fileName;
            
            move_uploaded_file($_FILES["EVENT_FILE"]["tmp_name"][$i],$FILE);
            
            GetQuery("insert into d_eventphoto (PHOTO_ID,EVENT_ID,PHOTO_NAME,PHOTO_PIC,PROJECT_OWNER,INPUT_BY,INPUT_DATE) values ('$PHOTO_ID','$EVENT_ID','$fileName','$FILESAVE','$PROJECT_OWNER','$USER_ID',now())");
            
            }
        }

        GetQuery("insert into m_event (EVENT_ID,EVENT_TITLE,EVENT_LOCATION,EVENT_DATE,EVENT_TIME,EVENT_DESC,EVENT_MAP,PROJECT_OWNER,INPUT_BY,INPUT_DATE) values ('$EVENT_ID','$EVENT_TITLE','$EVENT_LOCATION','$EVENT_DATE','$EVENT_TIME','$EVENT_DESC','$EVENT_MAP','$PROJECT_OWNER','$USER_ID',now())");

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
        $EVENT_ID = $_POST["EVENT_ID"];
    
        GetQuery("update m_event set EVENT_STATUS = 1 where EVENT_ID = '$EVENT_ID'");
        $response="Success";
        echo $response;
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["editevent"])) {

    try {
        $EVENT_ID = $_POST["EVENT_ID"];
        // Code that may throw an exception
        $DATE_TIME = new DateTime($_POST["EVENT_DATE"]);

        $EVENT_TITLE = $_POST["EVENT_TITLE"];
        $EVENT_LOCATION = $_POST["EVENT_LOCATION"];
        $EVENT_DATE = $DATE_TIME->format('Y-m-d');
        $EVENT_TIME = $DATE_TIME->format('H:i:s');
        $EVENT_DESC = $_POST["EVENT_DESC"];
        $EVENT_MAP = $_POST["EVENT_MAP"];


        // Handle uploaded files
        if (!empty($_FILES['EVENT_FILE']['tmp_name'])) {
            $resultInc = GetQuery("select count(PHOTO_ID) as INC from d_eventphoto where EVENT_ID = '$EVENT_ID'");
            while ($rowInc = $resultInc->fetch(PDO::FETCH_ASSOC)) {
                extract($rowInc);

                $URUT = $INC + 1;
            }

            GetQuery("update d_eventphoto set PHOTO_STATUS=1 where EVENT_ID = '$EVENT_ID'");
            
            for ($i = 0; $i < count($_FILES['EVENT_FILE']['tmp_name']); $i++) {
            $PHOTO_ID=createKode("d_eventphoto","PHOTO_ID","PHO-$YEAR$MONTH-",3);

            $fileName = $_FILES['EVENT_FILE']['name'][$i];
            $fileTmp = $_FILES['EVENT_FILE']['tmp_name'][$i];

            // $ext = strtolower(pathinfo($_FILES["EVENT_FILE"]["name"][$i], PATHINFO_EXTENSION));

            if (!file_exists("../../../../images/events/".$PROJECT_OWNER)) {
                mkdir("../../../../images/events/".$PROJECT_OWNER, 0777, true);
            }

            $FILE = "../../../../images/events/".$PROJECT_OWNER."/" .$URUT. " " . $fileName;
            $FILESAVE = "images/events/".$PROJECT_OWNER."/" .$URUT. " " . $fileName;
            
            move_uploaded_file($_FILES["EVENT_FILE"]["tmp_name"][$i],$FILE);
            
            GetQuery("insert into d_eventphoto (PHOTO_ID,EVENT_ID,PHOTO_NAME,PHOTO_PIC,PROJECT_OWNER,INPUT_BY,INPUT_DATE) values ('$PHOTO_ID','$EVENT_ID','$fileName','$FILESAVE','$PROJECT_OWNER','$USER_ID',now())");
            
            }
        }

        GetQuery("update m_event set EVENT_TITLE = '$EVENT_TITLE',EVENT_LOCATION = '$EVENT_LOCATION',EVENT_DATE = '$EVENT_DATE',EVENT_TIME = '$EVENT_TIME',EVENT_DESC = '$EVENT_DESC',EVENT_MAP = '$EVENT_MAP',PROJECT_OWNER = '$PROJECT_OWNER',INPUT_BY = '$USER_ID',INPUT_DATE = now() where EVENT_ID = '$EVENT_ID'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>