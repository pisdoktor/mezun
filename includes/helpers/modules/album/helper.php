<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAlbumHelper {
	
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
		} else if ($status == 1) {
			echo 'Arkadaşlarım';
		} else {
			echo 'Gizli';
		}
		
	}
	
	
}
