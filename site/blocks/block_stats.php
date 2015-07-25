<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	global $dbase, $my;

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