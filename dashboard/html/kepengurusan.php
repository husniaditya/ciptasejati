<?php 
require_once ("./module/connection/conn.php");

if(!isset($_SESSION["LOGINIDUS_CS"]))
{
    ?><script>alert('Silahkan login dahulu');</script><?php
    ?><script>document.location.href='index.php';</script><?php
    die(0);
}
?>

<!DOCTYPE html>
<html class="backend">
    <!-- START Head -->
    <head>
        <?php include 'module/head.php'; ?>
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body>
        <!-- START Template Header -->
        <header id="header" class="navbar">
            <?php include 'module/header.php'; ?>
        </header>
        <!--/ END Template Header -->

        <!-- START Template Sidebar (Left) -->
        <aside class="sidebar sidebar-left sidebar-menu">
			<?php include 'module/sidebar.php'; ?>
        </aside>
        <!--/ END Template Sidebar (Left) -->

        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START Template Container -->
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="page-header page-header-block">
                    <div class="page-header-section">
                        <h4 class="title semibold">Data Kepengurusan</h4>
                    </div>
                    <div class="page-header-section">
                        <!-- Toolbar -->
                        <div class="toolbar">
                            <ol class="breadcrumb breadcrumb-transparent nm">
                                <li><a href="javascript:void(0);">Master</a></li>
                                <li class="active">Kepengurusan</li>
                            </ol>
                        </div>
                        <!--/ Toolbar -->
                    </div>
                </div>
                <!-- Page Header -->

                <?php include 'module/component/transaksi/kepengurusan/v_kepengurusan.php';?>
            </div>
        </section>
            <!--/ END Template Container -->
            <!-- START Template Footer -->
            <footer id="footer">
                <?php include 'module/footer.php';?>
            </footer>
            <!--/ END Template Footer -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->
        <!--/ END Template Main -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <?php include 'module/js.php';?>
        <script type="text/javascript" src="module/javascript/component/transaksi/kepengurusan/kepengurusan.js"></script>
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>