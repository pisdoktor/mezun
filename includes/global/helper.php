<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalHelper {	
	
	static function calculateAge($bday) {
		
		if ($bday) {
			$now = mezunGlobalHelper::time_stamp();
			list($day, $month, $year) = explode('-', $bday);
			$birth = mktime(0, 0, 0, $month, $day, $year);
		
			$diff = $now-$birth;
		
		return ceil($diff/(60*60*24*365));
		
		} else {
			return false;
		}
	}
	
	static function formButton($value, $onclick, $uyari=0) {
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
	
	static function getSiteAkis() {
		global $dbase;
		
		$dbase->setQuery("SELECT * FROM #__akis ORDER BY tarih DESC LIMIT 10");
		$rows = $dbase->loadObjectList();
		?>
		<div class="col-sm-12">
		<div class="panel panel-danger">
		<div class="panel-heading">Sitede Neler Oluyor?</div>
		<div class="panel-body">
		<?php
		  foreach ($rows as $row) {
			  ?>
			  <div class="row">
			  <div class="col-sm-3">
			  <?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
			  </div>
			  <div class="col-sm-9">
			  <?php echo $row->text;?>
			  </div>
			  </div>
			  <?php
		  }  
		?>
		</div>
		</div>
		</div>
		<?php
	}
	
	static function loadDuyuru() {
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

	static function loadUserStats() {
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
	
	$link['aynisehir'] = $aynisehir-1 ? '<a href="'.sefLink('index.php?option=site&bolum=arama&task=search&sehir='.$my->sehirid).'">'.($aynisehir-1).'</a>' : '0';
	
	//sizinle aynı şehirde doğan hemşeriniz üyeler
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND dogumyeri=".$dbase->Quote($my->dogumyeriid);
	$dbase->setQuery($query);
	$aynidogum = $dbase->loadResult();
	
	$link['aynidogum'] = $aynidogum-1 ? '<a href="'.sefLink('index.php?option=site&bolum=arama&task=search&dogumyeri='.$my->dogumyeriid).'">'.($aynidogum-1).'</a>' : '0';
	
	//aynı branştaki üye sayısı
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND brans=".$dbase->Quote($my->brans);
	$dbase->setQuery($query);
	$aynibrans = $dbase->loadResult();
	
	$link['aynibrans'] = $aynibrans-1 ? '<a href="'.sefLink('index.php?option=site&bolum=arama&task=search&brans='.$my->brans).'">'.($aynibrans-1).'</a>' : '0';
	
	//aynı yıl okula başlayanlar
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND byili=".$dbase->Quote($my->byili);
	$dbase->setQuery($query);
	$ayniyilbaslama = $dbase->loadResult();
	
	$link['ayniyilbaslama'] = $ayniyilbaslama-1 ? '<a href="'.sefLink('index.php?option=site&bolum=arama&task=search&byili='.$my->byili).'">'.($ayniyilbaslama-1).'</a>' : '0';
	
	//aynı yıl okulu bitirenler
	$query = "SELECT COUNT(*) FROM #__users WHERE activated=1 AND myili=".$dbase->Quote($my->myili);
	$dbase->setQuery($query);
	$ayniyilbitirme = $dbase->loadResult();
	
	$link['ayniyilbitirme'] = $ayniyilbitirme-1 ? '<a href="'.sefLink('index.php?option=site&bolum=arama&task=search&myili='.$my->myili).'">'.($ayniyilbitirme-1).'</a>' : '0';
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

	static function shortText($text, $len) {

		// It was already short enough!
		if (strlen($text) <= $len)
			return $text;

		// Shorten it by the length it was too long, and strip off junk from the end.
		return substr($text, 0, $len) . '...';
	}
	
	static function siteMenu() {
		global $my, $dbase;
	
		mimport('helpers.modules.mesaj.helper');
		mimport('helpers.modules.istek.helper');
		mimport('helpers.modules.online.helper');
	?>
<div id="cssmenu">

<ul>

<li><a href="<?php echo SITEURL;?>"><span>Anasayfa</span></a></li>
<?php
// üyelere özel menü
if (!$my->id) {
?>
<li><a href="<?php echo sefLink('index.php?option=register');?>"><span>Kayıt Ol</span></a></li>  
<?php        
} else {
?>
<li class="has-sub"><a href="#"><span>Menüm</span></a>
<ul>

<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=profil&task=my');?>"><span>Profilim</span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=arkadas');?>"><span>Arkadaşlarım</span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=online');?>"><span>Online Üyeler <span class="badge"><?php mezunOnlineHelper::totalOnline();?></span></span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=bildirim');?>"><span>Geri Bildirim</span></a>
</li>

</ul>

</li>

<li class="has-sub"><a href="#"><span>Mesajlar</span></a>

<ul>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=mesaj&task=inbox');?>"><span>Gelen Kutusu <span class="badge"><?php mezunMesajHelper::totalUnread();?></span></span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=mesaj&task=outbox');?>"><span>Giden Kutusu</span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=mesaj&task=new');?>"><span>Yeni Mesaj</span></a>
</li>

</ul>

</li>

<li class="has-sub"><a href="#"><span>İstekler</span></a>

<ul>

<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=istek&task=inbox');?>"><span>Gelen İstekler <span class="badge"><?php mezunIstekHelper::totalWaiting();?></span></span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=istek&task=outbox');?>"><span>Giden İstekler</span></a>
</li>

</ul>

</li>

<li class="has-sub"><a href="#"><span>Gruplar</span></a>

<ul>

<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=all');?>"><span>Tüm Gruplar</span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=my');?>"><span>Gruplarım</span></a>
</li>
<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=new');?>"><span>Yeni Grup</span></a>
</li>

</ul>

</li>

<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=forum');?>"><span>Forum</span></a>
</li>

<li>
<a href="<?php echo sefLink('index.php?option=site&bolum=arama');?>"><span>Üye Arama</span></a>
</li>

<?php 
if ($my->id == 1) {
?>
<li>
<a href="index.php?option=admin"><span>Yönetim</span></a>
</li>
<?php    
}
?>
<li>
<a href="<?php echo sefLink('index.php?option=logout');?>"><span>Çıkış Yap</span></a>
</li>   
 

<?php
}
?>
</ul>
</div>
<?php
}

	static function time_stamp($timestamp = null) {
	
	if ($timestamp === null) {
		$timestamp = time();
	} elseif ($timestamp == 0) {
		return 0;
	}

	return $timestamp + (OFFSET * 3600);
}

	static function timeformat($logTime, $show_today = true, $datetime=false) {
		
		$txt['days'] = array('Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi');
		$txt['days_short'] = array('Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt');
		// Months must start with 1 => 'January'. (or translated, of course.)
		$txt['months'] = array(1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık');
		$txt['months_short'] = array(1 => 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara');

		if ($datetime == true) {
			$logTime = strtotime($logTime);
		}
		// Offset the time.
		$time = $logTime + (OFFSET * 3600);

		// We can't have a negative date (on Windows, at least.)
		if ($time < 0) {
			$time = 0;
		}

		// Today and Yesterday?
		if (todayMod >= 1 && $show_today === true) {
		// Get the current time.
		$nowtime = mezunGlobalHelper::time_stamp();

		$then = @getdate($time);
		$now = @getdate($nowtime);

		// Try to make something of a time format string...
		$s = strpos(TIMEFORMAT, '%S') === false ? '' : ':%S';
		if (strpos(TIMEFORMAT, '%H') === false && strpos(TIMEFORMAT, '%T') === false) {
			$today_fmt = '%I:%M' . $s . ' %p';
		} else {
			$today_fmt = '%H:%M' . $s;
		}

		// Same day of the year, same year.... Today!
		if ($then['yday'] == $now['yday'] && $then['year'] == $now['year']) {
			return '<b>Bugün</b> ' . mezunGlobalHelper::timeformat($logTime, $today_fmt);
		}

		// Day-of-year is one less and same year, or it's the first of the year and that's the last of the year...
		if (todayMod == '2' && (($then['yday'] == $now['yday'] - 1 && $then['year'] == $now['year']) || ($now['yday'] == 0 && $then['year'] == $now['year'] - 1) && $then['mon'] == 12 && $then['mday'] == 31)) {
			return '<b>Dün</b> ' . mezunGlobalHelper::timeformat($logTime, $today_fmt);
		}
	}

	$str = !is_bool($show_today) ? $show_today : TIMEFORMAT;

	if (setlocale(LC_TIME, 'tr_TR')) {
		foreach (array('%a', '%A', '%b', '%B') as $token)
			if (strpos($str, $token) !== false)
				$str = str_replace($token, ucwords(strftime($token, $time)), $str);
	} else {
		// Do-it-yourself time localization.  Fun.
		foreach (array('%a' => 'days_short', '%A' => 'days', '%b' => 'months_short', '%B' => 'months') as $token => $text_label)
			if (strpos($str, $token) !== false)
				$str = str_replace($token, $txt[$text_label][(int) strftime($token === '%a' || $token === '%A' ? '%w' : '%m', $time)], $str);
		if (strpos($str, '%p'))
			$str = str_replace('%p', (strftime('%H', $time) < 12 ? 'am' : 'pm'), $str);
	}

	// Format any other characters..
	return strftime($str, $time);
}
	
	static function WelcomePanel() {
		global $my;
	
	$lastvisit = ($my->lastvisit == '0000-00-00 00:00:00') ? 'İlk Defa Giriş Yaptınız' : mezunGlobalHelper::timeformat($my->lastvisit, true, true);
	
	$pimage = $my->image ? '<img src="'.SITEURL.'/images/profil/'.$my->image.'" alt="'.$my->name.'" title="'.$my->name.'" width="100" height="100" />':'<img src="'.SITEURL.'/images/profil/noimage.png" alt="'.$my->name.'" title="'.$my->name.'" width="100" height="100" />';
	
	$pimage = '<a href="index.php?option=site&bolum=profil&task=my">'.$pimage.'</a>';
	
	echo '<div class="col-sm-12">';
	echo '<div class="panel panel-primary">';
	echo '<div class="panel-heading">Hoşgeldiniz '.$my->name.'</div>';
	echo '<div class="panel-body">';
	echo '<div align="left" style="float:left;padding:5px;" class="img-rounded">'.$pimage.'</div>';
	echo '<span><strong><u>Yaşadığınız Şehir:</u></strong><br />'. $my->sehir.'</span><br />';
	echo '<span><strong><u>Siteye Son Gelişiniz:</u></strong><br />'. $lastvisit.'</span><br />';
	echo '<span><strong><u>Yaşınız:</u></strong><br />'. mezunGlobalHelper::calculateAge($my->dogumtarihi).'</span><br />';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
}