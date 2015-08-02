<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAlbums extends mezunTable {
	
	var $id     = null;
	
	var $userid = null;
	
	var $name  = null;
	
	var $aciklama   = null;
	
	var $creationdate = null;
	
	var $status = null; //0: herkese açık, 1: arkadaşlarım, 2: gizli
	
	function mezunAlbums( &$db ) {
		$this->mezunTable( '#__albums', 'id', $db );
	}
}

class mezunAlbumImages extends mezunTable {
	
	var $id     = null;
	
	var $albumid = null;
	
	var $userid  = null;
	
	var $image   = null;
	
	var $title = null;
	
	var $addeddate = null;
	
	function mezunAlbumImages( &$db ) {
		$this->mezunTable( '#__album_images', 'id', $db );
	}
}
