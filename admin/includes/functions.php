<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  

function adminMenu() {
	?>
<div id="cssmenu">
<ul>
<li><a href="<?php echo SITEURL;?>"><span>Anasayfa</span></a></li>
<li class="has-sub"><a href="#">Modül</a>
		<ul>
		<li><a href="index.php?option=admin&bolum=akis"><span>Akış Yönetimi</span></a></li>
		<li class="has-sub"><a href="#"><span>Albüm Yönetimi</span></a>
			<ul>
			<li><a href="index.php?option=admin&bolum=album&task=album"><span>Albüm Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=album&task=images"><span>Resim Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=album&task=recount"><span>Tekrar Say</span></a></li>
			</ul>
		</li>
		<li class="has-sub"><a href="#"><span>Grup Yönetimi</span></a>
			<ul>
			<li><a href="index.php?option=admin&bolum=group&task=group"><span>Grup Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=group&task=messages"><span>Mesaj Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=group&task=recount"><span>Tekrar Say</span></a></li>
			</ul>
		</li>
		<li class="has-sub"><a href="#"><span>Forum Yönetimi</span></a>
			<ul>
			<li><a href="index.php?option=admin&bolum=forum&task=categories"><span>Kategori Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=forum&task=boards"><span>Board Yönetimi</span></a></li>
			<li><a href="index.php?option=admin&bolum=forum&task=recount"><span>Tekrar Say</span></a></li>
			</ul>
		</li>
		</ul>
	</li>

<li class="has-sub"><a href="#">Üyelik</a>
	<ul>
		<li><a href="index.php?option=admin&bolum=user"><span>Üye Yönetimi</span></a></li>
		<li><a href="index.php?option=admin&bolum=sehir"><span>Şehir Yönetimi</span></a></li>
		<li><a href="index.php?option=admin&bolum=brans"><span>Branş Yönetimi</span></a></li>
	</ul>
</li>
<li class="has-sub"><a href="#">Sistem</a>
	<ul>
		<li><a href="index.php?option=admin&bolum=ayarlar"><span>Yapılandırma Dosyası</span></a></li>
		<li><a href="index.php?option=admin&bolum=config"><span>Site Ayarları</span></a></li>
		<li><a href="index.php?option=admin&bolum=duyuru"><span>Duyuru Yönetimi</span></a></li>
		<li><a href="index.php?option=admin&bolum=db"><span>Veritabanı Yönetimi</span></a></li>
		<li><a href="index.php?option=admin&bolum=bildirim"><span>Geri Bildirimler</span></a></li>
		<?php if (STATS) {?>
		<li class="has-sub"><a href="#"><span>İstatistikler</span></a>
			<ul>
			<li><a href="index.php?option=admin&bolum=stats"><span>Kontrol Paneli</span></a></li>
			<li><a href="index.php?option=admin&bolum=stats&task=acq"><span>Toplama</span></a></li>
			<li><a href="index.php?option=admin&bolum=stats&task=blocklist"><span>Blok Listesi</span></a></li>
			<li><a href="index.php?option=admin&bolum=stats&task=counts"><span>Sayaç</span></a></li>
			</ul>
		</li>
<?php } ?>
	</ul>
</li>
<li class="has-sub"><a href="#">Görünüm</a>
	<ul>
		<li><a href="index.php?option=admin&bolum=menu"><span>Menü Yönetimi</span></a></li>
		<li class="has-sub"><a href=""><span>Tema Yönetimi</span></a>
			<ul>
			<li><a href="index.php?option=admin&bolum=templates&task=sitetemplates"><span>Site Temaları</span></a></li>
			<li><a href="index.php?option=admin&bolum=templates&task=admintemplates"><span>Admin Temaları</span></a></li>
			</ul>
		</li>
		<li><a href="index.php?option=admin&bolum=blocks"><span>Blok Yönetimi</span></a></li>
	</ul>
</li>
<li><a href="index.php?option=site"><span>Siteye Geçiş Yap</span></a></li>
<li><a href="index.php?option=logout"><span>Çıkış Yap</span></a></li>    
</ul>
</div>
<?php
}

function loadAdminModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	AdminPanel();
	break;
	
	case 'admin':
	if ($bolum) {
		if (file_exists(ABSPATH. '/admin/modules/'.$bolum.'/index.php')) {
	include_once(ABSPATH. '/admin/modules/'.$bolum.'/index.php');
		} else {
			NotAuth();
			return;
		}
	} else {
		Redirect('index.php');
	}
	break;
	
	case 'site':
	convertSite();
	break;
}
}

function AdminPanel() {
	?>
	<div class="row">
	<div class="col-sm-4">
	<?php AdminStats();?>
	</div>

	<div class="col-sm-8">
	<?php AdminPanelMenu();?>
	</div>
	</div>
	<?php
}

function AdminStats() {
	global $dbase;
	
	//Toplam üye
	$dbase->setQuery("SELECT COUNT(*) FROM #__users");
	$totaluye = $dbase->loadResult();
	
	//Aktif üye
	$dbase->setQuery("SELECT COUNT(*) FROM #__users WHERE activated=1");
	$totalaktif = $dbase->loadResult();
	
	//Bugün kayıt olan üye sayısı
	$day = date('d');
	$month = date('m');
	$year = date('Y');
	
	$startts = mktime(0, 0, 0, $month, $day, $year);
	$start = date('Y-m-d H:i:s', $startts); 
	$endts = mktime(23, 59, 59, $month, $day, $year);
	$end = date('Y-m-d H:i:s', $endts);
	
	$dbase->setQuery("SELECT COUNT(*) FROM #__users WHERE registerDate BETWEEN ".$dbase->Quote($start)." AND ".$dbase->Quote($end));
	$bugunuye = $dbase->loadResult();
	
	//toplam grup sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__groups");
	$totalgrup = $dbase->loadResult();
	
	//toplam kapalı grup sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__groups WHERE status=1");
	$totalkapaligrup = $dbase->loadResult();
	
	//toplam topic sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__forum_topics");
	$totaltopic = $dbase->loadResult();
	
	//toplam mesaj sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__forum_messages");
	$totalmsg = $dbase->loadResult();
	
	//toplam albüm sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__albums");
	$totalalbum = $dbase->loadResult();
	
	//toplam resim sayısı
	$dbase->setQuery("SELECT COUNT(*) FROM #__album_images");
	$totalalbumimg = $dbase->loadResult();
	
	?>
	<div class="panel panel-default">
	<div class="panel-heading"><h4>Site İstatistikleri</h4></div>
	<div class="panel-body">
	
	<fieldset>
	<legend>Üye İstatistikleri:</legend>
	<div class="row">
	<div class="col-sm-9">
	Toplam Kayıtlı Üye Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totaluye;?>
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Aktif Üye Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalaktif;?>
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-9">
	Bugün Kayıt Olan Üye Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $bugunuye;?>
	</div>
	</div>
	
	</fieldset>
	<fieldset>
	<legend>Grup İstatistikleri:</legend>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Grup Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalgrup;?>
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Kapalı Grup Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalkapaligrup;?>
	</div>
	</div>
	
	</fieldset>
	
	<fieldset>
	<legend>Forum İstatistikleri:</legend>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Başlık Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totaltopic;?>
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Mesaj Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalmsg;?>
	</div>
	</div>
	
	</fieldset>
	
	<fieldset>
	<legend>Albüm İstatistikleri:</legend>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Albüm Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalalbum;?>
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-9">
	Toplam Resim Sayısı:
	</div>
	<div class="col-sm-3">
	<?php echo $totalalbumimg;?>
	</div>
	</div>
	
	</fieldset>
	
	</div>
	</div>
	<?php
}

function convertSite() {
	global $mainframe, $dbase, $my;
	
	if ($my->id == 1) {
	$session = new mezunSession($dbase);
	$session->load($mainframe->_session->session);

	$session->access_type = 'site';
	$session->update();
	
	Redirect('index.php');
	} else {
		NotAuth();
	}    
}

function quickiconButton( $link, $image, $text ) {
?>
<div class="quickicon col-sm-3">
<span>
<a href="<?php echo $link; ?>">
<img src="<?php echo SITEURL;?>/admin/templates/<?php echo ADMINTEMPLATE;?>/images/<?php echo $image;?>" alt="<?php echo $text;?>" title="<?php echo $text;?>" border="0" /><br /><?php echo $text;?>
</a>
</span>
</div>
<?php
}

function AdminPanelMenu() {
	?>
	<div class="panel panel-default">
	<div class="panel-heading"><h4>Kısayollar</h4></div>
	<div class="panel-body">
	<?php
	echo quickiconButton('index.php?option=admin&bolum=ayarlar', 'ayarlar.png', 'Yapılandırma Dosyası');
	echo quickiconButton('index.php?option=admin&bolum=config', 'config.png', 'Site Ayarları');
	echo quickiconButton('index.php?option=admin&bolum=user', 'kullanici.png', 'Kullanıcı Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=menu', 'menu.png', 'Menü Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=blocks', 'block.png', 'Site Blok Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=templates', 'templates.png', 'Site Tema Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=sehir', 'sehir.png', 'Şehir Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=brans', 'brans.png', 'Branş Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=db', 'db.png', 'Veritabanı Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=duyuru', 'duyuru.png', 'Duyuru Yönetimi');    
	echo quickiconButton('index.php?option=admin&bolum=bildirim', 'form.png', 'Geri Bildirimler');
	echo quickiconButton('index.php?option=admin&bolum=akis', 'akis.png', 'Site Akış Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=album', 'album.png', 'Site Albüm Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=group', 'group.png', 'Site Grup Yönetimi');
	echo quickiconButton('index.php?option=admin&bolum=forum', 'mesaj.png', 'Forum Yönetimi');
	echo quickiconButton('index.php?option=site', 'change.png', 'Siteye Geçiş Yap');
	?>
	</div></div>
	<?php
}

function itemState($status) {
	
	return $status ? '<img src="'.SITEURL.'/admin/images/tick.png" />':'<img src="'.SITEURL.'/admin/images/x.png" />';
	
}

function itemAccess($status) {
	if ($status == 0) {
		return 'Ziyaretçi';
	} else if ($status == 1) {
		return 'Üye';
	} else {
		return 'Admin';
	}
}