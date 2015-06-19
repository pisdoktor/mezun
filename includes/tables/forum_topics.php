<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class BoardTopics extends DBTable {
	
	var $ID_TOPIC = null;
	
	var $isSticky = null;
	
	var $ID_BOARD = null;
	
	var $ID_FIRST_MSG = null;
	
	var $ID_LAST_MSG = null;
	
	var $numReplies = null;
	
	var $numViews = null;
	
	var $locked = null;
	
	function BoardTopics(&$db) {
		$this->DBTable( '#__forum_topics', 'ID_TOPIC', $db );
	}
	
	function TopicInfo($id) {
		global $dbase;
		
		$dbase->setQUery("SELECT * FROM #__forum_topics WHERE ID_TOPIC=".$id);
		$dbase->loadObject($topic_info);
		
		return $topic_info;
	}
	
	function TopicIndex($id) {
		global $dbase;
	}
}
