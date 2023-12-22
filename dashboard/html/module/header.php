<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_KEY = $_SESSION["LOGINKEY_CS"];

$getNotif = GetQuery("SELECT n.*,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI,
CASE
  WHEN DATEDIFF(NOW(), n.INPUT_DATE) < 1 THEN
      CONCAT(
          HOUR(TIMEDIFF(NOW(), n.INPUT_DATE)), ' Jam ',
          MINUTE(TIMEDIFF(NOW(), n.INPUT_DATE)), ' Menit yang lalu'
      )
  ELSE
      CONCAT(
			DATEDIFF(NOW(), n.INPUT_DATE), ' Hari ',
			HOUR(TIMEDIFF(time(NOW()), time(n.INPUT_DATE))), ' Jam ',
         MINUTE(TIMEDIFF(time(NOW()), time(n.INPUT_DATE))), ' Menit yang lalu'
		)
END AS difference
FROM t_notifikasi n
LEFT JOIN m_anggota a ON n.INPUT_BY = a.ANGGOTA_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
WHERE NOTIFIKASI_USER = '$USER_KEY' ORDER BY READ_STATUS ASC,INPUT_DATE DESC LIMIT 15");

$getCountNotif = GetQuery("SELECT COUNT(*) AS TOTAL FROM t_notifikasi WHERE NOTIFIKASI_USER = '$USER_KEY' AND READ_STATUS = 0");
?>
<!-- START navbar header -->
<div class="navbar-header">
    <!-- Brand -->
    <a class="navbar-brand" href="javascript:void(0);">
        <span class="logo-figure-nav"></span>
        <span class="logo-text-nav"></span>
    </a>
    <!--/ Brand -->
</div>
<!--/ END navbar header -->

<!-- START Toolbar -->
<div class="navbar-toolbar clearfix">
    <!-- START Left nav -->
    <ul class="nav navbar-nav navbar-left">
        <!-- Sidebar shrink -->
        <li class="hidden-xs hidden-sm">
            <a href="javascript:void(0);" class="sidebar-minimize" data-toggle="minimize" title="Minimize sidebar">
                <span class="meta">
                    <span class="icon"></span>
                </span>
            </a>
        </li>
        <!--/ Sidebar shrink -->

        <!-- Offcanvas left: This menu will take position at the top of template header (mobile only). Make sure that only #header have the `position: relative`, or it may cause unwanted behavior -->
        <li class="navbar-main hidden-lg hidden-md hidden-sm">
            <a href="javascript:void(0);" data-toggle="sidebar" data-direction="ltr" rel="tooltip" title="Menu sidebar">
                <span class="meta">
                    <span class="icon"><i class="ico-paragraph-justify3"></i></span>
                </span>
            </a>
        </li>
        <!--/ Offcanvas left -->
    </ul>
    <!--/ END Left nav -->

    <!-- START navbar form -->
    <div class="navbar-form navbar-left dropdown" id="dropdown-form">
        <form action="" role="search">
            <div class="has-icon">
                <input type="text" class="form-control" placeholder="Search application...">
                <i class="ico-search form-control-icon"></i>
            </div>
        </form>
    </div>
    <!-- START navbar form -->
    
    <!-- START Right nav -->
    <ul class="nav navbar-nav navbar-right">
        <!-- Notification dropdown -->
        <li class="dropdown custom" id="header-dd-notification">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <span class="meta">
                    <span class="icon"><i class="fa-regular fa-bell fa-lg"></i></span>
                    <?php
                    while ($rowNotif = $getCountNotif->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowNotif);
                        ?>
                        <span class="label label-danger"><?= $TOTAL; ?></span>
                        <?php
                    }
                    ?>
                </span>
            </a>

            <!-- Dropdown menu -->
            <div class="dropdown-menu" role="menu">
                <div class="dropdown-header">
                    <span class="title"><i class="fa-regular fa-bell"></i> Notifikasi <span class="count"></span></span>
                </div>
                <div class="dropdown-body">
                    
                    <!-- Message list -->
                    <div class="media-list">
                        <?php
                        while ($rowNotif = $getNotif->fetch(PDO::FETCH_ASSOC)) {
                            extract($rowNotif);
                            if ($READ_STATUS == 0) {
                                ?>
                                <a href="#" data-toggle="modal" data-toggle="modal" class="media border-dotted open-ChangePassword" style="background-color: lavender;">
                                    <span class="media-body">
                                        <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                                        <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                                        <span class="media-text ellipsis nm semibold"><?= $BODY; ?></span>
                                        <!-- meta icon -->
                                        <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                                        <span class="media-meta pull-right"><?= $difference; ?></span>
                                        <!--/ meta icon -->
                                    </span>
                                </a>
                                <?php
                            } else {
                                ?>
                                <a href="javascript:void(0);" class="media read border-dotted">
                                    <span class="media-body">
                                        <span class="media-heading"><?= $DOKUMEN_ID; ?></span>
                                        <span class="media-heading"><?= $SUBJECT; ?></span>
                                        <span class="media-text ellipsis nm"><?= $BODY; ?></span>
                                        <!-- meta icon -->
                                        <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                                        <span class="media-meta pull-right"><?= $difference; ?></span>
                                        <!--/ meta icon -->
                                    </span>
                                </a>
                                <?php
                            }
                            
                        }
                        ?>
                    </div>
                    <!--/ Message list -->
                </div>
            </div>
            <!--/ Dropdown menu -->
        </li>
        <!--/ Notification dropdown -->
        
        <!-- Profile dropdown -->
        <li class="dropdown profile">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <span class="meta">
                    <span class="avatar"><img src="<?=$_SESSION["LOGINPP_CS"]; ?>" class="img-circle" alt="" /></span>
                    <span class="text hidden-xs hidden-sm pl5"><?= $_SESSION["LOGINNAME_CS"]; ?></span>
                </span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="page-profile.php"><span class="icon"><i class="fa-solid fa-user-check"></i></span> Akun Saya</a></li>
                <li><a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-ChangePassword" href="#ChangePassword"><span class="icon"><i class="fa-solid fa-key"></i></span> Ubah Password</a></li>
                <li><a href="assets/dataterpusat/panduan/Panduan Aplikasi Cipta Sejati.pdf" target="_blank"><span class="icon"><i class="ico-book2"></i></span> Buku Panduan</a></li>
                <li class="divider"></li>
                <li><a href="logout.php"><span class="icon"><i class="ico-exit"></i></span> Sign Out</a></li>
            </ul>
        </li>
        <!-- Profile dropdown -->
        
    </ul>
    <!--/ END Right nav -->
</div>
<!--/ END Toolbar -->

<?php include 'module/component/v_changepassword.php'; ?>