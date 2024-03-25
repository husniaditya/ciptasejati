<?php
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
?>

<!-- START Sidebar Content -->
<section class="content slimscroll">
    <!-- START Template Navigation/Menu -->
    <ul class="topmenu topmenu-responsive" data-toggle="menu">
        <?php
        $getParent = GetQuery("SELECT m.* FROM m_menu m
        LEFT JOIN m_menuakses a ON m.MENU_ID = a.MENU_ID
        WHERE m.MENU_INDUK=0 AND a.USER_AKSES='$USER_AKSES' and a.`VIEW`='Y'
        ORDER BY m.MENU_ID ASC;");

        while ($rowParent = $getParent->fetch(PDO::FETCH_ASSOC)) {
            extract($rowParent);
            if ($MENU_ID == 1) {
                ?>
                <li  class="<?= $MENU_LEVEL; ?>">
                    <a href="<?= $MENU_URL; ?>">
                        <span class="figure"><i class="<?= $MENU_ICON; ?>"></i></span>
                        <span class="text"><?= $MENU_NAMA; ?></span>
                    </a>
                </li>
                <?php
            } else {
                ?>
                <li class="<?= $MENU_LEVEL; ?>" >
                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#<?= $MENU_TARGET; ?>" data-parent=".topmenu">
                        <span class="figure"><i class="<?= $MENU_ICON; ?>"></i></span>
                        <span class="text"><?= $MENU_NAMA; ?></span>
                        <span class="arrow"></span>
                    </a>
                    <!-- START 2nd Level Menu -->
                    <ul id="<?= $MENU_TARGET; ?>" class="submenu collapse ">
                        <?php
                        $getChild2 = GetQuery("SELECT m.* FROM m_menu m
                        LEFT JOIN m_menuakses a ON m.MENU_ID = a.MENU_ID
                        WHERE left(m.MENU_ID,1)='$MENU_ID' AND MENU_LEVEL='level2' and a.USER_AKSES = '$USER_AKSES' AND a.view = 'Y'
                        ORDER BY m.MENU_ID ASC");

                        while ($rowChild2 = $getChild2->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <?php
                            if ($rowChild2["MENU_TARGET"] == "") {
                                ?>
                                <li class="<?= $rowChild2["MENU_LEVEL"]; ?>">
                                    <a href="<?= $rowChild2["MENU_URL"]; ?>">
                                        <span class="text"><?= $rowChild2["MENU_NAMA"]; ?></span>
                                    </a>
                                </li>
                                <?php
                            }
                            else {
                                ?>
                                <li  class="<?= $rowChild2["MENU_LEVEL"]; ?>" >
                                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#<?= $rowChild2["MENU_TARGET"]; ?>" data-parent="#<?= $MENU_TARGET; ?>">
                                        <span class="text"><?= $rowChild2["MENU_NAMA"]; ?></span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul id="<?= $rowChild2["MENU_TARGET"]; ?>" class="submenu collapse ">
                                        <?php
                                        $getChild3 = GetQuery("SELECT m.* FROM m_menu m
                                        LEFT JOIN m_menuakses a ON m.MENU_ID = a.MENU_ID
                                        WHERE m.MENU_INDUK = '{$rowChild2["MENU_ID"]}' AND MENU_LEVEL='level3' and a.USER_AKSES = 'administrator' AND a.view = 'Y' AND m.DELETION_STATUS = 0
                                        ORDER BY m.MENU_ID ASC");

                                        while ($rowChild3 = $getChild3->fetch(PDO::FETCH_ASSOC)) {
                                            if ($rowChild3["MENU_TARGET"] == "") {
                                                ?>
                                                <li  class="<?= $rowChild3["MENU_LEVEL"]; ?>" >
                                                    <a href="<?= $rowChild3["MENU_URL"]; ?>">
                                                        <span class="text"><?= $rowChild3["MENU_NAMA"]; ?></span>
                                                    </a>
                                                </li>
                                                <?php
                                            } else {
                                                ?>
                                                <li class="<?= $rowChild3["MENU_LEVEL"]; ?>" >
                                                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#<?= $rowChild3["MENU_TARGET"]; ?>" data-parent="#<?= $rowChild2["MENU_TARGET"]; ?>">
                                                        <span class="text"><?= $rowChild3["MENU_NAMA"]; ?></span>
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul id="<?= $rowChild3["MENU_TARGET"]; ?>" class="submenu collapse ">
                                                        <?php
                                                        $getChild4 = GetQuery("SELECT m.* FROM m_menu m
                                                        LEFT JOIN m_menuakses a ON m.MENU_ID = a.MENU_ID
                                                        WHERE m.MENU_INDUK = '{$rowChild3["MENU_ID"]}' AND MENU_LEVEL='level4' and a.USER_AKSES = 'administrator' AND a.view = 'Y' AND m.DELETION_STATUS = 0
                                                        ORDER BY m.MENU_ID ASC");

                                                        while ($rowChild4 = $getChild4->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <li class="<?= $rowChild4["MENU_LEVEL"]; ?>">
                                                                <a href="<?= $rowChild4["MENU_URL"]; ?>">
                                                                    <span class="text"><?= $rowChild4["MENU_NAMA"]; ?></span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <?php
                                            }
                                            
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                    <!--/ END 2nd Level Menu -->
                    </ul>
                </li>
                <?php
            }
            
        }
        ?>
    </ul>
    <!--/ END Template Navigation/Menu -->
</section>
<!--/ END Sidebar Container -->