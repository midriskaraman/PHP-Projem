<?php
include 'class.database.php';

$db=new Database();




if($db->TekKayitSil('filmler','film_id',$_GET['id'])==1){
	header("Location: adminanasayfa.php?filmsilmebasari=true");
	exit();
}else{
	header("Location: adminanasayfa.php?filmsilmehata=true");
	exit();
}

