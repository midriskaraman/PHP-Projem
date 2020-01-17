<?php

include 'class.database.php';

$db=new Database();


$adminid=$_POST["id"];


$hatalar=array();


		$sql = "SELECT * FROM admin where admin_id!=:arananad and admin_kadi=:kadi";
		$db->Sorgula($sql);
		$db->bind('arananad',$adminid);
		$db->bind('kadi',$_POST["kadi"]);
		$satir = $db->TekCek();

		

		
		
		if(count($satir)>0 && $satir!=NULL){
			$hatalar[]="Bu kullanıcı adı mevcut!";
		}
		
		if($_POST["kadi"]==NULL){
			$hatalar[]="Kullanıcı adı boş bırakılamaz!";
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
		$sorgu_alan='admin_id';
		$sorgu_deger=$adminid;

		$alanlar=array("admin_kadi","parola","email") ;
		$degerler=array($_POST["kadi"],$_POST["parola"],$_POST["email"]) ;

		if($db->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger)){
			header("Location: adminler.php?adminguncellebasari=true");
			exit();
		}else{
			header("Location: adminler.php?adminguncellehata=true");
			exit();
		}
