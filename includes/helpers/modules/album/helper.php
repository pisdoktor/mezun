<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAlbumHelper {
	
	static function getUserAlbums($userid) {
		global $dbase, $my;
		
		//arkadaşlık var mı bakalım
		mimport('helpers.modules.arkadas.helper');
		$varmi = mezunArkadasHelper::checkArkadaslik($userid);
		//arkadaş isek status:1 olan albümleri de alalım (status:2 ise gizlidir sadece kendisi görebilir)
		if ($varmi) {
			$status = '0,1';
		} else {
			$status = '0';
		}
		
		$query = "SELECT a.*, COUNT(i.id) AS total FROM #__albums AS a "
		. "\n LEFT JOIN #__album_images AS i ON i.albumid=a.id"
		. "\n WHERE a.userid=".$dbase->Quote($userid)." AND a.status IN (".$status.")"
		. "\n GROUP BY a.id"
		;
		$dbase->setQuery($query);
		
		return $dbase->loadObjectList();
	}
	
	static function getMyAlbums() {
		global $dbase, $my;
		
		$query = "SELECT a.*, COUNT(i.id) AS total FROM #__albums AS a "
		. "\n LEFT JOIN #__album_images AS i ON i.albumid=a.id"
		. "\n WHERE a.userid=".$dbase->Quote($my->id)
		. "\n GROUP BY a.id"
		;
		$dbase->setQuery($query);
		
		return $dbase->loadObjectList();
	}
	
	static function getTotalAlbum() {
		global $dbase, $my;
		
		$query = "SELECT COUNT(id) FROM #__albums WHERE userid=".$dbase->Quote($my->id);
		
		return $dbase->loadResult();
	}
	
	static function albumStatus($status) {
		
		if ($status == 0) {
			echo 'Herkese Açık';
		} else 
		
		if ($status == 1) {
			echo 'Arkadaşlarım';
		} else {
			echo 'Gizli';
		}
		
	}
}
