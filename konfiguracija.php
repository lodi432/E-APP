<?php

session_start();


require_once $_SERVER['DOCUMENT_ROOT'].'/EcomApp/config.php';
require_once BASEURL.'helpers/helpers.php';



//$putanjaAPP = "http://localhost/FoundationFlex";
//$putanjaApp = "/projects/FoundationFlex/";

$putanjaApp = "/EcomApp/";
$naslovAPP="RIIS";
$appID="EDUNOVAAPP";


$brojRezultataPoStranici=7;
if($_SERVER["HTTP_HOST"]==="edunovanastava.byethost33.com"){
	$host="sql301.byethost18.com";
	$dbname="b18_21047707_pp16";
	$dbuser="b18_21047707";
	$dbpass="Edunova123";
	$dev=false;
}else{
	$host="localhost";
	$dbname="ecom244";
	$dbuser="root";
	$dbpass="kontrolf1";
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
			header("location: " . $putanjaAPP . "greske/kriviNazivBaze.html");
			exit;
			break;
		default:
			header("location: " . $putanjaAPP . "greske/greska.php?code=" . $e->getCode());
			exit;
			break;
	}


}
