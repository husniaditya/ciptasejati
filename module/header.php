<?php
$getProfil = GetQuery("SELECT * FROM c_profil");
while ($rowProfil = $getProfil->fetch(PDO::FETCH_ASSOC)) {
    extract($rowProfil);
}
$getMedia = GetQuery("SELECT * FROM c_mediasosial");
?>

<div class="header-body border-0 box-shadow-none">
    <div class="border-bottom-light">
        <div class="header-container container container-xl-custom">
            <div class="header-row py-4">
                <div class="header-column text-center">
                    <div class="header-row">
                        <div class="header-logo m-0">
                            <a href="index.php">
                                <img alt="Cipta Sejati Indonesia" width="43" height="43" src="<?= $PROFIL_LOGO_WEB; ?>">
                            </a>
                        </div>
                        &nbsp;
                        <div class="m-0">
                            <h5 class="my-auto">CIPTA SEJATI INDONESIA</h5>
                        </div>
                    </div>
                </div>
                <div class="header-column justify-content-end align-items-center flex-row">
                    <div class="hstack gap-4 ps-4 py-2 font-weight-semi-bold">
                        <div class="d-none d-lg-inline-block">
                            <ul class="nav nav-pills me-1">
                                <?php
                                while ($rowMedia = $getMedia->fetch(PDO::FETCH_ASSOC)) {
                                    extract($rowMedia);
                                    ?>
                                    <li class="nav-item pe-2 mx-2">
                                        <a href="<?= $MEDIA_LINK; ?>" target="_blank" title="Facebook" class="text-color-dark text-color-hover-primary text-2"><i class="<?= $MEDIA_ICON; ?>"></i></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="vr opacity-2 d-none d-lg-inline-block"></div>
                        <div>
                            <a href="tel:0123456789" class="d-flex align-items-center text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold ms-1">
                                <i class="icon icon-phone text-color-primary text-4-5 me-2"></i>
                                <?= $PROFIL_TELP_1; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>	
    </div>				
    <div class="header-nav-bar z-index-0">
        <div class="container container-xl-custom">
            <div class="header-row py-2">
                <div class="header-column">
                    <div class="header-row align-items-center justify-content-end">
                        <div class="header-nav header-nav-links justify-content-start pb-1">
                            <div class="header-nav-main header-nav-main-text-capitalize header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" id="mainNav">
                                        <li>
                                            <a class="nav-link" href="index.php">
                                                Beranda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="tentang.php">
                                                Tentang Kami
                                            </a>
                                        </li>
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle nav-link" href="#">
                                                Cabang
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-submenu">
                                                    <a class="nav-link" href="cabang.php">Daftar Cabang</a>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a class="nav-link" href="koordinatorcabang.php">Koordinator Cabang</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="blog.php">
                                                Blog
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="hubungi.php">
                                                Hubungi Kami
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <a href="dashboard/html/" class="btn btn-modern btn-cipta font-weight-bold border-0 btn-arrow-effect-1">Login <i class="fa-solid fa-right-to-bracket"></i></a>
                        <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>