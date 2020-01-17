<?php


	  
	class Database{
		
		private $VtHost       = "localhost";  // Veritabanı sql bağlantı adresi.
		private $VtKullanici  = "muhammeddb";  // Veritabanı kullanıcıadı.
		private $VtParola     = "muhammed123.";  // Veritabanı parolası.
		private $VtAd         = "film_data";  // Bağlanılacak veritabanı adı.
		
		private $Pdo;   // Pdo bağlantı nesnesi.
		private $Hata;	// Hata mesajları nesnesi.
		private $Sorgu; // Sql sorgu nesnesi.
		
		//Bu sınıftan nesne üretüldiğinde çalışacak öncül kodlar.
		public function __construct(){
			$dsn     = 'mysql:host=' . $this->VtHost . ';dbname=' . $this->VtAd . ';'; // PDO parametreleri.
			$ayarlar = array(
							PDO::ATTR_PERSISTENT => False,                // Kalıcı bağlantıyı kapattık.
							PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION // Hata mesajlarını döndürür.
							//PDO::ATTR_ERRMODE    => PDO::ERRMODE_SILENT // Hata mesajı döndürmez.
					   );
			try{
				$this->Pdo = new PDO($dsn, $this->VtKullanici, $this->VtParola, $ayarlar);
				$this->Pdo->exec("SET CHARACTER SET utf8");
			}catch(PDOexeption $e){
				$this->Hata = "Baglantı hatası : " . $e->getMessage();
			}
		}
		public function permalink($string)
		{
			$find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
			$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
			$string = strtolower(str_replace($find, $replace, $string));
			$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
			$string = trim(preg_replace('/\s+/', ' ', $string));
			$string = str_replace(' ', '-', $string);
			return $string;
		}
		public function toColName($string)
		{
			$find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
			$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
			$string = strtolower(str_replace($find, $replace, $string));
			$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
			$string = trim(preg_replace('/\s+/', ' ', $string));
			$string = str_replace(' ', '_', $string);
			return $string;
		}

		public function isTable($name)
		{
			$sql = "SELECT COUNT(TABLE_NAME) as toplamtablo FROM information_schema.tables WHERE TABLE_SCHEMA = '".$this->VtAd."' AND TABLE_NAME = '".$name."' ";
			$this->Sorgula($sql);
  			$sonuc = $this->TekCek();

 			if($sonuc["toplamtablo"]>0) return true;
 			else return false;
		}


		
		public function profilGetir($id){
			$this->id=$id;
			$dunyaveri=$this->Pdo->prepare('SELECT * FROM uyeler WHERE uye_id=?');
			$dunyaveri->execute(array($this->id));
			if($dunyaveri->rowCount()==0){
				return "yok";
			}else{
				return $dunyaveri->fetch(PDO::FETCH_ASSOC);
			}
		}
		public function profilGetirKad($id){
			$this->id=$id;
			$dunyaveri=$this->Pdo->prepare('SELECT * FROM uyeler WHERE uye_kullaniciadi=?');
			$dunyaveri->execute(array($this->id));
			if($dunyaveri->rowCount()==0){
				return "yok";
			}else{
				return $dunyaveri->fetch(PDO::FETCH_ASSOC);
			}
		}
		public function YorumListele($id){
			$this->id=$id;
			$dunyaveri=$this->Pdo->prepare('SELECT * FROM yorumlar WHERE yorum_konum=? ORDER BY yorum_id  ASC');
			$dunyaveri->execute(array($this->id));
			
			if($dunyaveri->rowCount()==0){
				return "yok";
			}else{
				return $dunyaveri->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		public function yorumSayisi($soruokunma,$soruid){
			$this->soruokunma=$soruokunma;
			$this->soruid=$soruid;
			$oku=$this->Pdo->prepare('UPDATE yazilar SET yazi_yorum=? WHERE yazi_id=?');
			$oku->execute(array($this->soruokunma, $this->soruid));
			
		}
		public function yorumSayisisoru($soruokunma,$soruid){
			$this->soruokunma=$soruokunma;
			$this->soruid=$soruid;
			$oku=$this->Pdo->prepare('UPDATE sorular SET soru_yorum=? WHERE soru_id=?');
			$oku->execute(array($this->soruokunma, $this->soruid));
			
		}
		
		public function yorumEkle($yorum,$yapan,$nereye){
			$this->yorum=$yorum;
			$this->yapan=$yapan;
			$this->nereye=$nereye;

			$sorgu=$this->Pdo->prepare('INSERT INTO yorumlar (yorum,yapan_id,yorum_konum,yorum_tarih) VALUES (?,?,?,?)');
			$sorgu->execute(array($this->yorum,$this->yapan,$this->nereye,date('Y:m:d H:i:s')));
			if(!$sorgu){
				return False;
			}else{
				return True;
			}

		}

		public function icerik($tmp){
			$this->tmp=$tmp;
			$sorgu=$this->Pdo->prepare('SELECT * FROM yazilar WHERE yazi_link=:link');
			$sorgu->bindValue(":link",$this->tmp,PDO::PARAM_STR);
			$sorgu->execute();
			return $sorgu->fetch(PDO::FETCH_ASSOC);
		}
		// Sorgula metodu gönderilen SQL sorgusunu hazır hale getirir.
		public function Sorgula($Sql){
			$this->Sorgu = $this->Pdo->prepare($Sql);
		}
		
		// Çalıştır metodu hazırlanan sorguyu çalıştırır.
		public function Calistir($parametre = null){
			return $this->Sorgu->execute($parametre);
		}
		
		// TekVeriCek metdu verilen tablo adından istenilen alana(kolon,sütun) göre verilen değeri sorgular.
		/* Geri Dönen Değer : @Dizi
		*  Parametreler     : $Tablo (Tablo Adı), $Alan (Sütun Adı), $Deger (Sütun Değeri)
		*/
		public function TekVeriCek($Tablo, $Alan, $Deger){
			$Sql = "SELECT * FROM " . $tablo . " WHERE " . $alan . " =?" ;
			$this->Sorgula($Sql);
			$this->Calistir(array($Deger));
			return $this->Sorgu->fetch(PDO::FETCH_ASSOC);
		}
		
		// Veritabanından tek kayıt çeker.
		public function TekCek(){
			$this->Calistir();
			return $this->Sorgu->fetch(PDO::FETCH_ASSOC);
		}
		
		// Veritabanından birden fazla satır çeker.
		public function TamCek(){
			$this->Calistir();
			return $this->Sorgu->fetchAll(PDO::FETCH_BOTH);
		}
		
		// İşlemler yapıldıktan sonra bağlatı için gçerlidir.
		public function Bind($Parametre, $deger, $Tip = null){
			if(is_null($Tip)){
				switch (true){
					case is_int($deger):
						$Tip = PDO::PARAM_INT;
						break;
					case is_bool($deger):
						$Tip = PDO::PARAM_BOOL;
						break;
					case is_null($deger):
						$Tip = PDO::PARAM_NULL;
						break;
					default:
						$Tip = PDO::PARAM_STR;
				}
			}
			$this->Sorgu->bindValue($Parametre, $deger, $Tip);
		}
		
		// Verilen tablo ismine ve alanlara göre tüm kayıtları çeker.
		public function TumKayitAl($Tablo, $Alan=null, $Deger = null){
			if( $Alan != null && $Deger != null ){
				$Sql = "SELECT * FROM " . $Tablo . " WHERE " . $Alan . "= " . $Deger ;
			}else{
				$Sql = "SELECT * FROM " . $Tablo ;
			}
			try{
				$this->Sorgula($Sql);
				$this->Calistir();
				return $this->Sorgu->fetchAll(PDO::FETCH_ASSOC);
			}catch(Exeption $e){
				die("TumKayitAl() fonsiyonunda sorgu hatası!");
			}
		}
		
		// Kayıt Ekleme(INSERT)
		public function KayitEkle($tablo, $alanlar=array(), $degerler = array()){
			$Sql1 = implode(',',$alanlar);
			$Sql2 = implode(',',array_fill(0, count($degerler),'?'));
			
			$Sql = 'INSERT INTO '.$tablo;
			$Sql .= '('.$Sql1.') ';
			$Sql .= 'VALUES ('.$Sql2.')';
			
			try{
				$this->Sorgula($Sql);
				$this->Calistir(array_values($degerler));
				return $this->SonEklenenID();
			}catch(Exeption $e){
				die("KayitEkle() fonksiyonunda sorgu hatası.");
				return 0;
			}
		}
		
		// Kayıt güncelleme(UPDATE)
		public function KayitGuncelle($tablo, $alanlar=array(), $degerler = array(), $sorgu_alan=NULL, $sorgu_deger=NULL){
			$set = '';
			for($i=0; $i<count($alanlar);$i++){
				$set .= "`$alanlar[$i]` = ?";
				if ($i!=count($alanlar)-1) $set.=",";
			}
			if ($sorgu_alan!=NULL && $sorgu_deger!=NULL)
				$sql  = "UPDATE $tablo SET ".$set." WHERE $sorgu_alan=?";
			else 
				$sql = "UPDATE $tablo SET ".$set;
			//echo $sql; 
			 
			try{
				$this->Sorgula($sql);
				if ($sorgu_alan!=NULL) {
				$degerler[] = $sorgu_deger;
				$this->Calistir($degerler);
				}
				else $this->Calistir($degerler);
				 
				return 1;
			}catch (Exception $e) {
				die("kayitGuncelle() fonksiyonunda sorgu hatasi.");
				return 0;
			}
		}
		
		//Verilen tablodan $alan isimli değişkene $deger parametresiyle gönderilen değeri siler.
		public function TekKayitSil($tablo, $alan, $deger){
			$sql = "DELETE FROM ".$tablo." WHERE ".$alan." = ?";
			try{
				$this->Sorgula($sql);
				$this->Calistir(array($deger));
				return 1;
			}catch(Exception $e){
				die("tekKayitSil() fonksiyonunda sorgu hatası");
				return 0; 
			}
		}
		
		//Verilen tablodaki tüm kayıtları siler.
		public function TumKayitSil($tablo){
			$sql = "TRUNCATE TABLE " . $tablo ;
			try{
				$this->Sorgula($sql);
				$this->Calistir(array($tablo));
				return 1;
			}catch(Exception $e){
				die("TumKayitSil() fonksiyonunda sorgu hatası.");
				return 0;
			}
		}
		
		//İşlem sonucunda etkilenen kayıt sayısı alınır
		public function satirSay(){
			return $this->sorgu->rowCount();
		}

		//Veritabnına ekleme yapıldığında veritabanı tarafından verilen son ID değerini döndürür.
		public function sonEklenenID(){
			return $this->Pdo->lastInsertId();
		}

		//TRANSACTION işlemini başlatır. Eğer birden fazla (toplu) sorgu çalıştıracaksanız 
		//ve bu sorguların hep beraber işletilmesi gerekiyorsa transaction kullanırsınız.
		public function islemeBasla(){
			return $this->Pdo->beginTransaction();
		}

		// Transaction işlemini onaylar(commit) eder.
		public function islemiBitir(){
			return $this->Pdo->commit();
		}

		//Sorgulardan birinde hata alınan transaction işlemini sonlandırır. 
		public function islemIptal(){
			return $this->Pdo->rollBack();
		}

		// gönderilen tarihin üstünden şimdiye kadar geçen süreyi dile döker.
		public function tarihOku($tarih){ 
			

			$now         =    date('Y:m:d H:i:s');

			$nowYear     =    substr($now, 0, 4);
			$nowMounth   =    substr($now, 5, 2);
			$nowDay      =    substr($now, 8, 2);
			$nowHour     =    substr($now, 11,2);
			$nowMinute   =    substr($now, 14,2);
			$nowSecond   =    substr($now, 17,2);


			$nowDate 	 = 	  mktime($nowHour,$nowMinute,$nowSecond,$nowMounth,$nowDay,$nowYear);


			$tarihYear   =    substr($tarih, 0, 4);
			$tarihMounth =    substr($tarih, 5, 2);
			$tarihDay    =    substr($tarih, 8, 2);
			$tarihHour   =    substr($tarih, 11,2);
			$tarihMinute =    substr($tarih, 14,2);
			$tarihSecond =    substr($tarih, 17,2);

			$dataDate 	 = 	  mktime($tarihHour,$tarihMinute,$tarihSecond,$tarihMounth,$tarihDay,$tarihYear);

			$fark        =    $nowDate-$dataDate;
			if($fark<60){
				return "Şimdi";
			}
			else if($fark>=60 && $fark<3600){
				$kalanDk=$fark/60;
				return floor($kalanDk) . " Dk";
			}else if($fark>=3600 && $fark<86400){
				$kalanSa=$fark/(60*60);
				return floor($kalanSa) . " Sa";
			}else if($fark>=86400){
				$kalanG=$fark/(24*60*60);
				return floor($kalanG). " Gn";
			}

			
			
		}
		public function turkceVarmi($gelen){   // a-z ye ve A-Z ye ve 0-9 'a kabul eder sadece
        	$this->gelen=$gelen;
        	for ($i=0; $i < strlen($gelen) ; $i++) { 
        		if(ord($gelen[$i])<48 || (ord($gelen[$i])>57 && ord($gelen[$i])<65) || (ord($gelen[$i])>90 && ord($gelen[$i])<97) || ord($gelen[$i])>122) {
        			return True;
        		}
        	}
        	return false;
        }
        public function yaziBegeniEkle($link)
        {
        	$this->link=$link;
        	$bulunacakID=$link;


        	$sql = "SELECT * FROM yazilar WHERE yazi_link=:aranankisi";
			$this->Sorgula($sql);
			$this->bind('aranankisi',$bulunacakID);
			$satir = $this->TekCek();
        	$eklenecek=$satir["yazi_begeni"]+1;
 
        	//ekleme
			$tablo='yazilar';
			$sorgu_alan='yazi_link';
			$sorgu_deger=$bulunacakID;

			$alanlar=array("yazi_begeni") ;
			$degerler=array($eklenecek) ;

			$this->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);

        }
        public function ilanOkunmaEkle($link)
        {
        	$this->link=$link;
        	$bulunacakID=$link;


        	$sql = "SELECT * FROM ilan WHERE link=:aranankisi";
			$this->Sorgula($sql);
			$this->bind('aranankisi',$bulunacakID);
			$satir = $this->TekCek();
        	$eklenecek=$satir["gosterim"]+1;
 
        	//ekleme
			$tablo='ilan';
			$sorgu_alan='link';
			$sorgu_deger=$bulunacakID;

			$alanlar=array("gosterim") ;
			$degerler=array($eklenecek) ;

			$this->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);

        }
        public function soruOkunmaEkle($link)
        {
        	$this->link=$link;
        	$bulunacakID=$link;


        	$sql = "SELECT * FROM sorular WHERE soru_link=:aranankisi";
			$this->Sorgula($sql);
			$this->bind('aranankisi',$bulunacakID);
			$satir = $this->TekCek();
        	$eklenecek=$satir["soru_okunma"]+1;
 
        	//ekleme
			$tablo='sorular';
			$sorgu_alan='soru_link';
			$sorgu_deger=$bulunacakID;

			$alanlar=array("soru_okunma") ;
			$degerler=array($eklenecek) ;

			$this->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);

        }
        public function soruBegeniEkle($link)
        {
        	$this->link=$link;
        	$bulunacakID=$link;


        	$sql = "SELECT * FROM sorular WHERE soru_link=:aranankisi";
			$this->Sorgula($sql);
			$this->bind('aranankisi',$bulunacakID);
			$satir = $this->TekCek();
        	$eklenecek=$satir["soru_begeni"]+1;
 
        	//ekleme
			$tablo='sorular';
			$sorgu_alan='soru_link';
			$sorgu_deger=$bulunacakID;

			$alanlar=array("soru_begeni") ;
			$degerler=array($eklenecek) ;

			$this->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);

        }

        public function enSonId($tab,$col)
		{	
			$sql= "SELECT max(".$col.") ensonid "."FROM ".$tab;
			
			$this->Sorgula($sql);
			$this->Calistir();
			$satir = $this->TekCek();
			foreach ($satir as $i) {
				$sonuc=$i;
			}
	
			return $sonuc;				

		}
		public function toplamSatir($tablo,$col,$deger)
		{	
			if($deger==NULL) $sql= "SELECT count(".$col.") toplam "."FROM ".$tablo;
			else $sql= "SELECT count(".$col.") toplam "."FROM ".$tablo." WHERE ".$col."='".$deger."'";
			
			
			$this->Sorgula($sql);
			$this->Calistir();
			$satir = $this->TekCek();
			foreach ($satir as $i) {
				$sonuc=$i;
			}
	
			return $sonuc;				

		}
		public function ilkResimAl($id)
		{
			$yol="uploads/resim/".$id;
            # Resimleri cek  
            $dizin = $yol; //Resminizin Bulundugu Yolu Yaziniz   
            $tutucu = opendir($dizin);  
            $resim_adres="";
            while($dosya = readdir($tutucu)){  
            if(is_file($dizin."/".$dosya))  
            $resim[] = $dosya;  
            }  
            closedir($tutucu);  
            $resim_adres=$dizin."/".$resim[0];
            
           	return $resim_adres;

		}
		public function alt_replace($string){
		   $search = array(
		      chr(0xC2) . chr(0xA0), // c2a0; Alt+255; Alt+0160; Alt+511; Alt+99999999;
		      chr(0xC2) . chr(0x90), // c290; Alt+0144
		      chr(0xC2) . chr(0x9D), // cd9d; Alt+0157
		      chr(0xC2) . chr(0x81), // c281; Alt+0129
		      chr(0xC2) . chr(0x8D), // c28d; Alt+0141
		      chr(0xC2) . chr(0x8F), // c28f; Alt+0143
		      chr(0xC2) . chr(0xAD), // cdad; Alt+0173
		      chr(0xAD)
		   );
		   $string = str_replace($search, '', $string);
		   return trim($string);
		}

		
		//dizi döner
		public  function regexParcala($deger,$suzgec)
		  {
		    preg_match_all($suzgec, $deger, $cikti);
		    return $cikti;
		  }


		public function ozellikGetir($baslik,$kategori="konut")
			{
			  $sql="SELECT ozellik FROM `ozellikler` WHERE ".$kategori."=1 AND ".$baslik."=1";
			  $this->Sorgula($sql);
			  $this->Calistir();
			  $satir = $this->TamCek();
			  return $satir;
			}

	

		
	}
	
	

    /////////////////////////////////////////////////////////
   ////                                                 ////
  ////   METODLARIN(FONKSİYONLARIN) ÖRNEK KULLANIMI    ////
 ////	                                              ////
/////////////////////////////////////////////////////////



	/*  
		// nesneyi dahil etme.

		$db = new sincap();
	*/


	/*  
		// Sorgula ile bir veriyi bulma örneği

		$bulunacakID = 1;
		$sql = "SELECT * FROM uyeler WHERE uye_id=:aranankisi";
		$db->Sorgula($sql);
		$db->bind('aranankisi',$bulunacakID);
		$satir = $db->TekCek();
		$adi = $satir['uye_isim'];
		$soyadi = $satir['uye_soyisim'];

		echo $adi . " " . $soyadi;
	 */
	 
	 
	/*  
		// TamCek metodu örneği.
	
		$bulunacakID = 0;
		$sql = "SELECT * FROM uyeler WHERE uye_yetki=:aranankisi";
		$db->Sorgula($sql);
		$db->bind('aranankisi',$bulunacakID);
		$satirlar = $db->TamCek();
		foreach($satirlar as $satir){
			 $adi = $satir['uye_isim'];
			$soyadi = $satir['uye_soyisim'];

			echo $adi . " " . $soyadi . "<br>";
		}
	*/

	 
	/*  
		// Kayıtekle metodu örneği (INSERT) .

		$tablo='uyeler';

		$alanlar = array("uye_isim","uye_soyisim","uye_parola");
		$degerler = array("Gülnaz","Savaşcı","123");
		$eklenenId=$db->KayitEkle($tablo, $alanlar, $degerler);
		echo "yeni eklenen uye id : " . $eklenenId . "<br>";
		$satirlar=$db->TumKayitAl('uyeler');
		foreach($satirlar as $satir){
			echo $satir['uye_isim'] . " " . $satir['uye_soyisim'] . "<br>";
		}
	*/


	/*
		// tekkayıtsil metodu örneği (DELETE) .

		$db->TekKayitSil('uyeler','uye_id','18');
		$sonuc = $db->TumKayitAl('uyeler');
		foreach($sonuc as $satir){
			echo $satir['uye_id'] . " " . $satir['uye_isim'] . " " . $satir['uye_soyisim'] . "<br>";
		}
	*/


	/*
		// Tumkayitsil örneği (TRANCATE) .

		$db->TumKayitSil('uyeler');
		$sonuc = $db->TumKayitAl('uyeler');
		foreach($sonuc as $satir){
			echo $satir['uye_id'] . " " . $satir['uye_isim'] . " " . $satir['uye_soyisim'] . "<br>";
		}
	*/


	/*
		// Kayıtgüncelle örneği (UPDATE) .
		
		$tablo='uyeler';
		$sorgu_alan='uye_id';
		$sorgu_deger='1';

		$alanlar=array("uye_isim","uye_soyisim") ;
		$degerler=array("Henry","Jones") ;

		$db->KayitGuncelle($tablo, $alanlar, $degerler, $sorgu_alan, $sorgu_deger);

		$sonuc = $db->TumKayitAl($tablo);

		foreach($sonuc as $satir){
			echo $satir['uye_id'] . " " . $satir['uye_isim'] . " " . $satir['uye_soyisim'] . "<br>";
		}
	*/





?>

