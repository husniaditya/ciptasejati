<?php

$getPusat = GetQuery("SELECT * FROM m_pusat");
while ($rowPusat = $getPusat->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPusat);
}
$getMedia = GetQuery("SELECT * FROM c_mediasosial");
?>
<div class="container container-xl-custom pt-5 pb-3">
    <div class="row pt-5">
        <div class="col-md-6 col-lg-3">
            <h3 class="mb-3 text-4-5 text-color-light">Kantor Pusat</h3>							
            <p class="text-3 text-color-grey mb-0"><?= $PUSAT_SEKRETARIAT; ?></p>
        </div>
        <div class="col-md-6 col-lg-3 mt-4 mt-md-0">
            <h3 class="mb-3 text-4-5 text-color-light">Hubungi Kami</h3>							
            <a href="tel:<?= $PROFIL_TELP_1; ?>" class="d-flex align-items-center text-decoration-none text-info text-color-hover-light font-weight-medium ms-1">
                <i class="icon icon-phone text-info text-4-5 me-2"></i>
                <?= $PROFIL_TELP_1; ?>
            </a>
            <a class="d-flex align-items-center text-decoration-none text-info text-color-hover-light font-weight-medium ms-1" href="mailto:<?= $PROFIL_EMAIL_1; ?>"><i class="fa-regular fa-envelope text-info text-4-5 me-2"></i><?= $PROFIL_EMAIL_1; ?></a>
            <a class="d-flex align-items-center text-decoration-none text-info text-color-hover-light font-weight-medium ms-1" href="mailto:<?= $PROFIL_EMAIL_2; ?>"> <i class="fa-regular fa-envelope text-info text-4-5 me-2"></i><?= $PROFIL_EMAIL_2; ?></a>
        </div>
        <div class="col-md-6 col-lg-4 mt-4 mt-lg-0">
            <h3 class="mb-3 text-4-5 text-color-light">Halaman</h3>
            <ul class="list list-unstyled columns-lg-2 font-weight-medium">
                <li><a href="tentang.php" class="text-color-grey text-color-hover-cipta-white">Tentang Kami</a></li>
                <li><a href="cabang.php" class="text-color-grey text-color-hover-cipta-white">Daftar Cabang</a></li>
                <li><a href="koordinatorcabang.php" class="text-color-grey text-color-hover-cipta-white">Koordinator Cabang</a></li>
                <li><a href="blog.php" class="text-color-grey text-color-hover-cipta-white">Blog</a></li>
                <li><a href="hubungi.php" class="text-color-grey text-color-hover-cipta-white">Hubungi Kami</a></li>
            </ul>
        </div>
        <div class="col-md-6 col-lg-2 mt-4 mt-lg-0">
            <h3 class="mb-3 text-4-5 text-color-light">Ikuti Kami</h3>
            <ul class="social-icons social-icons-clean social-icons-medium">
                <?php
                foreach ($getMedia as $rowMedia) {
                    extract($rowMedia);
                    ?>
                    <li class="social-icons-skype">
                        <a href="<?= $MEDIA_LINK; ?>" target="_blank" title="<?= $MEDIA_DESKRIPSI; ?>">
                            <i class="<?= $MEDIA_ICON; ?> text-color-light"></i>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<div class="footer-copyright bg-transparent mt-5">
    <div class="container container-xl-custom">
        <hr class="bg-color-light opacity-1">
        <div class="row">
            <div class="col mt-4 mb-4 pb-5">
                <p class="text-center text-color-grey text-3 mb-0">Cipta Sejati Indonesia © 2023. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</div>