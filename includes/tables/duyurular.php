<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Duyurular extends DBTable {
	
	var $id     = null;
	
	var $text  = null;
	
	var $tarih = null;
	
	function Duyurular( &$db ) {
		$this->DBTable( '#__duyurular', 'id', $db );
	}
}