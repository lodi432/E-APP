<?php include_once '../konfiguracija.php';


if(!isset($_GET["sifra"])){

		header("location: " . $putanjaAPP . "logout.php");

}else{

	$izraz=$veza->prepare("delete from brand where id=:sifra");
	$izraz->execute($_GET);
	header("location: brands.php");

}
