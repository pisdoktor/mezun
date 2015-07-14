<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunSehirler extends mezunTable {
	
	var $id     = null;
	
	var $name   = null;
	
	function mezunSehirler( &$db ) {
		$this->mezunTable( '#__sehirler', 'id', $db );
	}
}