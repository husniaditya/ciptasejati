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

			<?php include 'module/component/v_hubungi.php'; ?>
			
			<footer id="footer" class="position-relative bg-quaternary mt-0 border-top-0">		
				<?php include('module/footer.php'); ?>	
			</footer>
		</div>

		<!-- JS -->
		<?php include('module/js.php'); ?>
	</body>
</html>
