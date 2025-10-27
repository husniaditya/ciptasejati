<?php
require_once (__DIR__ . "/module/connection/conn.php");

// Perform logout
unset($_SESSION["LOGINIDUS_CS"]);
session_destroy();   // destroy session data in storage

// Detect AJAX request
$isAjax = (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
	|| (isset($_POST['ajax']) && $_POST['ajax'] == '1')
	|| (isset($_GET['ajax']) && $_GET['ajax'] == '1')
);

if ($isAjax) {
	header('Content-Type: application/json');
	echo json_encode(['status' => 'ok', 'message' => 'Anda berhasil logout', 'redirect' => 'index.php']);
	exit;
}

?><script>alert('Anda berhasil logout');</script><?php
?><script>document.location.href='index.php';</script><?php
?>