<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForumboard extends mezunTable {
	
	var $ID_BOARD     = null;
	
	var $ID_CAT = null;
	
	var $ID_PARENT = null;
	
	var $boardOrder = null;
	
	var $ID_LAST_MSG = null;
	
	var $ID_MSG_UPDATED = null;
	
	var $name  = null;
	
	var $aciklama = null;
	
	var $numTopics = null;
	
	var $numPosts = null;
	
	var $countPosts = null;
	
	function mezunForumboard( &$db ) {
		$this->mezunTable( '#__forum_boards', 'ID_BOARD', $db );
	}
}