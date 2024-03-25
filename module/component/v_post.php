<?php
$BLOG_TITLE="";
$BLOG_MESSAGE="";
$BLOG_IMAGE="";
$INPUT_BY="";
$INPUT_DATE="";

if (isset($_GET['id'])) {
    $BLOG_ID = $_GET['id'];

    $sql = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY, a.ANGGOTA_PIC,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,cb.CABANG_DESKRIPSI,d.DAERAH_DESKRIPSI
    FROM c_blog c
    LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_cabang cb ON a.CABANG_KEY = cb.CABANG_KEY
    LEFT JOIN m_daerah d ON cb.DAERAH_KEY = d.DAERAH_KEY
    WHERE c.DELETION_STATUS = 0 and c.BLOG_ID = '$BLOG_ID'");

    $getListBlog = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
    FROM c_blog c
    LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID
    WHERE c.DELETION_STATUS = 0");

    // Fetch all rows into an array
    $rowPost = $sql->fetchAll(PDO::FETCH_ASSOC);
    $rowd = $getListBlog->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rowPost as $row) {
        extract($row);
    }
    ?>
    <div role="main" class="main">

        <section class="page-header page-header-modern bg-color-grey page-header-lg m-0">
            <div class="container container-xl-custom">
                <div class="row">
                    <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                        <h1 class="text-dark font-weight-bold"><?= $BLOG_TITLE; ?></h1>
                    </div>
                    <div class="col-md-4 order-1 order-md-2 align-self-center">
                        <ul class="breadcrumb d-block text-md-end font-weight-medium">
                            <li><a href="#">Home</a></li>
                            <li class="active">Blog</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="container container-xl-custom py-5 my-3">

            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">

                    <article>
                        <div class="card border-0">
                            <div class="card-body z-index-1 p-0">
                                <p class="text-uppercase text-1 mb-3 text-color-default"><time pubdate datetime="2023-01-10"><?= $INPUT_DATE; ?></time> <span class="opacity-3 d-inline-block px-2">|</span> <?= $INPUT_BY; ?></p>

                                <div class="post-image pb-4">
                                    <img class="card-img-top rounded-0" src="./dashboard/html/<?= $BLOG_IMAGE; ?>" alt="Card Image">
                                </div>

                                <div class="card-body p-0">
                                    <p class="text-justify"><?= $BLOG_MESSAGE; ?></p>

                                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                                    <div class="addthis_inline_share_toolbox"></div>
                                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-60ba220dbab331b0"></script>

                                    <hr class="my-5">

                                    <div class="post-block post-author">
                                        <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0 mb-3">Penulis</h3>
                                        <div class="img-thumbnail img-thumbnail-no-borders d-block pb-3">
                                            <img src="./dashboard/html/<?= $ANGGOTA_PIC; ?>" class="rounded-circle" alt="" style="text-align: center;overflow: hidden;position: relative;width: 100px;height: 100px;">
                                        </div>
                                        <p><strong class="name"><a href="#" class="text-4 text-dark pb-2 pt-2 d-block"><?= $INPUT_BY; ?></a></strong></p>
                                        <p><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></p>
                                        <p><?= $CABANG_DESKRIPSI; ?>, <?= $DAERAH_DESKRIPSI; ?></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </article>

                </div>
                <div class="blog-sidebar col-lg-4 pt-4 pt-lg-0">
                    <aside class="sidebar">
                        <div class="px-3 mb-4">
                            <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0 mb-3">Tentang Blog</h3>
                            <p class="m-0">Lorem ipsum dolor sit amet, conse elit porta. Vestibulum ante justo, volutpat quis porta diam.</p>
                        </div>
                        <div class="py-1 clearfix">
                            <hr class="my-2">
                        </div>
                        <div class="px-3 mt-4">
                            <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0 mb-3">Postingan Terbaru</h3>
                            <div class="pb-2 mb-1">
                            <?php
                            foreach ($rowd as $rowTitle) {
                                extract($rowTitle);
                                ?>
                                <a href="post.php?id=<?= $BLOG_ID; ?>" class="text-color-default text-uppercase text-1 mb-0 d-block text-decoration-none"><?= $INPUT_DATE; ?> <span class="opacity-3 d-inline-block px-2">|</span> <?= $INPUT_BY; ?></a>
                                <a href="post.php?id=<?= $BLOG_ID; ?>" class="text-color-dark text-hover-primary font-weight-bold text-3 d-block pb-3 line-height-4"><?= $BLOG_TITLE; ?></a>
                                <?php
                            }
                            ?>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

        </div>
    </div>
    <?php
} else {
    
}