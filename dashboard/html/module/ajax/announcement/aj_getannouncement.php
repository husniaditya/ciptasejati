<?php
require_once ("../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINUSNAME_WEDD"];
$PROJECT_OWNER = $_SESSION["LOGINPROJECT_WEDD"];


if (isset($_POST["ANNOUNCEMENT_TITLE"]) || isset($_POST["ANNOUNCEMENT_COUPLE"]) || isset($_POST["ANNOUNCEMENT_TEXTDATE"]) || isset($_POST["ANNOUNCEMENT_DATE"])) {
    $ANNOUNCEMENT_TITLE = $_POST["ANNOUNCEMENT_TITLE"];
    $ANNOUNCEMENT_COUPLE = $_POST["ANNOUNCEMENT_COUPLE"];
    $ANNOUNCEMENT_TEXTDATE = $_POST["ANNOUNCEMENT_TEXTDATE"];
    $ANNOUNCEMENT_DATE = $_POST["ANNOUNCEMENT_DATE"];

    $GetAnn = GetQuery("select * from m_announcement where PROJECT_OWNER = '$PROJECT_OWNER' and ANNOUNCEMENT_STATUS = '0' AND (ANNOUNCEMENT_TITLE LIKE CONCAT('%', '$ANNOUNCEMENT_TITLE', '%')) and  (ANNOUNCEMENT_COUPLE LIKE CONCAT('%', '$ANNOUNCEMENT_COUPLE', '%')) and (ANNOUNCEMENT_TEXTDATE LIKE CONCAT('%', '$ANNOUNCEMENT_TEXTDATE', '%')) and (ANNOUNCEMENT_DATE LIKE CONCAT('%', '$ANNOUNCEMENT_DATE', '%')) order by ANNOUNCEMENT_TITLE");
    
} else {
    $GetAnn = GetQuery("select * from m_announcement where PROJECT_OWNER = '$PROJECT_OWNER' and ANNOUNCEMENT_STATUS = '0' order by ANNOUNCEMENT_TITLE");
}

while ($rowAnn = $GetAnn->fetch(PDO::FETCH_ASSOC)) {
    extract($rowAnn);
    ?>
    <tr>
        <td align="center">
            <form id="annoption-form" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewAnnouncement" class="open-ViewAnnouncement" data-annid="<?= $ANNOUNCEMENT_ID; ?>" data-title="<?= $ANNOUNCEMENT_TITLE; ?>" data-couple="<?= $ANNOUNCEMENT_COUPLE; ?>" data-video="<?= $ANNOUNCEMENT_VIDEO; ?>" data-textdate="<?= $ANNOUNCEMENT_TEXTDATE; ?>" data-date="<?= $ANNOUNCEMENT_DATE; ?>" style="color:forestgreen;"><span class="ico-check"></span> View</a></li>
                        <li><a data-toggle="modal" href="#EditAnnouncement" class="open-EditAnnouncement editButtons" data-annid="<?= $ANNOUNCEMENT_ID; ?>" data-title="<?= $ANNOUNCEMENT_TITLE; ?>" data-couple="<?= $ANNOUNCEMENT_COUPLE; ?>" data-video="<?= $ANNOUNCEMENT_VIDEO; ?>" data-textdate="<?= $ANNOUNCEMENT_TEXTDATE; ?>" data-date="<?= $ANNOUNCEMENT_DATE; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Edit</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="AnnconfirmAndPost('<?= $GUEST_ID;?>','deleteann')" style="color:firebrick;"><span class="ico-trash"></span> Delete</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td class="hidden"><?= $ANNOUNCEMENT_ID;?></td>
        <td><?= $ANNOUNCEMENT_TITLE;?></td>
        <td><?= $ANNOUNCEMENT_COUPLE;?></td>
        <td><iframe width="360" height="210" src="https://www.youtube.com/embed/7yNSOigxOfg?autoplay=1&loop=1" frameborder="0" allowfullscreen></iframe></td>
        <td><?= $ANNOUNCEMENT_TEXTDATE;?></td>
        <td><?= $ANNOUNCEMENT_DATE;?></td>
    </tr>
    <?php
}
?>