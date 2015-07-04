<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  

/**
* Site menüsü
*/
function siteMenu() {
	global $my, $dbase;
	$msg = new Mesajlar($dbase);
	$istek = new Istekler($dbase);
	$online = new Session($dbase);
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
<li class="has-sub"><a href="#"><span>Menüm</span></a>
<ul>
<li><a href="index.php?option=site&bolum=profil&task=my"><span>Profilim</span></a></li>
<li><a href="index.php?option=site&bolum=arkadas"><span>Arkadaşlarım</span></a></li>
<li><a href="index.php?option=site&bolum=online"><span>Online Üyeler <span class="badge"><?php $online->totalOnline();?></span></span></a></li>
</ul>
</li>
<li class="has-sub"><a href="#"><span>Mesajlar</span></a>
<ul>
<li><a href="index.php?option=site&bolum=mesaj&task=inbox"><span>Gelen Kutusu <span class="badge"><?php $msg->totalUnread();?></span></span></a></li>
<li><a href="index.php?option=site&bolum=mesaj&task=outbox"><span>Giden Kutusu</span></a></li>
<li><a href="index.php?option=site&bolum=mesaj&task=new"><span>Yeni Mesaj</span></a></li>
</ul>
</li>
<li class="has-sub"><a href="#"><span>İstekler</span></a>
<ul>
<li><a href="index.php?option=site&bolum=istek&task=inbox"><span>Gelen İstekler <span class="badge"><?php $istek->totalWaiting();?></span></span></a></li>
<li><a href="index.php?option=site&bolum=istek&task=outbox"><span>Giden İstekler</span></a></li>
</ul>
</li>
<li><a href="index.php?option=site&bolum=arama"><span>Üye Arama</span></a></li>
<li><a href="index.php?option=site&bolum=forum"><span>Forum</span></a></li>
<?php 
if ($my->id == 1) {
?>
<li><a href="index.php?option=admin"><span>Yönetim</span></a></li>
<?php    
}
?>
<li><a href="index.php?option=logout"><span>Çıkış Yap</span></a></li>	
</ul>
</div>
<div id="messages"></div>
<?php
}
}
/**
* Admin için siteden yönetim paneline geçiş için fonksiyon
*/
function convertAdmin() {
	global $mainframe, $dbase, $my;
	
	if ($my->id == 1) {
	$session = new Session($dbase);
	$session->load($mainframe->_session->session);

	$session->access_type = 'admin';
	$session->update();
	
	Redirect('index.php');
	} else {
		NotAuth();
	}    
}
/**
* Site modüllerini yükleyen fonksiyon
*/
function loadSiteModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	MainPage();
	break;
	
	case 'site':
	if ($bolum) {
	include_once(ABSPATH. '/site/modules/'.$bolum.'/index.php');
	} else {
		Redirect('index.php');
	}
	break;
	
	case 'admin':
	convertAdmin();
	break;
	}
	
}

function MainPage() {
	?>
	<div class="col-sm-5">
	<?php 
	UserPanel();
	loadIstek();
	loadMailBox();
	loadStats();
	?>
	</div>
	<div class="col-sm-7">
	<?php
	loadDuyuru();
	?>
	</div>
	<?php
}
/**
* Duyuruları getiren fonksiyon
*/
function loadDuyuru() {
	global $dbase, $my;
	
	$query = "SELECT * FROM #__duyurular"
	. "\n ORDER BY tarih ASC";
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
?>
<div class="col-sm-12">
<div class="panel panel-danger">
  <div class="panel-heading">Duyuru Paneli</div>
  <div class="panel-body">
<?php

if (!$rows) {
	echo 'Herhangi bir duyuru bulunamadı!';
}
	foreach ($rows as $row) {
		?>
		<span>
		<strong>Duyuru Tarihi:</strong> <?php echo FormatDate($row->tarih, '%d-%m-%Y %H:%M:%S');?>
		</span>
		<br />
		<span>
		<?php echo $row->text;?>
		</span>
		<br /><br />
		<?php
	}
?>
</div>
</div>
</div>
<?php
	
}
/**
* Kullanıcı paneli: kullanıcı hakkında kısa bilgi
*/
function UserPanel() {
	global $my;
	
	$lastvisit = ($my->lastvisit == '0000-00-00 00:00:00') ? 'İlk Defa Giriş Yaptınız' : Forum::timeformat($my->lastvisit, true, true);
	
	echo '<div class="col-sm-12">';
	echo '<div class="panel panel-primary">';
	echo '<div class="panel-heading">Hoşgeldiniz '.$my->name.'</div>';
	echo '<div class="panel-body">';
	echo '<span><strong><u>Çalıştığınız Kurum:</u></strong><br />'. $my->work.'</span><br />';
	echo '<span><strong><u>Bulunduğunuz Şehir:</u></strong><br />'. $my->sehir.'</span><br />';
	echo '<span><strong><u>Siteye Son Gelişiniz:</u></strong><br />'. $lastvisit.'</span><br />';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
/**
* Site istatistiklerini gösteren fonksiyon
*/
function loadStats() {
	global $my, $dbase;
	
	$link = array();
	//aktive edilmiş toplam üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1";
	$dbase->setQuery($query);
	$tactivated = $dbase->loadResult();
	
	//aynı ildeki üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND sehir=".$dbase->Quote($my->sehirid);
	$dbase->setQuery($query);
	$aynisehir = $dbase->loadResult();
	
	$link['aynisehir'] = $aynisehir-1 ? '<a href="index.php?option=site&bolum=arama&task=search&sehir='.$my->sehirid.'">'.($aynisehir-1).'</a>' : '0';
	
	//sizinle aynı şehirde doğan hemşeriniz üyeler
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND dogumyeri=".$dbase->Quote($my->dogumyeriid);
	$dbase->setQuery($query);
	$aynidogum = $dbase->loadResult();
	
	$link['aynidogum'] = $aynidogum-1 ? '<a href="index.php?option=site&bolum=arama&task=search&dogumyeri='.$my->dogumyeriid.'">'.($aynidogum-1).'</a>' : '0';
	
	//aynı branştaki üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND brans=".$dbase->Quote($my->brans);
	$dbase->setQuery($query);
	$aynibrans = $dbase->loadResult();
	
	$link['aynibrans'] = $aynibrans-1 ? '<a href="index.php?option=site&bolum=arama&task=search&brans='.$my->brans.'">'.($aynibrans-1).'</a>' : '0';
	
	//aynı yıl okula başlayanlar
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND byili=".$dbase->Quote($my->byili);
	$dbase->setQuery($query);
	$ayniyilbaslama = $dbase->loadResult();
	
	$link['ayniyilbaslama'] = $ayniyilbaslama-1 ? '<a href="index.php?option=site&bolum=arama&task=search&byili='.$my->byili.'">'.($ayniyilbaslama-1).'</a>' : '0';
	
	//aynı yıl okulu bitirenler
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND myili=".$dbase->Quote($my->myili);
	$dbase->setQuery($query);
	$ayniyilbitirme = $dbase->loadResult();
	
	$link['ayniyilbitirme'] = $ayniyilbitirme-1 ? '<a href="index.php?option=site&bolum=arama&task=search&myili='.$my->myili.'">'.($ayniyilbitirme-1).'</a>' : '0';
	?>
	<div class="col-sm-12">
	
	<div class="panel panel-warning">
  <div class="panel-heading">Site İstatistikleri</div>
  <div class="panel-body">
	<table width="100%" class="table-hover">
	<tr>
	<td>
	Toplam Üye Sayısı:
	</td>
	<td>
	<?php echo $tactivated;?> Kişi
	</td>
	</tr>
	<tr>
	<th colspan="2" align="left">Sizinle</th></tr>
	<tr>
	<td>
	Aynı Şehirde Yaşayan Üye Sayısı:
	</td>
	<td>
	<?php echo $link['aynisehir'];?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Aynı Şehirde Doğan Üye Sayısı:
	</td>
	<td>
	<?php echo $link['aynidogum'];?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Aynı Branştaki Üye Sayısı:
	</td>
	<td>
	<?php echo $link['aynibrans'];?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Aynı Yıl Okula Başlayanlar:
	</td>
	<td>
	<?php echo $link['ayniyilbaslama'];?> Kişi
	</td>
	</tr>
	<tr>
	<td>
	Aynı Yıl Okulu Bitirenler:
	</td>
	<td>
	<?php echo $link['ayniyilbitirme'];?> Kişi
	</td>
	</tr>
	</table>
	</div>
	</div>
	</div>
	<?php
}
/**
* Gelen arkadaşlık isteklerini gösteren fonksiyon
*/
function loadIstek() {
	global $dbase, $my;
	
	$query = "SELECT COUNT(*) FROM #__istekler WHERE durum=0 AND aid=".$my->id;
	$dbase->setQuery($query);
	
	$total = $dbase->loadResult();
	
	$link = $total ? '<a href="index.php?option=site&bolum=istek&task=inbox">'.$total.'</a>' : $total;
	?>
	<div class="col-sm-12">
	<div class="panel panel-default">
  <div class="panel-heading">Gelen Arkadaşlık İstekleri</div>
  <div class="panel-body">Toplam <span class="badge"><?php echo $link;?></span> arkadaşlık isteğiniz var
  </div>
  </div>
	</div>
	<?php
}

function loadMailBox() {
	global $dbase;
	$msg = new Mesajlar($dbase);
	?>
	<div class="col-sm-12">
	<div class="panel panel-default">
  <div class="panel-heading">Mesaj Kutunuz</div>
  <div class="panel-body"><?php echo $msg->newMsg();?>
  </div>
  </div>
	</div>
	
	 <?php
}

function formButton($value, $onclick, $uyari=0) {
	$html = "";
	$html.= '<input type="button" name="button"';
	$html.= ' value="'.$value.'"';
	if ($uyari==1) {
	$html.= 'onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert(\'Lütfen listeden bir seçim yapın\'); } else {submitbutton(\''.$onclick.'\');}"';
	} elseif ($uyari==2) {
	$html.= 'onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert(\'Lütfen listeden bir seçim yapın\'); } else if (confirm(\'İşlemi onaylıyor musunuz?\')){ submitbutton(\''.$onclick.'\');}"';	
	} else {
	$html.= ' onclick="javascript:submitbutton(\''.$onclick.'\');"';
	}
	$html.= ' class="btn btn-default" />';
	
	return $html;
}

function isOnline($userid, $showimage=true) {
	global $dbase;
		
		$query = "SELECT userid FROM #__sessions WHERE userid=".$dbase->Quote($userid);
		$dbase->setQuery($query);
		$row = $dbase->loadResult();
		
		if ($showimage) {
		if ($row) {
			echo '<span class="onlineStatus">
			<img src="'.SITEURL.'/images/online/online.png" alt="Online" title="Online" /> Online
			</span>';
		} else {
			echo '<span class="onlineStatus">
			<img src="'.SITEURL.'/images/online/offline.png" alt="Offline" title="Offline" /> Offline
			</span>';
		}
		} else {
		if ($row) {
			echo 'Online';
		} else {
			echo 'Offline';
		}    
		}
	}