<?php
	// Start the session
	session_start();
	
	date_default_timezone_set("Asia/Jakarta");
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**ciptasejati');
	ini_set('upload_max_filesize', '10M');


	// $ssh_host = '158.140.167.180';
	// $ssh_username = 'lo';
	// $ssh_password = 'waru51';
	

	// $mysql_host = 'localhost';
	// $mysql_port = 3306;
	// $mysql_database = 'ciptasejati';
	// $mysql_username = 'lo';
	// $mysql_password = 'waru51';

	// // Create an SSH connection
	// $connection = ssh2_connect($ssh_host);

	// if (ssh2_auth_password($connection, $ssh_username, $ssh_password)) {
	// 	echo "SSH connection established.<br>";

	// 	// Create an SSH tunnel
	// 	$tunnel = ssh2_tunnel($connection, $mysql_host, $mysql_port);

	// 	if ($tunnel) {
	// 		echo "SSH tunnel established.<br>";

	// 		// Now, you can establish a PDO connection to the MySQL server through the SSH tunnel
	// 		try {
	// 			$pdo = new PDO("mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_database", $mysql_username, $mysql_password);
	// 			echo "PDO connection established through SSH tunnel.<br>";

	// 			// Perform database operations here

	// 		} catch (PDOException $e) {
	// 			die("PDO connection failed: " . $e->getMessage());
	// 		}
	// 	} else {
	// 		die("SSH tunnel connection failed.");
	// 	}
	// } else {
	// 	die("SSH authentication failed.");
	// }
		
	$YEAR=date("Y");
	$MONTH=date("m");


	function GetQuery($query){
		$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**ciptasejati');
	
		$result = $db1->prepare("$query") or trigger_error("Error Info : ".$db1->errorInfo());
		$result->execute();
	
		return $result;
	}	

	function createKode($namaTabel,$namaKolom,$awalan,$jumlahAngka)
	{
		$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**ciptasejati');
		$angkaAkhir = 0;
		
		$stmt = $db1->query("select max(right($namaKolom,$jumlahAngka)) as akhir from $namaTabel");
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			if(isset($row["akhir"]))
			{
				$angkaAkhir = intval($row["akhir"]);
			}
		}
		$angkaAkhir = $angkaAkhir + 1;
		return $awalan.substr("00000000".$angkaAkhir,-1*$jumlahAngka);

	}

	function autoInc($namaTabel,$namaKolom,$jumlahAngka)
	{
		$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**ciptasejati');
		$angkaAkhir = 0;
		
		$stmt = $db1->query("select max(right($namaKolom,$jumlahAngka)) as akhir from $namaTabel");
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			if(isset($row["akhir"]))
			{
				$angkaAkhir = intval($row["akhir"]);
			}
		}
		$angkaAkhir = $angkaAkhir + 1;
		return substr("00000000".$angkaAkhir,-1*$jumlahAngka);

	}
?>