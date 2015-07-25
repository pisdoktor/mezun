<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	global $dbase, $my;
		
		$query = "SELECT COUNT(*) FROM #__mesajlar WHERE aid=".$dbase->Quote($my->id)." AND okunma=0";
		
		$dbase->setQuery($query);
		
		$total = $dbase->loadResult();
		
		$link = $total ? '<a href="'.sefLink('index.php?option=site&bolum=mesaj&task=inbox').'">'.$total.'</a>' : $total;
	?>
	Toplam <span class="badge"><?php echo $link;?></span> yeni mesajınız var
