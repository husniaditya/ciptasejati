<?php
$getKoordinator = GetQuery("SELECT a.*,SUBSTRING(a.ANGGOTA_PIC, 2) ANGGOTA_PIC,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN
FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE t.TINGKATAN_LEVEL BETWEEN 6 AND 9 AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0");

?>

<div role="main" class="main">

    <!-- Page Title -->
    <section class="page-header page-header-modern bg-color-grey page-header-lg m-0">
        <div class="container container-xl-custom">
            <div class="row">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-dark font-weight-bold">Koordinator Cabang</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-block text-md-end font-weight-medium">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Koordinator Cabang</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- List Koordinator -->
    <div class="container container-xl-custom pt-5 pb-3 mt-4">
        <div class="row">
            <?php
            foreach ($getKoordinator as $rowKoordinator) {
                extract($rowKoordinator);
                ?>
                <div class="col-lg-4 mb-5">
                    <span class="thumb-info thumb-info-swap-content anim-hover-inner-wrapper rounded">
                        <span class="thumb-info-wrapper overlay overlay-show overlay-gradient-bottom-content">
                        <img src="./dashboard/html/<?= $ANGGOTA_PIC; ?>" class="img-fluid" alt="" style="text-align: center; overflow: hidden; position: relative; height: 400px; object-fit:cover;">
                        </span>
                    </span>
                    <h4 class="font-weight-bold mt-4"><?= $ANGGOTA_NAMA; ?></h4>
                    <ul class="list list-icons list-primary font-weight-medium mt-3">
                        <li><i class="fas fa-code-branch text-color-primary"></i> Cabang : <?= $CABANG_DESKRIPSI; ?></li>
                        <li><i class="fas fa-chart-simple text-color-primary"></i> Tingkatan : <?= $TINGKATAN_NAMA; ?></li>
                        <li><i class="fas fa-pen text-color-primary"></i> Gelar : <?= $TINGKATAN_SEBUTAN; ?></li>
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Ads -->
    <section class="section my-0 py-5 border-0 bg-color-primary text-color-light p-relative mt-3 overflow-hidden">

        <svg class="d-none d-lg-block custom-svg-position-2 rotate-r-90" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1686.88 1095.86" data-appear-animation-svg="true">
            <path class="appear-animation" data-plugin-options="{'accY': -500, 'forceAnimation': true}" data-appear-animation="customLines1anim" data-appear-animation-delay="100" data-appear-animation-duration="7s" fill="none" stroke="#d8d8d8" stroke-width="2px" stroke-miterlimit="10" d="M87.95,1.4c6.82,9.14,15.53,21.59,24.68,36.94c6.82,11.45,27.18,46.82,42.55,96.51
                c22.8,73.68,21.39,136.02,20.51,156c-3.11,70.56-22.16,122.51-36,159.32c-10.88,28.95-11.68,24.38-59.74,125.62
                c-43.46,91.53-49.66,109.7-52.85,119.49C6.6,758.14,2.98,804.59,2.16,829.14c-1.49,44.72,4.54,70.82,6.47,78.64
                c3.54,14.35,10.42,41.25,29.79,70.47c6.64,10.01,30.84,44.6,76.77,69.11c42.9,22.9,81.52,24.6,110.47,25.87
                c45.57,2.01,79.98-6.18,113.02-14.3c30.83-7.58,58.4-18.38,113.53-40c59.55-23.35,66.43-28.58,110.47-43.91
                c35.63-12.41,57.67-19.98,89.36-25.7c25.68-4.64,55.3-9.77,94.3-6.3c12.43,1.11,53.97,5.59,102.13,27.74
                c32.05,14.74,53.03,30.87,57.53,34.38c24.26,18.91,41.05,38.65,51.91,53.45"/>
            <path class="appear-animation" data-plugin-options="{'accY': -500, 'forceAnimation': true}" data-appear-animation="customLines1anim" data-appear-animation-delay="100" data-appear-animation-duration="7s" fill="none" stroke="#d8d8d8" stroke-width="2px" stroke-miterlimit="10" d="M119.44,34.42c8.99,12.85,20.33,30.49,31.66,52.43c26.28,50.9,36.35,93.84,39.15,106.55
                c3.12,14.2,10.77,52.5,9.53,102.81c-0.28,11.19-2.03,65.48-23.83,133.79c-9.82,30.78-21.07,54.56-43.57,102.13
                c-26.78,56.6-29.14,53.79-45.62,90.21c-19.84,43.85-42.56,94.07-48.68,161.02c-2.86,31.34-5.69,66.08,7.49,108.6
                c6.03,19.44,20.95,65.45,64,101.11c47.45,39.3,101.05,42.8,133.79,44.94c63.04,4.12,115.57-13.6,165.11-30.3
                c5.59-1.89,23.59-8.86,59.57-22.81c100.23-38.85,99.33-40.27,122.21-47.32c41.18-12.69,80.51-24.8,133.11-21.79
                c19.83,1.14,63.01,5.65,111.66,28.94c8.19,3.92,50.6,24.68,88.51,64.34c5.66,5.92,10.38,11.39,12.6,13.96
                c23.78,27.59,39.5,52.94,49.36,70.81"/>
            <path class="appear-animation" data-plugin-options="{'accY': -500, 'forceAnimation': true}" data-appear-animation="customLines1anim" data-appear-animation-delay="100" data-appear-animation-duration="7s" fill="none" stroke="#d8d8d8" stroke-width="2px" stroke-miterlimit="10" d="M149.4,68.12c6.57,8.86,14.9,20.95,23.49,35.91c4.29,7.48,19.67,34.89,32.17,73.53
                c6.72,20.77,21.93,69.22,20.6,133.28c-1.3,62.06-17.49,108.17-28.94,139.91c-10.84,30.08-24.93,58.75-53.11,116.09
                c-20.68,42.08-23.94,45.72-33.7,69.11c-12.01,28.77-26.1,63.09-33.02,108.6c-5.01,32.91-10.64,69.92,1.7,115.4
                c5.5,20.27,17.08,60.94,53.45,94.64c42.55,39.43,93.06,45.28,119.49,48.34c54.36,6.29,98.94-6.87,146.04-20.77
                c14.56-4.3,31.27-10.58,64.68-23.15c90.64-34.09,94.12-40.57,133.45-51.74c33.81-9.61,71.69-20.37,122.21-16.68
                c58.83,4.3,99.83,25.64,107.91,29.96c40.02,21.39,65.7,49.16,77.96,62.64c8.35,9.18,14.84,17.39,19.4,23.49"/>
            <path class="appear-animation" data-plugin-options="{'accY': -500, 'forceAnimation': true}" data-appear-animation="customLines1anim" data-appear-animation-delay="100" data-appear-animation-duration="7s" fill="none" stroke="#d8d8d8" stroke-width="2px" stroke-miterlimit="10" d="M179.69,101.14c6.75,9.74,13.52,20.4,20.09,32c22.79,40.27,33.1,74.23,35.4,82.04
                c5.33,18.08,15.6,58.21,14.64,110.3c-0.24,12.92-1.88,65.03-24.85,127.32c-3.1,8.42-7.74,18.89-17.02,39.83
                c-25.65,57.88-36.57,75.11-52.43,110.3c-18.12,40.19-31.28,69.39-39.83,110.64c-7.2,34.73-15.41,74.33-2.38,122.21
                c4.55,16.73,14.43,51.38,44.94,82.04c35.67,35.85,78.11,44.69,100.43,49.02c58.53,11.37,107.23-3.46,156.26-18.38
                c24.42-7.44,15.93-6.77,86.47-33.7c103.46-39.5,129.97-44.06,142.98-45.96c28.7-4.18,61.65-8.66,103.15,1.02
                c7.96,1.86,47.73,11.59,90.21,42.55c30.91,22.53,50.88,47.29,62.64,64.34"/>
            <path class="appear-animation" data-plugin-options="{'accY': -500, 'forceAnimation': true}" data-appear-animation="customLines1anim" data-appear-animation-delay="100" data-appear-animation-duration="7s" fill="none" stroke="#d8d8d8" stroke-width="2px" stroke-miterlimit="10" d="M208.89,133.57c7.42,9.37,15.01,20.15,22.21,32.43c21.75,37.04,29.38,69.66,33.96,89.87
                c4.69,20.73,13.92,63.26,7.4,117.96c-4.84,40.58-16.18,69.74-28.09,100.34c-11.91,30.61-15.38,31.76-42.38,89.11
                c-24.66,52.37-36.99,78.56-44.68,105.45c-8.49,29.66-18.6,66.37-13.28,113.36c2.65,23.35,6.06,53.41,27.06,84
                c28.75,41.87,70.98,56.35,86.81,61.53c35.79,11.71,65.37,8.99,93.19,6.13c15-1.54,44-5.54,139.91-42.38
                c72.26-27.76,81.46-35.35,120.77-43.91c27.26-5.94,54.69-11.68,91.15-8.17c55.63,5.36,93.81,28.56,102.38,33.96
                c36.12,22.73,57.57,50.99,66.38,62.81c3.86,5.17,6.88,9.63,8.94,12.77"/>
        </svg>	

        <div class="container container-xl-custom py-4">
            <div class="row align-items-center py-5">
                <div class="col-lg-3 text-lg-end">
                    <h2 class="text-color-light font-weight-bold text-10 negative-ls-05 line-height-1 mb-0 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">Cipta Sejati<br><span class="font-weight-extra-bold custom-highlight-1 custom-highlight-1-tertiary ws-nowrap p-1 custom-highlight-anim custom-highlight-anim-delay">Indonesia</span></h2>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <p class="mb-0 text-color-light ps-lg-5">Cras a elit sit amet leo accumsan volutpat. Suspendisse hendreriast ehicula leo, vel efficitur felis ultrices non. Cras a elit sit amet leo acun volutpat.</p>
                </div>
                <div class="col-lg-3 mt-3 mt-lg-0 text-lg-end">
                    <a href="hubungi.php" class="btn btn-modern btn-light text-color-dark font-weight-bold border-0 py-3 px-5 btn-arrow-effect-1 ws-nowrap">DAFTAR SEKARANG <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Data Aktivitas -->
    <div class="container container-xl-custom pb-3">
        <div class="row py-5 pb-4">
            <div class="col-lg-3">
                <div class="card border-0 bg-color-light box-shadow-1 box-shadow-1-hover anim-hover-translate-top-10px transition-3ms">
                    <div class="card-body text-center text-lg-start m-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">
                        <img height="90" src="./dashboard/html/<?= $PROFIL_LOGO; ?>" alt="" />
                        <h4 class="font-weight-bold mt-4">Pembukaan Pusat Daya</h4>
                        <p class="text-3">Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Proin mauris erat, accumsan quis ullamcorper.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 bg-color-light box-shadow-1 box-shadow-1-hover anim-hover-translate-top-10px transition-3ms">
                    <div class="card-body text-center text-lg-start m-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="400" data-plugin-options="{'minWindowWidth': 0}">
                        <img height="90" src="./dashboard/html/<?= $PROFIL_LOGO; ?>" alt="" />
                        <h4 class="font-weight-bold mt-4">Ujian Kenaikan Tingkat</h4>
                        <p class="text-3">Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Proin mauris erat, accumsan quis ullamcorper.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 bg-color-light box-shadow-1 box-shadow-1-hover anim-hover-translate-top-10px transition-3ms">
                    <div class="card-body text-center text-lg-start m-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="700" data-plugin-options="{'minWindowWidth': 0}">
                        <img height="90" src="./dashboard/html/<?= $PROFIL_LOGO; ?>" alt="" />
                        <h4 class="font-weight-bold mt-4">Latihan Gabungan Nasional</h4>
                        <p class="text-3">Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Proin mauris erat, accumsan quis ullamcorper.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 bg-color-light box-shadow-1 box-shadow-1-hover anim-hover-translate-top-10px transition-3ms">
                    <div class="card-body text-center text-lg-start m-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="1200" data-plugin-options="{'minWindowWidth': 0}">
                        <img height="90" src="./dashboard/html/<?= $PROFIL_LOGO; ?>" alt="" />
                        <h4 class="font-weight-bold mt-4">Pendidikan dan Latihan</h4>
                        <p class="text-3">Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Proin mauris erat, accumsan quis ullamcorper.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>