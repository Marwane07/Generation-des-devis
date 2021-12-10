<?php 

session_start();
$_SESSION['userId'] = 1;
require_once 'db_connect.php';

// echo $_SESSION['userId'];

if(!$_SESSION['userId']) {
	header('location: http://localhost/stock/index.php');	
} 



?>