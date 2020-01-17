<?php

ini_set('display_errors', 1);



include 'class.database.php';

$db=new Database();


$filmadi=$_POST["filmadi"];
$filmkategori=$_POST["filmkategori"];
$yapimyili=$_POST["yapimyili"];
$adminpuani=$_POST["adminpuani"];

$aciklama=$_POST["aciklama"];

	
$hatalar=array();

/*
   $bulunacakID = $filmadi;
			$sql = "SELECT * FROM filmler WHERE film_ad=:arananad";
			$db->Sorgula($sql);
			$db->bind('arananad',$bulunacakID);
			$satir = $db->TekCek();
		
		
*/		

		
		
		
		/*

			$bulunacakID = $yapimyili;
					$sql = "SELECT * FROM filmler WHERE yapım_yili=:arananad";
					$db->Sorgula($sql);
					$db->bind('arananad',$bulunacakID);
					$satir = $db->TekCek();
					
			$bulunacakID = $adminpuani;
					$sql = "SELECT * FROM filmler WHERE admin_puani=:arananad";
					$db->Sorgula($sql);
					$db->bind('arananad',$bulunacakID);
					$satir = $db->TekCek();
					
	*/
		if($filmadi==NULL){
			$hatalar[]="film adı boş bırakılamaz!";
		}
		if($yapimyili==NULL){
			$hatalar[]="yapım yılı boş bırakılamaz!";
		}
		if($adminpuani==NULL){
			$hatalar[]="admin puanı boş bırakılamaz!";
		}
		if($aciklama==NULL){
			$hatalar[]="Açıklama boş bırakılamaz!";
		}
		
		
		
		$filename=time();
		
		
	
			
			
			if(isset($_FILES['resim'])){
				$name = $_FILES["resim"]["name"];
				$ext = end((explode(".", $name))); # extra () to prevent notice
				$filename=$filename.".".$ext;
			      $errors= array();
			      $file_size =$_FILES['resim']['size'];
			      $file_tmp =$_FILES['resim']['tmp_name'];
			      $file_type=$_FILES['resim']['type'];
			      
			      $expensions= array("jpeg","jpg","png");
			      
			      if(in_array($ext,$expensions)=== false){
			         $hatalar[]="Uzantı desteklenmiyor, Lütfen şu uzantılarda bir resim seçiniz: JPEG yada PNG";
			      }
			      
			      if($file_size > 2097152){
			         $hatalar[]='Dosya boyutu 2MB yi geçmemeli.';
			      }
			      
			      if(empty($hatalar)==true){
			         move_uploaded_file($file_tmp,"uploads/".$filename);
			         
			      }else{
			         
			      }
			   }
					
		
		if(count($hatalar)>0){
			$hata="";
			for($i=0;$i<=count($hatalar);$i++){
				$hata.=$hatalar[$i]."<br>";
			}
			header("Location:adminanasayfa.php?filmeklemehata=".$hata);
			exit();
		}
		
		
		
		
		
		
		
		
		
		
		
		$tablo='filmler';

		$alanlar = array("film_ad","yapim_yili","admin_puani","kategori_id","resim","aciklama");
		$degerler = array($filmadi,$yapimyili,$adminpuani,$filmkategori,$filename,$aciklama);
		$eklenenId=$db->KayitEkle($tablo, $alanlar, $degerler);
		
		
		if($eklenenId>0){
			header("Location: adminanasayfa.php?filmeklebasari=true");
			exit();
		
		}
		
		
		
		
		