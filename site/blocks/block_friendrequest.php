<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	global $dbase, $my;

	$query = "SELECT COUNT(*) FROM #__istekler WHERE durum=0 AND aid=".$my->id;
	$dbase->setQuery($query);
	
	$total = $dbase->loadResult();
	
	$link = $total ? '<a href="'.sefLink('index.php?option=site&bolum=istek&task=inbox').'">'.$total.'</a>' : $total;
	?>
	Toplam <span class="badge"><?php echo $link;?></span> yeni arkadaşlık isteğiniz var