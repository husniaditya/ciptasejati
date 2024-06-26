<?php 
require_once ("./module/connection/conn.php");

if(!isset($_SESSION["LOGINIDUS_CS"]))
{
    ?><script>alert('Silahkan login dahulu');</script><?php
    ?><script>document.location.href='index.php';</script><?php
    die(0);
}
$getMaintenance = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'isMaintenance'");
while ($mt = $getMaintenance->fetch(PDO::FETCH_ASSOC)) {
    extract($mt);
}

if($CODE == 1)
{
    ?><script>document.location.href='maintenance.php';</script><?php
    die(0);
}
?>

<!DOCTYPE html>
<html class="backend">
    <!-- START Head -->
    <head>
        <?php include 'module/head.php';?>
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
                        <h4 class="title semibold"><span class="figure"><i class="ico-home2"></i></span> Dashboard</h4>
                    </div>
                </div>
                <!-- Page Header -->

                
                <!-- START view -->
                <?php include 'module/component/dashboard/v_dashboard.php';?>
                <!--/ END view -->
                
            </div>
            <!--/ END Template Container -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->
        </section>
        <!--/ END Template Main -->

        <!-- START Template Footer -->
        <footer id="footer">
            <?php include 'module/footer.php';?>
        </footer>
        <!--/ END Template Footer -->
        
        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <?php include 'module/js.php';?>
        <script type="text/javascript" src="module/javascript/component/dashboard/dataanggota.js"></script>
        <script type="text/javascript" src="module/javascript/component/dashboard/datatingkatan.js"></script>
        <script type="text/javascript" src="module/javascript/component/dashboard/dataaktivitas.js"></script>
        <script type="text/javascript" src="module/javascript/component/dashboard/pieaktivitas.js"></script>
        <script type="text/javascript" src="module/javascript/component/dashboard/datastatus.js"></script>
        <script type="text/javascript" src="module/javascript/component/dashboard/dashboard.js"></script>
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>