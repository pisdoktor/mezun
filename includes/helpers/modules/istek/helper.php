<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunIstekHelper {
	
	/**
	* Gelen arkadaşlık isteklerini gösteren fonksiyon
	* 
	*/
	static function loadIstekPanel() {
		global $dbase, $my;
	
	$query = "SELECT COUNT(*) FROM #__istekler WHERE durum=0 AND aid=".$my->id;
	$dbase->setQuery($query);
	
	$total = $dbase->loadResult();
	
	$link = $total ? '<a href="'.sefLink('index.php?option=site&bolum=istek&task=inbox').'">'.$total.'</a>' : $total;
	?>
	<div class="col-sm-12">
	<div class="panel panel-default">
	<div class="panel-heading">Arkadaşlık İstekleri</div>
	<div class="panel-body">
	Toplam <span class="badge"><?php echo $link;?></span> yeni arkadaşlık isteğiniz var
	</div>
	</div>
	</div>
	<?php
	}
	
	/**
	* Belirtilen kullanıcı ile bir arkadaşlık isteği olup olmadığına bakan fonksiyon
	* 
	* @param mixed $userid : isteğin bakılacağı kullanıcı id
	*/
	static function checkIstek($userid) {
		global $dbase, $my;
		
		$where[] = "(gid=".$dbase->Quote($userid)." AND aid=".$dbase->Quote($my->id).")";
		$where[] = "(gid=".$dbase->Quote($my->id)." AND aid=".$dbase->Quote($userid).")";
		
		$query = "SELECT id FROM #__istekler"
		. "\n WHERE (" . implode( ' OR ', $where ).")"
		. "\n AND durum=0";
		;
		$dbase->setQuery($query);
		
		if ($dbase->loadResult() > 0) {
			return true;
		} else {
			return false;
		}
	} 
	
	/**
	* Toplam bekleyen arkadaşlık istekleri 
	* 
	*/
	static function totalWaiting() {
		global $dbase, $my;
		
		$query = "SELECT COUNT(*) FROM #__istekler WHERE aid=".$dbase->Quote($my->id)." AND durum=0";
		$dbase->setQuery($query);
		
		if ($dbase->loadResult()) {
			echo $dbase->loadResult();
		}
	}
	
}
