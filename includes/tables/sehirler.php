<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Sehirler extends DBTable {
	
	var $id     = null;
	
	var $name   = null;
	
	function Sehirler( &$db ) {
		$this->DBTable( '#__sehirler', 'id', $db );
	}
}