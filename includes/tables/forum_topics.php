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
		global $dbase, $my;
		
		$dbase->setQuery("SELECT t.ID_TOPIC, t.ID_BOARD, t.numReplies, t.numViews, t.locked, ms.subject, t.isSticky, t.ID_FIRST_MSG, t.ID_LAST_MSG, IFNULL(lt.ID_MSG, -1) + 1 AS new_from
	FROM (#__forum_topics AS t, #__forum_messages AS ms)
	LEFT JOIN #__forum_log_topics AS lt ON (lt.ID_TOPIC = ".$id." AND lt.ID_MEMBER = ".$my->id.")
	WHERE t.ID_TOPIC = ".$id." AND ms.ID_MSG = t.ID_FIRST_MSG LIMIT 1");
		$dbase->loadObject($topic_info);
		
		return $topic_info;
	}
	
	function TopicIndex($id, $limitstart, $limit) {
		global $dbase, $my;
		
		$dbase->setQuery("SELECT * FROM #__forum_messages WHERE ID_TOPIC=".$dbase->Quote($id), $limitstart, $limit);
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
}
