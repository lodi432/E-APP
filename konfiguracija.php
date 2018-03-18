<?php

session_start();


$putanjaApp = "/EcomApp/";
$naslovAPP="EComApp";
$appID="EAPP";


$brojRezultataPoStranici=7;
if($_SERVER["HTTP_HOST"]===""){
	$host="";
	$dbname="";
	$dbuser="";
	$dbpass="";
	$dev=false;
}else{
	$host="localhost";
	$dbname="ecom244";
	$dbuser="root";
	$dbpass="";
	$dev=true;
}


try{
	$veza = new PDO("mysql:host=" . $host . ";dbname=" . $dbname,$dbuser,$dbpass);
	$veza->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$veza->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8';");
	$veza->exec("SET NAMES 'utf8';");
}catch(PDOException $e){

	switch($e->getCode()){
		case 1049:
			header("location: " . $putanjaApp . "greske/kriviNazivBaze.html");
			exit;
			break;
		default:
			header("location: " . $putanjaApp . "greske/greska.php?code=" . $e->getCode());
			exit;
			break;
	}


}
