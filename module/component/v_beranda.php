<?php
$getLogo = GetQuery("SELECT PROFIL_LOGO FROM c_profil");
while ($rowLogo = $getLogo->fetch(PDO::FETCH_ASSOC)) {
	extract($rowLogo);
}
$getVisi = GetQuery("SELECT * FROM c_visimisi WHERE VISIMISI_KATEGORI = 'Visi'");
$getMisi = GetQuery("SELECT * FROM c_visimisi WHERE VISIMISI_KATEGORI = 'Misi'");
$getKegiatan = GetQuery("SELECT * FROM c_kegiatan WHERE DELETION_STATUS = 0");
$getInformasi = GetQuery("SELECT *,ROW_NUMBER() OVER (ORDER BY INFORMASI_ID) AS row_num FROM c_informasi WHERE DELETION_STATUS = 0");
$getKoordinator = GetQuery("SELECT a.*,SUBSTRING(a.ANGGOTA_PIC, 2) ANGGOTA_PIC,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN
FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE a.ANGGOTA_AKSES = 'Koordinator' AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0");
$getListBlog = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_blog c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID
WHERE c.DELETION_STATUS = 0 ORDER BY INPUT_DATE DESC limit 10");

$rowInformasi = $getInformasi->fetchAll(PDO::FETCH_ASSOC);

?>



<div role="main" class="main">

    <section class="section section-with-shape-divider border-0 py-0 my-0 bg-transparent">

        <div class="hero position-relative overflow-hidden">
            <div class="background-image-wrapper position-absolute overlay overlay-show overlay-primary overlay-op-8 top-0 left-0 right-0 bottom-0" data-appear-animation="kenBurnsToLeft" data-appear-animation-duration="30s" data-plugin-options="{'minWindowWidth': 0}" style="background-image: url(img/demos/renewable-energy/hero/hero-1.png); background-size: cover; background-position: left top;"></div>

            <div class="hero-el-1 bg-color-tertiary z-index-2"></div>
            <div class="hero-el-2 z-index-1"></div>

            <svg class="d-none d-lg-block z-index-3 custom-svg-position-1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1686.88 1095.86" data-appear-animation-svg="true">
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

            <div class="container-fluid position-relative z-index-3 h-100">
                <div class="row px-lg-5 mx-lg-3 justify-content-between align-items-center h-100">
                    <div class="col-xl-5 text-center text-xl-end">
                        <h2 class="font-weight-bold text-color-light line-height-4 text-5 mb-2 text-uppercase appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="800" data-plugin-options="{'minWindowWidth': 0}"><span class="opacity-6">Institut Seni Bela Diri Silat</span></h2>
                        <h1 class="text-color-light font-weight-bold text-14 negative-ls-05 line-height-1 mb-4 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="1100" data-plugin-options="{'minWindowWidth': 0}">CIPTA SEJATI<br><span class="font-weight-extra-bold custom-highlight-1 ws-nowrap p-1 custom-highlight-anim custom-highlight-anim-delay">Indonesia</span></h1>
                    </div>
                    <div class="col-xl-3 text-center text-xl-end d-none d-xl-block">
                        <div class="row align-items-end pb-4">
                            <div class="col-12 ps-5">
                                <div class="appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">
                                    <img src="img/lazy.png" data-src="./dashboard/html/assets/images/logo/Logo.png" class="img-fluid border-radius lazyload">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-el-pos-2">
            <div class="shape-divider shape-divider-bottom shape-divider-reverse-y custom-el-pos-3 z-index-6">
                <div class="shape-divider-horizontal-animation shape-divider-horizontal-animation-to-right top-9" style="animation-duration: 20s;">
                    <div class="d-flex width-100vw">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1 custom-shape-divider-1-flip-horizonal">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                    </div>
                </div>
            </div>

            <div class="shape-divider shape-divider-bottom shape-divider-reverse-y z-index-6 opacity-7">
                <div class="shape-divider-horizontal-animation shape-divider-horizontal-animation-to-right top-9" style="animation-duration: 30s;">
                    <div class="d-flex width-100vw">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1 custom-shape-divider-1-flip-horizonal">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                    </div>
                </div>
            </div>

            <div class="shape-divider shape-divider-bottom shape-divider-reverse-y z-index-6 opacity-5">
                <div class="shape-divider-horizontal-animation shape-divider-horizontal-animation-to-right top-9" style="animation-duration: 10s;">
                    <div class="d-flex width-100vw">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1 custom-shape-divider-1-flip-horizonal">
                        <img src="img/demos/renewable-energy/svg/divider-1.svg" class="width-100vw custom-shape-divider-1">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Visi Misi -->
    <section class="section my-0 py-5 border-0 bg-color-grey"> 
        <div class="container container-xl-custom pt-4 pb-5">
            <div class="row justify-content-between py-5">
                <div class="col-lg-6">
                    <h4 class="text-cipta text-3 font-weight-bold mb-2">VISI</h4>
                    <ul>
                        <?php
                        while ($rowVisi = $getVisi->fetch(PDO::FETCH_ASSOC)) {
                            extract($rowVisi);
                            ?>
                            <li><p class="mb-4 pb-2"><?= $VISIMISI_DESKRIPSI; ?></p></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0 mb-5 mb-lg-0">
                    <h4 class="text-cipta text-3 font-weight-bold mb-2">MISI</h4>
                    <ul>
                        <?php
                        while ($rowMisi = $getMisi->fetch(PDO::FETCH_ASSOC)) {
                            extract($rowMisi);
                            ?>
                            <li><p class="mb-4 pb-2"><?= $VISIMISI_DESKRIPSI; ?></p></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section my-0 py-5 border-0 bg-color-primary text-color-light p-relative overflow-hidden">

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

        <div class="container container-xl-custom pt-4 pb-5 mb-5">
            <div class="row align-items-center py-5">
                <div class="col-lg-3 text-lg-end">
                    <h2 class="text-color-light font-weight-bold text-10 negative-ls-05 line-height-1 mb-0 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">Kegiatan Cipta Sejati<br><span class="font-weight-extra-bold custom-highlight-1 custom-highlight-1-tertiary ws-nowrap p-1 custom-highlight-anim custom-highlight-anim-delay">Indonesia</span></h2>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <p class="mb-0 text-color-light ps-lg-5">Membuat generasi muda untuk mencintai budaya pencak silat yang menjadi kebanggaan bangsa indonesia. Semua kegiatan Cipta Sejati Indonesia bertujuan untuk meningkatkan keterampilan, persaudaraan, dan kesadaran budaya pencak silat Indonesia di antara anggotanya.</p>
                </div>
                <div class="col-lg-3 mt-3 mt-lg-0 text-lg-end">
                    <a href="hubungi.php" class="btn btn-modern btn-light text-color-dark font-weight-bold border-0 py-3 px-5 btn-arrow-effect-1 ws-nowrap">DAFTAR SEKARANG <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Kegiatan -->
    <div class="container container-xl-custom p-relative z-index-1 custom-el-pos-1">
        <div class="row">
            <?php
            foreach ($getKegiatan as $rowKegiatan) {
                extract($rowKegiatan);
                ?>
                <div class="col-lg-3">
                    <div class="card border-0 bg-color-light box-shadow-1 box-shadow-1-hover anim-hover-translate-top-10px transition-3ms">
                        <div class="card-body text-center text-lg-start m-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">
                            <img height="90" src="./dashboard/html<?= $KEGIATAN_IMAGE; ?>" alt="" />
                            <h4 class="font-weight-bold mt-4"><?= $KEGIATAN_JUDUL; ?></h4>
                            <p class="text-3"><?= $KEGIATAN_DESKRIPSI; ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col py-4">
                <hr>
            </div>
        </div>
    </div>

    <!-- Section Koordinator Cabang -->
    <section class="section my-0 py-5 border-0 bg-transparent">
        <div class="container container-xl-custom">
            <div class="row justify-content-between align-items-center py-5">
                <div class="col-lg-3">
                    <h4 class="text-danger text-3 font-weight-bold mb-2">Cipta Sejati Indonesia</h4>
                    <h3 class="mb-3">Koordinator Cabang </h3>
                    <p class="mb-5 mb-lg-0">Cras a elit sit amet leo accumsan volutpat. Suspendisse hendreriast ehicula leo</p>
                </div>
                <div class="col-lg-8">
                    <div class="carousel-half-full-width-wrapper carousel-half-full-width-right">
                        <div class="owl-carousel owl-theme carousel-half-full-width-right nav-style-1 nav-dark nav-font-size-lg mb-0" data-plugin-options="{'responsive': {'1200': {'items': 3}}, 'loop': true, 'nav': true, 'dots': false, 'margin': 20}">
                            <?php
                            while ($rowKoordinator = $getKoordinator->fetch(PDO::FETCH_ASSOC)) {
                                extract($rowKoordinator);
                                ?>
                                <div class="p-relative">
                                    <span class="thumb-info thumb-info-swap-content anim-hover-inner-wrapper rounded">
                                        <span class="thumb-info-wrapper overlay overlay-show overlay-gradient-bottom-content">
                                        <img src="./dashboard/html/<?= $ANGGOTA_PIC; ?>" class="img-fluid" alt="" style="text-align: center; overflow: hidden; position: relative; height: 400px; object-fit:cover;">
                                        </span>
                                    </span>
                                    <h4 class="font-weight-bold mt-4"><?= $ANGGOTA_NAMA; ?></h4>
                                    <ul class="list list-icons list-danger font-weight-medium mt-3">
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
                </div>
            </div>
            <div class="row">
                <div class="col py-4">
                    <hr>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Informasi -->
    <section class="my-0 py-5 border-0 bg-color-tertiary text-color-light p-relative overflow-hidden" style="background-image: url('');">
        <svg class="d-none d-lg-block custom-svg-position-3 rotate-r-90" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1686.88 1095.86" data-appear-animation-svg="true">
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
        <!-- Container Informasi -->
        <div class="container container-xl-custom pt-4 pb-5">
            <div class="row py-5">
                <div class="col-lg-6">
                    <?php
                    foreach ($rowInformasi as $rowInfo) {
                        extract($rowInfo);
                        if ($INFORMASI_KATEGORI == "Utama") {
                            ?>
                            <h3 class="mb-3 text-warning"><u><?= $INFORMASI_JUDUL; ?></u></h3>
                            <p class="mb-4 pb-2 text-light"><?= $INFORMASI_DESKRIPSI; ?></p>
                            <?php
                        }
                    }
                    ?>

                    <div class="hstack gap-4 pt-3">
                        <div>
                            <a href="hubungi.php" class="btn btn-modern btn-cipta font-weight-bold border-0 py-3 px-5 btn-arrow-effect-1 ws-nowrap">KONTAK KAMI <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <a href="tel:<?= $PROFIL_TELP_1; ?>" class="d-flex align-items-center text-decoration-none text-color-light text-color-hover-danger font-weight-semibold ms-1">
                                <i class="icon icon-phone text-color-danger text-4-5 me-2"></i>
                                <?= $PROFIL_TELP_1; ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="accordion accordion-modern-status accordion-modern-status-cipta" id="accordion100">
                        <?php
                        foreach ($rowInformasi as $rowDetailinfo) {
                            extract($rowDetailinfo);
                            if ($INFORMASI_KATEGORI == "Tambahan") {
                                ?>
                                <div class="card card-default">
                                    <div class="card-header" id="collapse100HeadingOne">
                                        <h4 class="card-title m-0">
                                            <a class="accordion-toggle text-color-dark font-weight-bold border-radius text-3-5 collapsed" data-bs-toggle="collapse" data-bs-target="#<?= $INFORMASI_ID; ?>" aria-expanded="false" aria-controls="<?= $INFORMASI_ID; ?>">
                                                <?= $row_num; ?> - <?= $INFORMASI_JUDUL; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="<?= $INFORMASI_ID; ?>" class="collapse" aria-labelledby="collapse100HeadingOne" data-bs-parent="#accordion100">
                                        <div class="card-body">
                                            <p class="mb-0 text-justify"><?= $INFORMASI_DESKRIPSI; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!-- Section Blog Kegiatan -->
    <section class="section my-0 py-5 border-0 bg-transparent">
        <div class="container container-xl-custom py-5">
            <div class="row">
                <div class="col">
                    <h4 class="text-danger text-3 font-weight-bold mb-2">DARI BLOG KAMI</h4>
                    <h3 class="mb-3">KEGIATAN TERBARU</h3>
                </div>
            </div>
            <div class="row pt-2">
                <div class="carousel-half-full-width-wrapper carousel-half-full-width-right">
                    <div class="owl-carousel owl-theme carousel-half-full-width-right nav-style-1 nav-dark nav-font-size-lg mb-0" data-plugin-options="{'responsive': {'1200': {'items': 2}}, 'loop': true, 'nav': true, 'dots': false, 'margin': 20}">
                        <?php
                        while ($rowBlog = $getListBlog->fetch(PDO::FETCH_ASSOC)) {
                            extract($rowBlog);
                            ?>
                            <div class="col-lg-12 mt-5 mt-lg-0">
                                <div class="row align-items-center p-relative">
                                    <div class="col-lg-6">
                                        <img src="./dashboard/html/<?= $BLOG_IMAGE; ?>" class="img-fluid rounded" alt="" style="text-align: center;overflow: hidden;position: relative;height: 190; width:336"/>
                                    </div>
                                    <div class="col-lg-6 mt-4 mt-lg-0">
                                        <span class="d-block text-color-grey font-weight-semibold positive-ls-2 text-1 text-uppercase"><?= $INPUT_DATE; ?> | <?= $INPUT_BY; ?></span>
                                        <h4 class="font-weight-bold text-5 text-color-hover-primary mb-2"><?= $BLOG_TITLE; ?></h4>
                                        <p class="text-3 mb-2"><?= substr_replace($BLOG_MESSAGE, '...', 100); ?></p>
                                        <a href="post.php?id=<?= $BLOG_ID; ?>" class="btn btn-arrow-effect-1 ws-nowrap text-danger text-2 bg-transparent border-0 px-0 text-uppercase stretched-link">Lihat Lebih Banyak <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>