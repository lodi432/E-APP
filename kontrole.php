<?php

if(trim($_POST["naziv"])===""){
		$greska["naziv"]="Naziv obavezno";
	}
	
	if(strlen(trim($_POST["naziv"]))>50){
		$greska["naziv"]="Naziv predugačak, smanjite ga ispod 50 znakova";
	}
	
	if(!isset($_POST["sifra"])){
		$_POST["sifra"]=0;
	}
	$izraz=$veza->prepare("select sifra from smjer where naziv=:naziv and sifra!=:sifra");
	$izraz->execute(array("naziv"=>$_POST["naziv"], "sifra"=>$_POST["sifra"]));
	$sifra = $izraz->fetchColumn();
	if($sifra>0){
		$greska["naziv"]="Naziv postoji u bazi, odabrati drugi";
	}
	
	
	if(trim($_POST["cijena"])===""){
		$greska["cijena"]="Cijena obavezno";
	}
	
	if(trim($_POST["upisnina"])===""){
		$greska["upisnina"]="Upisnina obavezno";
	}
	
	if(trim($_POST["brojsati"])===""){
		$greska["brojsati"]="Broj sati obavezno";
	}