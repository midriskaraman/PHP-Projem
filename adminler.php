<?php 

session_start();


if(!isset($_SESSION["user"])) die("Erişim engellendi! Giriş yapmanız gerekiyor.");

if(isset($_SESSION["user"]["admin_level"])){
	if($_SESSION["user"]["admin_level"]!=2) die("Buraya erişim yetkiniz yok.");
}

include 'header.php';



include 'class.database.php';


$db=new Database();

$adminler=array();

if(isset($_GET["search"])){
	
		$sql = "SELECT * FROM admin WHERE CONCAT_WS(' ',email,admin_kadi) LIKE '%".$_GET["search"]."%' order by admin_id desc ";
		$db->Sorgula($sql);
		$satirlar = $db->TamCek();
		$adminler=$satirlar;
		
}else{
		$sql = "SELECT * FROM admin order by admin_id desc ";
		$db->Sorgula($sql);
		$satirlar = $db->TamCek();
		$adminler=$satirlar;
}











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
        <li><a href="adminler.php">Adminler <span class="sr-only">(current)</span></a></li>
         <li><a href="cikis.php">Çıkış Yap <span class="sr-only">(current)</span></a></li>
        
        
      </ul>
      
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
		
		
	</div>
		<div class="row">
			<div class="col-lg-4">
				<form action="adminler.php" method="get">
			    <div class="input-group">
			      <input type="text" class="form-control" name="search" placeholder="Admin email giriniz">
			      <span class="input-group-btn">
			        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
			      </span>
			    </div><!-- /input-group -->
			    </form>
			</div><!-- /.col-lg-6 -->
			<div class="col-md-2">
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ekle">Admin Ekle</button>
			</div>
			
			
			
			<?php if(isset($_GET["admineklebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Admin başarıyla eklendi.
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["admineklemehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <?=$_GET["admineklemehata"]?>
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["adminsilmebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Admin başarıyla sillindi!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["adminsilmehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Admin silinirken bir hata oluştu!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["adminguncellebasari"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Admin başarıyla güncellendi!
				</div>
			</div>
			<?php } ?>
			
			<?php if(isset($_GET["adminguncellehata"])){ ?>
			<div class="col-md-12">
			<br>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Admin silinirken bir hata oluştu!
				</div>
			</div>
			<?php } ?>
			
			
			
			<br><br><hr><br>
			<table class="table table-hover">
			 	<thead>
			 		<th>Admin İd</th>
			 		<th>Admin Adı</th>
			 		<th>Email</th>
			 		<th>Parola</th>
			 		
			 		<th>İşlemler</th>
			 	</thead>
			 	<tbody>
			 	
			 		<?php foreach ($adminler as $admin){ ?>
			 		<tr>
			 			<td><?=$admin['admin_id']?></td>
			 			<td><?=$admin['admin_kadi']?></td>
			 			<td><?=$admin['email']?></td>
			 			<td><?=$admin['parola']?></td>
			 			<td>
			 				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#guncelle<?=$admin['admin_id']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
			 				<a href="admin-sil.php?id=<?=$admin['admin_id']?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
			 			</td>
			 			
			 			
			 			
			 			<!-- Admin Güncelle Modal -->
						<div class="modal fade" id="guncelle<?=$admin['admin_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">Admin Güncelleme</h4>
						      </div>
						      <div class="modal-body">
						
								<form action="admin-guncelle.php" method="post">
								
									<input type="text" name="id" value="<?=$admin['admin_id']?>" hidden>
								  <div class="form-group">
								    <label for="exampleInputEmail1">Admin Adı</label>
								    <input type="text" class="form-control" id="exampleInputEmail1" name="kadi" placeholder="Admin adı" value="<?=$admin['admin_kadi']?>">
								  </div>
								 
								  <div class="form-group">
								    <label for="exampleInputPassword1">email</label>
								    <input type="text" class="form-control" id="exampleInputPassword1" name="email" placeholder="example@gmail.com" value="<?=$admin['email']?>">
								  </div>
								  <div class="form-group">
								    <label for="exampleInputPassword1">Parola</label>
								    <input type="text" class="form-control" id="exampleInputPassword1" name="parola" placeholder="Parola" value="<?=$admin['parola']?>">
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
			 			
			 		</tr>
			 		<?php } ?>
			 	</tbody>
			</table>
		</div>
</div>





<!-- Admin Ekle Modal -->
<div class="modal fade" id="ekle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Yeni Admin Ekle</h4>
      </div>
      <div class="modal-body">

		<form action="admin-ekle.php" method="post">
		  <div class="form-group">
		    <label for="exampleInputEmail1">Admin Adı</label>
		    <input type="text" class="form-control" name="kullaniciadi" id="exampleInputEmail1" placeholder="admin adı">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">email</label>
		    <input type="email" class="form-control" name="email" id="exampleInputPassword1" placeholder="example@gmail.com">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Parola</label>
		    <input type="text" class="form-control" name="parola" id="exampleInputPassword1" placeholder="Parola">
		  </div>
		  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Admin Ekle</button>
        </form>
      </div>
    </div>
  </div>
</div>






<?php 

include 'footer.php';

?>