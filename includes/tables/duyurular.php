<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunDuyurular extends mezunTable {
	
	var $id     = null;
	
	var $text  = null;
	
	var $tarih = null;
	
	function mezunDuyurular( &$db ) {
		$this->mezunTable( '#__duyurular', 'id', $db );
	}
}