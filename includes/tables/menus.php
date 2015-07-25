<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunMenus extends mezunTable {
	
	var $id     = null;
	
	var $parent = null;
	
	var $name   = null;
	
	var $link   = null;
	
	var $published = null;
	
	var $menu_type = null;
	
	var $access = null;
	
	var $ordering = null;
	
	function mezunMenus( &$db ) {
		$this->mezunTable( '#__menu', 'id', $db );
	}
}