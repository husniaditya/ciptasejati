<?php

require_once ("../module/connection/conn.php");

// Example usage with multiple CC addresses
$toAddress = 'adityahusni90@gmail.com';
$toName = 'Husni Aditya';
$ccAddresses = ['adityahusni90@yahoo.com'];
$ccNames = ['Husni Aditya'];
$subject = 'Persetujuan Mutasi Anggota ';
$body = 'Mutasi a.n Slamet dari cabang Kotawaringin Timur';

$result = sendEmail($toAddress, $toName, $ccAddresses, $ccNames, $subject, $body);
echo $result;
?>
