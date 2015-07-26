<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	global $my;
	
	$pimage = $my->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$my->image.'" alt="'.$my->name.'" title="'.$my->name.'" width="50" height="50" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" alt="'.$my->name.'" title="'.$my->name.'" width="50" height="50" />';
	
	$pimage = '<a href="index.php?option=site&bolum=profil&task=my">'.$pimage.'</a>';
	$pname = '<a href="index.php?option=site&bolum=profil&task=my">Profilini gör</a>';
	
	echo '<div class="row">';
	echo '<div class="col-sm-4">'.$pimage.'</div>';
	echo '<div class="col-sm-8">Merhaba, '.$my->name.'<br />'.$pname.'</div>';
	echo '</div>';
	
	
	/*
	$lastvisit = ($my->lastvisit == '0000-00-00 00:00:00') ? 'İlk Defa Giriş Yaptınız' : mezunGlobalHelper::timeformat($my->lastvisit, true, true);
	
	echo '<div align="left" style="float:left;padding:5px;" class="img-rounded">'.$pimage.'</div>';
	echo '<span><strong><u>Yaşadığınız Şehir:</u></strong><br />'. $my->sehir.'</span><br />';
	echo '<span><strong><u>Siteye Son Gelişiniz:</u></strong><br />'. $lastvisit.'</span><br />';
	echo '<span><strong><u>Yaşınız:</u></strong><br />'. mezunGlobalHelper::calculateAge($my->dogumtarihi).'</span><br />';
	*/



