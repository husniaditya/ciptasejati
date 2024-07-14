<?php
$getListBlog = GetQuery("SELECT c.*,SUBSTRING(BLOG_IMAGE FROM 2) BLOG_IMAGE, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_blog c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID
WHERE c.DELETION_STATUS = 0");


// Fetch all rows into an array
$rowd = $getListBlog->fetchAll(PDO::FETCH_ASSOC);

?>
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-grey page-header-lg m-0">
        <div class="container container-xl-custom">
            <div class="row">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-dark font-weight-bold">Blog</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-block text-md-end font-weight-medium">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Blog</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container container-xl-custom py-5 my-3">

        <div class="row">
            <div class="col-lg-8 mb-5 mb-lg-0">
                <?php
                foreach ($rowd as $rowBlog) {
                    extract($rowBlog);
                    ?>
                    <article class="mb-5">
                        <div class="card bg-transparent border-0">
                            <div class="card-body p-0 z-index-1">
                                <a href="post.php?id=<?= $BLOG_ID; ?>" data-cursor-effect-hover="plus">
                                    <img class="card-img-top rounded-0 mb-2" src="./dashboard/html/<?= $BLOG_IMAGE; ?>" alt="Card Image" style="text-align: center;overflow: hidden;position: relative;height: 500; width:1200">
                                </a>
                                <p class="text-uppercase text-color-default text-1 my-2">
                                    <time pubdate datetime="2023-01-10"><?= $INPUT_DATE; ?></time> 
                                    <span class="opacity-3 d-inline-block px-2">|</span> 
                                    <?= $INPUT_BY; ?>
                                </p>
                                <div class="card-body p-0">
                                    <h4 class="card-title text-5 font-weight-bold pb-1 mb-2"><a class="text-color-dark text-color-hover-primary text-decoration-none" href="post.php?id=<?= $BLOG_ID; ?>"><?= $BLOG_TITLE; ?></a></h4>
                                    <p class="card-text mb-2"><?= substr_replace($BLOG_MESSAGE, '...', 200); ?></p>
                                    <a href="post.php?id=<?= $BLOG_ID; ?>" class="read-more text-color-read-more font-weight-semibold mt-0 text-2">Tampilkan Lebih Banyak <i class="fas fa-angle-right position-relative top-1 ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php
                }
                ?>
            </div>
            <div class="blog-sidebar col-lg-4 pt-4 pt-lg-0">
                <aside class="sidebar">
                    <div class="px-3 mb-4">
                        <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0 mb-3">Tentang Blog Kami</h3>
                        <p class="m-0">Halaman ini menampilkan kegiatan-kegiatan yang kami lakukan</p>
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