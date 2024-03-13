<?php 
require_once ("./dashboard/html/module/connection/conn.php");

?>

<!DOCTYPE html>
<html lang="en">
	<head>

	<?php include 'module/head.php'; ?>

	</head>
	<body class="alternative-font-7">

		<div class="body">
			<header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyStartAt': 91, 'stickySetTop': '-91px', 'stickyChangeLogo': true}">				
				<?php include 'module/header.php'; ?>

			</header>

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

							<article class="mb-5">
								<div class="card bg-transparent border-0">
									<div class="card-body p-0 z-index-1">
										<a href="post.php" data-cursor-effect-hover="plus">
											<img class="card-img-top rounded-0 mb-2" src="img/blog/wide/blog-24.jpg" alt="Card Image">
										</a>
										<p class="text-uppercase text-color-default text-1 my-2">
											<time pubdate datetime="2023-01-10">10 Jan 2023</time> 
											<span class="opacity-3 d-inline-block px-2">|</span> 
											John Doe
										</p>
										<div class="card-body p-0">
											<h4 class="card-title text-5 font-weight-bold pb-1 mb-2"><a class="text-color-dark text-color-hover-primary text-decoration-none" href="post.php">An Interview with John Doe</a></h4>
											<p class="card-text mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc viverra lorem , consectetur adipiscing elit...</p>
											<a href="post.php" class="read-more text-color-read-more font-weight-semibold mt-0 text-2">Tampilkan Lebih Banyak <i class="fas fa-angle-right position-relative top-1 ms-1"></i></a>
										</div>
									</div>
								</div>
							</article>

							<article class="mb-5">
								<div class="card bg-transparent border-0">
									<div class="card-body p-0 z-index-1">
										<a href="post.php" data-cursor-effect-hover="plus">
											<img class="card-img-top rounded-0 mb-2" src="img/blog/wide/blog-41.jpg" alt="Card Image">
										</a>
										<p class="text-uppercase text-color-default text-1 my-2">
											<time pubdate datetime="2023-01-10">10 Jan 2023</time> 
											<span class="opacity-3 d-inline-block px-2">|</span> 
											John Doe
										</p>
										<div class="card-body p-0">
											<h4 class="card-title text-5 font-weight-bold pb-1 mb-2"><a class="text-color-dark text-color-hover-primary text-decoration-none" href="post.php">How to Grow your Business</a></h4>
											<p class="card-text mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc viverra lorem , consectetur adipiscing elit...</p>
											<a href="post.php" class="read-more text-color-read-more font-weight-semibold mt-0 text-2">Tampilkan Lebih Banyak <i class="fas fa-angle-right position-relative top-1 ms-1"></i></a>
										</div>
									</div>
								</div>
							</article>

							<article class="mb-5">
								<div class="card bg-transparent border-0">
									<div class="card-body p-0 z-index-1">
										<a href="post.php" data-cursor-effect-hover="plus">
											<img class="card-img-top rounded-0 mb-2" src="img/blog/wide/blog-41.jpg" alt="Card Image">
										</a>
										<p class="text-uppercase text-color-default text-1 my-2">
											<time pubdate datetime="2023-01-10">10 Jan 2023</time> 
											<span class="opacity-3 d-inline-block px-2">|</span> 
											John Doe
										</p>
										<div class="card-body p-0">
											<h4 class="card-title text-5 font-weight-bold pb-1 mb-2"><a class="text-color-dark text-color-hover-primary text-decoration-none" href="post.php">How to Grow your Business</a></h4>
											<p class="card-text mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc viverra lorem , consectetur adipiscing elit...</p>
											<a href="post.php" class="read-more text-color-read-more font-weight-semibold mt-0 text-2">Tampilkan Lebih Banyak <i class="fas fa-angle-right position-relative top-1 ms-1"></i></a>
										</div>
									</div>
								</div>
							</article>

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
										<a href="#" class="text-color-default text-uppercase text-1 mb-0 d-block text-decoration-none">10 Jan 2023 <span class="opacity-3 d-inline-block px-2">|</span> John Doe</a>
										<a href="#" class="text-color-dark text-hover-primary font-weight-bold text-3 d-block pb-3 line-height-4">Lorem ipsum dolor sit amet</a>
										<a href="#" class="text-color-default text-uppercase text-1 mb-0 d-block text-decoration-none">10 Jan 2023 <span class="opacity-3 d-inline-block px-2">|</span> John Doe</a>
										<a href="#" class="text-color-dark text-hover-primary font-weight-bold text-3 d-block pb-3 line-height-4">Consectetur adipiscing elit</a>
										<a href="#" class="text-color-default text-uppercase text-1 mb-0 d-block text-decoration-none">10 Jan 2023 <span class="opacity-3 d-inline-block px-2">|</span> John Doe</a>
										<a href="#" class="text-color-dark text-hover-primary font-weight-bold text-3 d-block pb-3 line-height-4">Vivamus sollicitudin nibh luctus</a>
									</div>
								</div>
							</aside>
						</div>
					</div>

				</div>

			</div>
			<footer id="footer" class="position-relative bg-quaternary mt-0 border-top-0">	
				<?php include('module/footer.php'); ?>	
			</footer>
		</div>

		<!-- JS -->
		<?php include('module/js.php'); ?>
	</body>
</html>
