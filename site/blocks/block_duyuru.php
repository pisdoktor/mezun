<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	global $dbase;

	$query = "SELECT * FROM #__duyurular"
	. "\n ORDER BY tarih ASC";
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
if (!$rows) {
	echo 'Herhangi bir duyuru bulunamadı!';
}
	foreach ($rows as $row) {
		?>
		<div class="row">
			<div class="col-sm-3"><?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?></div>
			<div class="col-sm-9"><?php echo $row->text;?></div>		
		</div>
		<?php
	}