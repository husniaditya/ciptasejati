<?php

if (isset($_GET["token"])) {
    $ANGGOTA_KEY = $_GET["token"];

    $anggotaCheck = GetQuery("SELECT COUNT(*) C_ANGGOTA FROM m_user WHERE ANGGOTA_KEY = '$ANGGOTA_KEY' AND USER_STATUS = 1");

    while ($check = $anggotaCheck->fetch(PDO::FETCH_ASSOC)) {
        extract($check);

        if ($C_ANGGOTA == 1) {
            GetQuery("UPDATE m_user SET USER_STATUS = 0 WHERE ANGGOTA_KEY = '$ANGGOTA_KEY'");
            GetQuery("insert into m_user_log select UUID(), ANGGOTA_KEY, ANGGOTA_ID, USER_PASSWORD, '', USER_STATUS, 'System', NOW(), 'U' from m_user where ANGGOTA_KEY = '$ANGGOTA_KEY'");
            ?>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <!-- Carousel -->
                    <div class="panel nm no-border">
                        <div class="panel-body owl-carousel" id="carousel1">
                            <div class="item table-layout">
                                <div class="col-sm-3 text-center">
                                    <img src="../image/icons/socialengagement.png" alt="advertisment" width="100%">
                                </div>
                                <div class="col-sm-9 pl15 text">
                                    <h4>Akun user anda sudah berhasil diaktivasi!</h4>
                                    <p class="text-justify">Selamat datang di Institut Seni Bela Diri Silat <b>CIPTA SEJATI</b> yang mengajarkan “tenaga dalam”, juga dikenal dengan istilah Prana atau Chi (Ki). Diantara manfaat terpenting bagi peserta latih adalah dapat meningkatkan dan memelihara kesehatan, pengobatan diri sendiri dan orang lain (bidang kesehatan).</p>
                                    <a href="index.php" class="btn btn-primary btn-outline btn-rounded"><i class="fa-solid fa-arrow-left"></i> Menuju Halaman Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Carousel -->
                </div>
                <div class="col-md-3"></div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <!-- Carousel -->
                    <div class="panel nm no-border">
                        <div class="panel-body owl-carousel" id="carousel1">
                            <div class="item table-layout">
                                <div class="col-sm-3 text-center">
                                        <img src="../image/icons/supportservices.png" alt="support" width="100%">
                                </div>
                                <div class="col-sm-9 pl15 text">
                                    <h4>Akun user anda tidak dapat diaktivasi!</h4>
                                    <p class="text-justify">Silahkan hubungi koordinator / pengurus setempat untuk info lebih lanjut.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Carousel -->
                </div>
                <div class="col-md-3"></div>
            </div>
            <?php
        }
        
    }
}
?>