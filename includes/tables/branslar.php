<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunBranslar extends mezunTable {
	
	var $id     = null;
	
	var $name  = null;
	
	function mezunBranslar( &$db ) {
		$this->mezunTable( '#__branslar', 'id', $db );
	}
}