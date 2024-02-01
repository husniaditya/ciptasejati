<?php 
require_once ("./module/connection/conn.php");

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

        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START row -->
            <?php include 'module/component/loginregister/v_aktivasiakun.php'; ?>
            <!--/ END row -->
        </section>
        <!--/ END Template Main -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <?php include 'module/js.php';?>
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>