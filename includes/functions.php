<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  

function siteMenu() {
	global $my;
	?>
<div id="cssmenu">
<ul>
<li><a href="<?php echo SITEURL;?>"><span>Anasayfa</span></a></li>
<?php
	// üyelere özel menü
	if ($my->id) {
?>
<li><a href="index.php?option=site&bolum=profil&task=my"><span>Profilim</span></a></li>
<li><a href="index.php?option=site&bolum=mesaj"><span>Mesajlarım</span></a></li>
<li><a href="index.php?option=site&bolum=arama"><span>Üye Arama</span></a></li>
<li><a href="index.php?option=logout"><span>Çıkış Yap</span></a></li>
<?php        
	} else {
		?>
<li><a href="index.php?option=register"><span>Kayıt Ol</span></a></li>		
		<?php
	}
?>

</ul>
</div>
<?php    
}

function loadSiteModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	UserPanel();
	loadDuyuru();
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
<div id="duyurular">
<h3><span> Son Duyurular</span></h3>
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
	
	echo '<div id="welcome">';
	echo '<span><center><strong>Hoşgeldiniz '.$my->name.'</strong></center></span><br />';
	echo '<span><strong><u>Son Giriş Zamanı:</u></strong><br />'. $lastvisit.'</span>';
	echo '</div>';
}