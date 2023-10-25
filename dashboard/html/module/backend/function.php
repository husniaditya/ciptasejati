<?php


function GetQuery($query){
    $db1 = new PDO('mysql:host=localhost;dbname=wedding', 'mis', 'Kabelangka8');

    $result = $db1->prepare("$query") or trigger_error("Error Info : ".$db1->errorInfo()); 
    $result->execute();

    return $result;
}	

?>