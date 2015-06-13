<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  

function siteMenu() {
	global $my, $dbase;
	$msg = new Mesajlar($dbase);
	?>
<div id="cssmenu">
<ul>
<li><a href="<?php echo SITEURL;?>"><span>Anasayfa</span></a></li>
<?php
// üyelere özel menü
if (!$my->id) {
?>
<li><a href="index.php?option=register"><span>Kayıt Ol</span></a></li> 
</ul> 
</div>  
<?php        
} else {
?>
<li><a href="index.php?option=site&bolum=profil&task=my"><span>Profilim</span></a></li>
<li><a href="index.php?option=site&bolum=mesaj"><span>Mesajlarım</span></a></li>
<li><a href="index.php?option=site&bolum=arama"><span>Üye Arama</span></a></li>
<li><a href="index.php?option=logout"><span>Çıkış Yap</span></a></li>	
</ul>
</div>
<div id="messages"><?php echo $msg->newMsg();?></div>
<?php
}
}

function loadSiteModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	UserPanel();
	//loadDuyuru();
	loadStats();
	break;
	
	case 'site':
	if ($bolum) {
	include_once(ABSPATH. '/site/modules/'.$bolum.'/index.php');
	} else {
		mosRedirect('index.php');
	}
	break;
	}
	
}

function loadDuyuru() {
	global $dbase, $my;
	
	$query = "SELECT * FROM #__duyurular"
	. "\n ORDER BY tarih ASC";
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
?>
<div id="duyurular" class="clearfix">
<h3><span>Son Duyurular</span></h3>
<?php
$t = 1;
	foreach ($rows as $row) {
		?>
		<div class="duyuru<?php echo $t;?>">
		<div class="duyuru-tarih">
		<strong>Duyuru Tarihi:</strong> <?php echo mosFormatDate($row->tarih, '%d-%m-%Y %H:%M:%S');?>
		</div>
		<div class="duyuru-metin">
		<?php echo $row->metin;?>
		</div>
		</div>
		<?php
		$t = 1 - $t;
	}
?>
</div>
<?php
	
}

function UserPanel() {
	global $my;
	
	$lastvisit = ($my->lastvisit == '0000-00-00 00:00:00') ? 'İlk Defa Giriş Yaptınız' : mosFormatDate($my->lastvisit, '%d-%m-%Y %H:%M:%S');
	
	echo '<div id="welcome" class="clearfix">';
	echo '<span><center><h3>Hoşgeldiniz '.$my->name.'</h3></center></span><br />';
	echo '<span><strong><u>Çalıştığınız Kurum:</u></strong><br />'. $my->work.'</span><br />';
	echo '<span><strong><u>Bulunduğunuz Şehir:</u></strong><br />'. $my->sehir.'</span><br />';
	echo '<span><strong><u>Siteye Son Gelişiniz:</u></strong><br />'. $lastvisit.'</span>';
	echo '</div>';
}

function loadStats() {
	global $my, $dbase;
	
	//aktive edilmiş toplam üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1";
	$dbase->setQuery($query);
	$tactivated = $dbase->loadResult();
	
	//aynı ildeki üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE sehir=".$dbase->Quote($my->sehirid);
	$dbase->setQuery($query);
	$aynisehir = $dbase->loadResult();
	
	//sizinle aynı şehirde doğan hemşeriniz üyeler
	$query = "SELECT COUNT(*) FROM #__users WHERE dogumyeri=".$dbase->Quote($my->sehirid);
	$dbase->setQuery($query);
	$aynidogum = $dbase->loadResult();
	
	//aynı branştaki üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE brans=".$dbase->Quote($my->brans);
	$dbase->setQuery($query);
	$aynibrans = $dbase->loadResult();
	
	//aynı yıl okula başlayanlar
	$query = "SELECT COUNT(*) FROM #__users WHERE byili=".$dbase->Quote($my->byili);
	$dbase->setQuery($query);
	$ayniyilbaslama = $dbase->loadResult();
	
	//aynı yıl okulu bitirenler
	$query = "SELECT COUNT(*) FROM #__users WHERE myili=".$dbase->Quote($my->myili);
	$dbase->setQuery($query);
	$ayniyilbitirme = $dbase->loadResult();
	?>
	<div id="stats" class="clearfix">
	<h3>Site İstatistikleri:</h3>
	<table width="100%">
	<tr>
	<td>
	Toplam Üye Sayısı:
	</td>
	<td>
	<?php echo $tactivated;?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Sizinle Aynı Şehirdeki Üye Sayısı:
	</td>
	<td>
	<?php echo $aynisehir-1;?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Sizinle Aynı Şehirde Doğan Üyeler:
	</td>
	<td>
	<?php echo $aynidogum-1;?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Sizinle Aynı Branştaki Üye Sayısı:
	</td>
	<td>
	<?php echo $aynibrans-1;?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Sizinle Aynı Yıl Okula Başlayanlar:
	</td>
	<td>
	<?php echo $ayniyilbaslama-1;?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Sizinle Aynı Yıl Okulu Bitirenler:
	</td>
	<td>
	<?php echo $ayniyilbitirme-1;?> Kişi
	</td>
	</tr>
	</table>
	</div>
	<?php
}