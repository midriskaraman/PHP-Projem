<?php
include 'class.database.php';

$db=new Database();



$kullaniciadi=$_POST["kullaniciadi"];
$email=$_POST["email"];
$parola=$_POST["parola"];


$hatalar=array();

		

		$bulunacakID = $kullaniciadi;
		$sql = "SELECT * FROM admin WHERE admin_kadi=:arananad";
		$db->Sorgula($sql);
		$db->bind('arananad',$bulunacakID);
		$satir = $db->TekCek();
	
		if(count($satir)>0 && $satir!=NULL){
			$hatalar[]="Bu kullanıcı adı mevcut!";
		}
		
		if($kullaniciadi==NULL){
			$hatalar[]="Kullanıcı adı boş bırakılamaz!";
		}
		if($email==NULL){
			$hatalar[]="Email boş bırakılamaz!";
		}
		if($_POST["parola"]==NULL){
			$hatalar[]="parola boş bırakılamaz!";
		}

			
		if(count($hatalar)>0){
			$hata="";
			for($i=0;$i<=count($hatalar);$i++){
				$hata.=$hatalar[$i]."<br>";
			}
			header("Location: adminler.php?admineklemehata=".$hata);
			exit();
		}
		
		
		$tablo='admin';

		$alanlar = array("admin_kadi","parola","email","admin_level");
		$degerler = array($kullaniciadi,$parola,$email,"1");
		$eklenenId=$db->KayitEkle($tablo, $alanlar, $degerler);
		
		if($eklenenId>0){
			header("Location: adminler.php?admineklebasari=true");
			exit();
		
		}
		