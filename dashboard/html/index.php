<?php 
require_once ("./module/connection/conn.php");
include "module/backend/loginregister/t_login.php";

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
        <?php include 'module/head.php'; ?>
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body>
        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START Template Container -->
            <?php include 'module/component/loginregister/v_login.php';?>
            <!--/ END Template Container -->
        </section>
        <!--/ END Template Main -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <?php include 'module/js.php'; ?>
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>