<?php
session_start();
include 'class.database.php';

$db=new Database();




$username=$_POST["username"];
$password=$_POST["password"];



$bulunacakID1 = $username;
$bulunacakID2 = $password;
		$sql = "SELECT * FROM admin WHERE admin_kadi=:kadi and parola=:parola";
		$db->Sorgula($sql);
		$db->bind('kadi',$bulunacakID1);
		$db->bind('parola',$bulunacakID2);
		$satir = $db->TekCek();
		
		
		
		if(count($satir)>0 && $satir!=null){
			$_SESSION["user"]=$satir;
			header("Location: index.php?state=giris");
			exit();
		}else{
			header("Location: index.php?hata=Bu bilgiler yanlış!");
			exit();
		}