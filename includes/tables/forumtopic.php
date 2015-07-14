<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForumtopic extends mezunTable {
	
	var $ID_TOPIC = null;
	
	var $isSticky = null;
	
	var $ID_BOARD = null;
	
	var $ID_FIRST_MSG = null;
	
	var $ID_LAST_MSG = null;
	
	var $numReplies = null;
	
	var $numViews = null;
	
	var $locked = null;
	
	var $icon = null;
	
	function mezunForumtopic(&$db) {
		$this->mezunTable( '#__forum_topics', 'ID_TOPIC', $db );
	}
}