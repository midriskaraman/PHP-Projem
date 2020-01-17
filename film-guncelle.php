<?php

include 'class.database.php';

$db=new Database();



$filmadi=$_POST["filmadi"];
$yapimyili=$_POST["yapimyili"];
$adminpuani=$_POST["adminpuani"];
$kategori_id=$_POST["kategori_id"];
$aciklama=$_POST["aciklama"];

$film_id=$_POST["film_id"];


$hatalar=array();


$bulunacakID = $_POST["filmadi"];
		$sql = "SELECT * FROM filmler WHERE film_ad=:arananad";
		$db->Sorgula($sql);
		$db->bind('arananad',$bulunacakID);
		$satir = $db->TekCek();


		
$bulunacakID = $_POST["yapimyili"];
		$sql = "SELECT * FROM filmler WHERE yapim_yili=:arananad";
		$db->Sorgula($sql);
		$db->bind('arananad',$bulunacakID);
		$satir = $db->TekCek();
		
$bulunacakID = $_POST["adminpuani"];
		$sql = "SELECT * FROM filmler WHERE admin_puani=:arananad";
		$db->Sorgula($sql);
		$db->bind('arananad',$bulunacakID);
		$satir = $db->TekCek();

$bulunacakID = $_POST["kategori_id"];
		$sql = "SELECT * FROM kategoriler WHERE kategori_id=:aranankisi";
		$db->Sorgula($sql);
		$db->bind('aranankisi',$bulunacakID);
		$satirlar = $db->TamCek();
		
		
		

		if($_POST["filmadi"]==NULL){
			$hatalar[]="Film adı boş bırakılamaz!";
		}
		
				
		if($_POST["yapimyili"]==NULL){
			$hatalar[]="yapım yılı boş bırakılamaz!";
		}
		
		if($_POST["adminpuani"]==NULL){
			$hatalar[]="admin puanı boş bırakılamaz!";
		}
		
		if($aciklama==NULL){
			$hatalar[]="Açıklama boş bırakılamaz!";
		}
		
		

		
		if(count($hatalar)>0){
			$hata="";
			for($i=0;$i<=count($hatalar);$i++){
				$hata.=$hatalar[$i]."<br>";
			}
			header("Location: adminanasayfa.php?filmeklemehata=".$hata);
			exit();
		}
		
		
		
		
		
		
		
$tablo='filmler';
		$sorgu_alan='film_id';
		$sorgu_deger=$film_id;

		$alanlar=array("film_ad","yapim_yili","admin_puani","aciklama","kategori_id") ;
		$degerler=array($_POST["filmadi"],$_POST["yapimyili"],$_POST["adminpuani"],$aciklama,$kategori_id) ;

		if($db->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger)){
			header("Location: adminanasayfa.php?filmguncellebasari=true");
			exit();
		}else{
			header("Location: adminanasayfa.php?filmguncellehata=true");
			exit();
		}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		