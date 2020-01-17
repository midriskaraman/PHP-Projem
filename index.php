<?php 
session_start();
include 'header.php';
?>


<?php 
include 'class.database.php';

$db=new Database();



$sql = "SELECT * FROM kategoriler";
		$db->Sorgula($sql);
		$kategoriler = $db->TamCek();
		
		
		
		$filmler=array();
		
		if(isset($_GET["kategori"]) && isset($_GET["admin_puan"]) && isset($_GET["yapim_yili"])){
			$sql = "select f.film_ad,f.yapim_yili,f.admin_puani,f.resim,k.kategori_ad,f.aciklama from filmler f left join kategoriler k on k.kategori_id=f.kategori_id where f.yapim_yili=:yapim_yili or f.admin_puani=:admin_puan or f.kategori_id=:kategori_id";
			$db->Sorgula($sql);
			$db->bind('yapim_yili',$_GET["yapim_yili"]);
			$db->bind('admin_puan',$_GET["admin_puan"]);
			$db->bind('kategori_id',$_GET["kategori"]);
			$filmler = $db->TamCek();
		}else{
			$sql = "select f.film_ad,f.yapim_yili,f.admin_puani,f.resim,k.kategori_ad,f.aciklama from filmler f left join kategoriler k on k.kategori_id=f.kategori_id ";
			$db->Sorgula($sql);
			$filmler = $db->TamCek();
		}
		
		
		
		
		
		
		

?>



<div class="container" >
		<div class="row"><hr></div>
	<div class="row" >
	
    	<div class="col-md-8" align="left"><img src="img/filmimigöster1.png" width="70%" />	</div>
  		
  		
  		<?php if(!isset($_SESSION["user"])){?>
  			<div class="col-md-4" align="right">
  			<form class="form-inline" method="post" action="giris.php">
  			<dıv class="row">
  				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
				  <input type="text" class="form-control" placeholder="Kullanıcı Adı" aria-describedby="basic-addon1" name="username">
				</div>
  			</dıv>
  			<dıv class="row">
  				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
				  <input type="password" class="form-control" placeholder="Parola" aria-describedby="basic-addon1" name="password">
				</div>
  			</dıv>
  			<dıv class="row">
  				 <button type="submit" class="btn btn-default">Giriş Yap</button>
  			</dıv>
			</form>
  		</div>
  		<?php } else{?>
  		Hoşgeldin, <?=$_SESSION["user"]["admin_kadi"]?> | <a href="adminanasayfa.php">Admin Sayfası</a> | <a href="cikis.php">Çıkış Yap</a>
  		<?php }?>
  		
  		
	</div>
	<hr>
	<?php if(isset($_GET["hata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong><?=$_GET["hata"]?></strong>
				</div>
			</div>
			<?php } ?>
	
	
	<form action="index.php" method="get">
	
	<div class="row">
		 <div class="col-md-4" align="left">
			<div class="row">
  			<div class="input-group input-group-lg">
  				<select class="form-control" name="kategori">
  				<option value="0">Film Kategorisi Seçiniz</option>
  					<?php foreach ($kategoriler as $kategori){?>
			  		
					<option value="<?=$kategori["kategori_id"]?>"><?=$kategori["kategori_ad"]?></option>
				
					<?php } ?>
				</select>
				</div>
			</div>
  		</div>
  	
  
  		<div class="col-md-4" align="center">

			<div class="row">
			<div class="input-group input-group-lg">
  				<select class="form-control" name="admin_puan">
  					<option value="0">Admin puanı Seçiniz</option>
 			 		<?php for($i=1;$i<=10;$i++){?>
 			 		<option value="<?=$i?>"><?=$i?></option>
 			 		<?php }?>
  					
				</select>
			</div>
			</div>
		</div>
		
		
	<div class="col-md-4" align="right">

		<div class="row">
		<div class="input-group input-group-lg">
  			<select class="form-control" name="yapim_yili">
  			<option value="0">Film Yapım Yılı</option>
  				<?php for($i=2017;$i>1950;$i--){?>
  				
  				<option value="<?=$i?>"><?=$i?></option>
  				<?php }?>
			</select>
		</div>
		</div>
				
		</div>
	
		
	</div>
	
	<br><br>
	
	<div class="row"> 
			
				<button type="submit" class="btn btn-primary btn-lg btn-block">Listele</button>				
			
		</div>
	<br>
	<hr>
	<br>
	</form>
	
	<?php  if(count($filmler)<=0){?>
	<div align="center">
	<h4 style="color:#ccc">İsteğinize uygun filmleri bulamadık. Lütfen farklı seçenekler deneyiniz.</h4>
	</div>
	
	
	 <?php } $i=1;foreach ($filmler as $film){?>
    	
    	<div class="row" id="box"> 
    	<div class="row"> 
    	<dıv class="col-md-4" >
    		<h4><b>  <?=($i)?>. <?=$film["film_ad"]?> </b> </h4> </dıv>
    	 </div>
    	
 
    	
    	<div class="row">
	    	<dıv class="col-md-4">
	    		<span class="label label-info"><?=$film["kategori_ad"]?></span> / 
	    		<!--  <img src="img/imdb.png" width="20px"/> --> 
	    		<span class="label label-warning">PUAN | <?=$film["admin_puani"]?></span> / 
	    		<span class="label label-default"><?=$film["yapim_yili"]?></span>
	    		
	    	</dıv><br> </br>
    	</div>
    	
    	
    		
    	<div class="row" >
	    	<dıv class="col-md-1">
	    	<?php 
			 		$resim="";
			 		if($film['resim']==null) $resim="img/empty.jpg";
			 		else $resim="uploads/".$film["resim"];
			 		?>
	    		 <img src="<?=$resim?>" class="img img-responsive" />
	    	</dıv>
	    	<dıv class="col-md-8">
	    		<?=$film["aciklama"]?>
	    	</dıv>
    	</div>
    		
    </div> <br> </br>
    		
    	<?php $i++;} ?>
	
	
    
    
  
		
</div>

   
<?php 

include 'footer.php';

?>