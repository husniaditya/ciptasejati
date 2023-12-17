<?php
require_once ("../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];

$EVENT_ID="";
$EVENT_TITLE="";
$EVENT_LOCATION="";
$EVENT_DATE="";
$EVENT_TIME="";
$EVENT_DESC="";
$EVENT_MAP="";
$EVENT_STATUS="";
$PHOTO_PIC="";
$PHOTO_STATUS="";

if (isset($_POST["EVENT_TITLE"]) || isset($_POST["EVENT_LOCATION"]) || isset($_POST["EVENT_DATE"]) || isset($_POST["EVENT_DESC"])) {
    $EVENT_TITLE = $_POST["EVENT_TITLE"];
    $EVENT_LOCATION = $_POST["EVENT_LOCATION"];
    $EVENT_DATE = $_POST["EVENT_DATE"];
    $EVENT_DESC = $_POST["EVENT_DESC"];

    $GetEvent = GetQuery("call sp_event('GET','FilterEvent','$EVENT_ID','$EVENT_TITLE','$EVENT_LOCATION','$EVENT_DATE','$EVENT_TIME','$EVENT_DESC','$EVENT_MAP','$EVENT_STATUS','$PHOTO_PIC','$PHOTO_STATUS','$PROJECT_OWNER')");

} else {
    $GetEvent = GetQuery("call sp_event('GET','Event','$EVENT_ID','$EVENT_TITLE','$EVENT_LOCATION','$EVENT_DATE','$EVENT_TIME','$EVENT_DESC','$EVENT_MAP','$EVENT_STATUS','$PHOTO_PIC','$PHOTO_STATUS','$PROJECT_OWNER')");
}

while ($rowEvent = $GetEvent->fetch(PDO::FETCH_ASSOC)) {
    extract($rowEvent);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewEvent" class="open-ViewEvent" data-eventid="<?= $EVENT_ID; ?>" data-title="<?= $EVENT_TITLE; ?>" data-location="<?= $EVENT_LOCATION; ?>" data-datetime="<?= $EVENT_DATETIME; ?>" data-desc="<?= $EVENT_DESC; ?>" data-map="<?= $EVENT_MAP; ?>"  data-eventstatus="<?= $EVENT_STATUS; ?>" style="color:forestgreen;"><span class="ico-check"></span> View</a></li>
                        <li><a data-toggle="modal" href="#EditEvent" class="open-EditEvent editButtons" data-eventid="<?= $EVENT_ID; ?>" data-title="<?= $EVENT_TITLE; ?>" data-location="<?= $EVENT_LOCATION; ?>" data-datetime="<?= $EVENT_DATETIME; ?>" data-desc="<?= $EVENT_DESC; ?>" data-map="<?= $EVENT_MAP; ?>"  data-eventstatus="<?= $EVENT_STATUS; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Edit</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Delete</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $EVENT_ID;?></td>
        <td><?= $EVENT_TITLE;?></td>
        <td><?= $EVENT_LOCATION;?></td>
        <td>
            <?php
            $GetPhoto = GetQuery("select * from d_eventphoto where EVENT_ID = '$EVENT_ID' and PHOTO_STATUS = '0'");
            while ($rowPhoto = $GetPhoto->fetch(PDO::FETCH_ASSOC)) {
                extract($rowPhoto);
                ?>
                <div>
                    <a href="./../../<?= $PHOTO_PIC; ?>" target="_blank">
                    <img src="./../../<?= $PHOTO_PIC; ?>" alt="Image" width="100" height="75" />
                    </a>
                </div><br>
                <?php
            }
            ?>
        </td>
        <td><?= $EVENT_DATETIME;?></td>
        <td><?= $EVENT_DESC;?></td>
        <td align="center">
            <iframe
                width="300"
                height="200"
                frameborder="0"
                style="border:0"
                src="<?= $EVENT_MAP; ?>"
                allowfullscreen
                >
            </iframe>
        </td>
    </tr>
    <?php
}
?>