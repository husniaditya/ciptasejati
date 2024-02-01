<?php

require_once ("../module/connection/conn.php");

// Example usage with multiple CC addresses
$toAddress = '';
$toName = '';
$ccAddresses = [''];
$ccNames = [''];
$subject = '';
$body = '';

$result = sendEmail($toAddress, $toName, $ccAddresses, $ccNames, $subject, $body);
echo $result;
?>
