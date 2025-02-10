<?php
require_once("../../../../module/connection/conn.php");

if (isset($_POST["MENU_ID"]) || isset($_POST["GRUP_ID"]) || isset($_POST["MENU_NAMA"]) || isset($_POST["ANGGOTA_AKSES"])) {
    $MENU_ID = $_POST["MENU_ID"];
    $GRUP_ID = $_POST["GRUP_ID"];
    $MENU_NAMA = $_POST["MENU_NAMA"];
    $USER_AKSES = $_POST["USER_AKSES"];
    
    // count length chars of GRUP_ID
    $LEN_GRUP_ID = strlen($GRUP_ID);
    
    $getMenu = GetQuery("SELECT m.*,u.MENU_NAMA,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
    FROM m_menuakses m
    LEFT JOIN m_menu u ON m.MENU_ID = u.MENU_ID
    LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
    WHERE (u.MENU_ID LIKE CONCAT('%','$MENU_ID','%')) 
    AND (u.MENU_NAMA LIKE CONCAT('%','$MENU_NAMA','%')) 
    AND (m.USER_AKSES LIKE CONCAT('%','$USER_AKSES','%'))
    AND ('$GRUP_ID' = '' OR (LEFT(u.MENU_ID,$LEN_GRUP_ID) = '$GRUP_ID'))
    ORDER BY m.USER_AKSES,m.MENU_ID");
} else {
    $getMenu = GetQuery("SELECT m.*,u.MENU_NAMA,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
    FROM m_menuakses m
    LEFT JOIN m_menu u ON m.MENU_ID = u.MENU_ID
    LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
    ORDER BY m.USER_AKSES,m.MENU_ID");
}

while ($rowMenu = $getMenu->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMenu);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= $MENU_KEY; ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#EditMenu" class="open-EditMenu" data-id="<?= $MENU_KEY; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Akses</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $MENU_ID; ?></td>
        <td><?= $MENU_NAMA; ?></td>
        <td><?= $USER_AKSES; ?></td>
        <td align="center">
            <?php
            if ($VIEW == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td align="center">
            <?php
            if ($ADD == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td align="center">
            <?php
            if ($EDIT == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td align="center">
            <?php
            if ($DELETE == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td align="center">
            <?php
            if ($APPROVE == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td align="center">
            <?php
            if ($PRINT == "Y") {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" checked disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            } else {
                ?>
                <label class="switch switch-primary">
                    <input type="checkbox" disabled>
                    <span class="switch" disabled></span>
                </label>
                <?php
            }
            
            ?>
        </td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>