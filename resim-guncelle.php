<?php


include 'class.database.php';

$db=new Database();


$film_id=$_POST["film_id"];


$hatalar=array();

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
		$sorgu_alan='film_id';
		$sorgu_deger=$film_id;

		$alanlar=array("resim");
		$degerler=array($filename);
		
		$db->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);
		header("Location: adminanasayfa.php?filmguncellebasari=true");