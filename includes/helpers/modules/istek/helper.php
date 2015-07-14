<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunIstekHelper {
	
	function __construct() {
		
		
	}
	
	static function loadIstek() {
		global $dbase, $my;
	
	$query = "SELECT COUNT(*) FROM #__istekler WHERE durum=0 AND aid=".$my->id;
	$dbase->setQuery($query);
	
	$total = $dbase->loadResult();
	
	$link = $total ? '<a href="'.sefLink('index.php?option=site&bolum=istek&task=inbox').'">'.$total.'</a>' : $total;
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
	
}
