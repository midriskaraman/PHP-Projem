<?php
include 'class.database.php';

$db=new Database();






if($db->TekKayitSil('admin','admin_id',$_GET['id'])==1){
	header("Location: adminler.php?adminsilmebasari=true");
	exit();
}else{
	header("Location: adminler.php?adminsilmehata=true");
	exit();
}


