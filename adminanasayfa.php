<?php 
session_start();


if(!isset($_SESSION["user"])) die("Erişim engellendi! Giriş yapmanız gerekiyor.");





include 'header.php';


include 'class.database.php';


$db=new Database();

$adminanasayfa=array();

if(isset($_GET["search"])){
	
		$sql = "SELECT * FROM filmler f left join kategoriler k on f.kategori_id=k.kategori_id WHERE film_ad LIKE '%".$_GET["search"]."%' order by film_id desc ";
		$db->Sorgula($sql);
		$satirlar = $db->TamCek();
		$adminanasayfa=$satirlar;
		
}else{
		$sql = "SELECT * FROM filmler f left join kategoriler k on f.kategori_id=k.kategori_id order by film_id desc ";
		$db->Sorgula($sql);
		$satirlar = $db->TamCek();
		$adminanasayfa=$satirlar;
}



		$sql = "SELECT * FROM kategoriler";
		$db->Sorgula($sql);
		$kategoriler = $db->TamCek();
		
		



?>

<div class="container" >

		<div class="row"><hr></div>
		<div class="row" >
	    	<div class="col-md-8" align="left"><img src="img/filmimigöster1.png" width="70%" />	</div>
		</div>
		<br><hr><br>
	<div class="row">
		<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Ana Sayfa</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="adminanasayfa.php">Filmler <span class="sr-only">(current)</span></a></li>
        <?php 
        if(isset($_SESSION["user"]["admin_level"])){
			if($_SESSION["user"]["admin_level"]==2) echo '<li><a href="adminler.php">Adminler <span class="sr-only">(current)</span></a></li>';
		}
        ?>
        
        <li><a href="cikis.php">Çıkış Yap <span class="sr-only">(current)</span></a></li>
        
        
      </ul>
      
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
		
		
	</div>
		
		<div class="row">
			<div class="col-lg-4">
			<form action="adminanasayfa.php" method="get">
			    <div class="input-group">
			      <input type="text" class="form-control" name="search" placeholder="Film adı giriniz">
			      <span class="input-group-btn">
			        <button type="sumbit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
			      </span>
			    </div><!-- /input-group -->
			</form>
			</div><!-- /.col-lg-6 -->
			<div class="col-md-2">
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ekle">Film Ekle</button>
			</div>
			
		
				<?php if(isset($_GET["filmeklebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  film başarıyla eklendi.
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["filmeklemehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <?=$_GET["filmeklemehata"]?>
				</div>
			</div>
			<?php } ?>
			
			
			<?php if(isset($_GET["filmsilmebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  film başarıyla sillindi!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["filmsilmehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  film silinirken bir hata oluştu!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["filmguncellebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Film başarıyla güncellendi!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["filmguncellehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Film güncellenirken bir hata oluştu!
				</div>
			</div>
			<?php } ?>
			
			

			<br><br><hr><br>
			<table class="table table-hover">
			 	<thead>
			 		<th>Film Resim</th>
			 		<th>Film Adı</th>
			 		<th>Kategorisi</th>
			 		<th>Yapım Yılı</th>
			 		<th>Admin Puan</th>
			 		<th>Açıklama</th>
			 		<th>İşlemler</th>
			 	</thead>
			 	<tbody>
			 		<?php foreach ($adminanasayfa as $filmler){ ?>
			 		<tr>
			 		<?php 
			 		$resim="";
			 		if($filmler['resim']==null) $resim="img/empty.jpg";
			 		else $resim="uploads/".$filmler["resim"];
			 		?>
			 			<td><img src="<?=$resim?>" class="img img-responsive" width="100px"/></td>
			 			<td><?=$filmler['film_ad']?></td>
			 			<td><?=$filmler['kategori_ad']?></td>
			 			<td><?=$filmler['yapim_yili']?></td>
			 			<td><?=$filmler['admin_puani']?></td>
			 			<td><?=$filmler['aciklama']?></td>
			 			<td>
			 				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#resimguncelle<?=$filmler['film_id']?>"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></button>
			 				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#guncelle<?=$filmler['film_id']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
			 				<a href="film-sil.php?id=<?=$filmler['film_id']?>" class="btn btn-danger" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
			 			</td>
			 		</tr>
			 		
			 		<!-- Güncelle Modal -->
<div class="modal fade" id="guncelle<?=$filmler['film_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Film Bilgilerini Güncelle</h4>
      </div>
      <div class="modal-body">

		<form action="film-guncelle.php" method="post">
		<input type="text" name="film_id" value="<?=$filmler["film_id"]?>" hidden>
		  <div class="form-group">
		    <label for="exampleInputEmail1">Film Adı</label>
		    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Film adı" value="<?=$filmler['film_ad']?>" name="filmadi">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Film Kategori</label>
			<select class="form-control"   name="kategori_id">
			  <?php foreach ($kategoriler as $kategori){?>
			  	
				<option value="<?=$kategori["kategori_id"]?>"><?=$kategori["kategori_ad"]?></option>
				
				<?php } ?>

			</select>
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Yapım Yılı</label>
		    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Yapım yılı" value="<?=$filmler['yapim_yili']?>" name="yapimyili">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Admin Puan</label>
		    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Admin puan" value="<?=$filmler['admin_puani']?>" name="adminpuani">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Açıklama</label>
		    <textarea class="form-control" rows="3" name="aciklama"><?=$filmler["aciklama"]?></textarea>
		  </div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- resim Modal -->
<div class="modal fade" id="resimguncelle<?=$filmler['film_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Yeni Film Ekle</h4>
      </div>
      <form action="resim-guncelle.php" enctype="multipart/form-data" method="post">
       <input type="text" name="film_id" value="<?=$filmler["film_id"]?>" hidden>
      <div class="modal-body">
      	<img src="<?=$resim?>" width="100px" class="img img-responsive" />
		<div class="form-group">
		    <label for="exampleInputFile">Resim</label>
		    <input type="file" id="exampleInputFile" name="resim">
		    <p class="help-block">Film resmini seçiniz.</p>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Resmi Güncelle</button>
        </form>
      </div>
    </div>
  </div>
</div>
			 		
			 		
			 		
<?php } ?>


<!-- Ekle Modal -->
<div class="modal fade" id="ekle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Yeni Film Ekle</h4>
      </div>
      <div class="modal-body">

		<form method="post" action="filmekle.php" enctype="multipart/form-data">
		  <div class="form-group">
		    <label for="exampleInputEmail1">Film Adı</label>
		    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Film adı" name="filmadi">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Film Kategori</label>
			<select class="form-control" name="filmkategori">
			 <?php foreach ($kategoriler as $kategori){?>
			  	
				<option value="<?=$kategori["kategori_id"]?>"><?=$kategori["kategori_ad"]?></option>
				
				<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Yapım Yılı</label>
		    <input type="year" class="form-control" id="exampleInputPassword1" placeholder="Yapım yılı" name="yapimyili">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Admin Puan</label>
		    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Admin puan" name="adminpuani">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Açıklama</label>
		    <textarea class="form-control" rows="3" name="aciklama"></textarea>
		  </div>
		  <div class="form-group">
		    <label for="exampleInputFile">Resim</label>
		    <input type="file" id="exampleInputFile" name="resim">
		    <p class="help-block">Film resmini seçiniz.</p>
		  </div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Film Ekle</button>
        </form>
      </div>
    </div>
  </div>
</div>
			 		
			 		
			 	</tbody>
			</table>
		</div>
</div>







<?php 

include 'footer.php';

?>