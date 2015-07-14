<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForumcategory extends mezunTable {
	
	var $ID_CAT = null;
	
	var $name = null;
	
	var $catOrder = null;
	
	function mezunForumcategory(&$db) {
		$this->mezunTable( '#__forum_categories', 'ID_CAT', $db );
	}
}