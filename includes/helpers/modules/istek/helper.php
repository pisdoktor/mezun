<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunIstekHelper {
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
			return $dbase->loadResult();
		} else {
			return 0;
		}
	}
	
}
