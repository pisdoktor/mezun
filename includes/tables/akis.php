<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAkis extends mezunTable {
	
	var $id     = null;
	
	var $tarih  = null;
	
	var $text   = null;
	
	function mezunAkis( &$db ) {
		$this->mezunTable( '#__akis', 'id', $db );
	}
}