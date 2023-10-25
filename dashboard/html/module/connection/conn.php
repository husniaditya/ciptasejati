<?php

	session_name("dashboard");
	// Start the session
	session_start();
	
	date_default_timezone_set("Asia/Jakarta");
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**Ciptasejati01');
	ini_set('upload_max_filesize', '10M');
		
	$YEAR=date("Y");
	$MONTH=date("m");

	$_SESSION["LOGINIDUS_WEDD"] = 'Administrator';
	$_SESSION["LOGINNAME_WEDD"] = 'Husni Aditya';
	$_SESSION["LOGINPP_WEDD"] = '';

	function GetQuery($query){
		$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**Ciptasejati01');
	
		$result = $db1->prepare("$query") or trigger_error("Error Info : ".$db1->errorInfo());
		$result->execute();
	
		return $result;
	}	

	function createKode($namaTabel,$namaKolom,$awalan,$jumlahAngka)
	{
		$db1 = new PDO('mysql:host=localhost;dbname=ciptasejati', 'admciptasejati', '**Ciptasejati01');
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
?>