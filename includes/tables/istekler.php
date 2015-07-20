<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunIstekler extends mezunTable {
	
	var $id = null;
	
	var $gid     = null;
	
	var $aid   = null;
	
	var $tarih = null;
	
	var $durum  = null;
	
	function mezunIstekler( &$db ) {
		$this->mezunTable( '#__istekler', 'id', $db );
	}
}