<?php
include "module/backend/loginregister/t_login.php";

$getURL = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'URL'");
while ($urlData = $getURL->fetch(PDO::FETCH_ASSOC)) {
    extract($urlData);
}
?>
<section class="container">
    <!-- START row -->
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <!-- Brand -->
            <div class="text-center" style="margin-bottom:20px;">
                <span class="logo-figure inverse"></span>
                <h4 class="semibold text-muted mt-5">CIPTA SEJATI INDONESIA</h4>
            </div>
            <!--/ Brand -->

            <hr><!-- horizontal line -->

            <!-- Login form -->
            <form class="panel" name="form-login" action="" method="post">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-stack has-icon pull-left">
                            <input name="username" type="text" class="form-control input-lg" placeholder="ID Anggota" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your username" data-parsley-required>
                            <i class="ico-user2 form-control-icon"></i>
                        </div>
                        <div class="form-stack has-icon pull-left">
                            <input name="password" id="NEWPASSWORD" type="password" class="form-control input-lg" placeholder="Password" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your password" data-parsley-required>
                            <div class="input-group-append form-control-icon" onclick="togglePassword('NEWPASSWORD')">
                                <span class="input-group-text toggle-password">
                                    <i class="fas fa-lock form-control-icon"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Error container -->
                    <div id="error-container"class="mb15"></div>
                    <!--/ Error container -->

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="<?= $DESK; ?>"><i class="ico-globe form-control-icon"></i> Kembali ke website</a>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="lupapassword">Lupa password?</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group nm">
                        <!-- <a href="dashboard.php" class="btn btn-block btn-primary"><span class="semibold">Sign In</span></a> -->
                        <button type="submit" class="btn btn-block btn-primary" name="login"><span class="semibold">Sign In</span></button>
                    </div>
                </div>
            </form>
            <!-- Login form -->

            <hr><!-- horizontal line -->

            <p class="text-muted text-center">Tidak punya akun? <a class="semibold" href="daftar.php">Segera daftar di sini</a></p>

            <hr><!-- horizontal line -->
        </div>
    </div>
    <!--/ END row -->
</section>

<!-- Page script -->
<script src="module/javascript/component/loginregister/login.js"></script>