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
		
		$dbase->setQuery("SELECT m.*, u.myili, u.lastvisit, u.image, u.name as posterName, u.cinsiyet, s.name AS sehirAdi 
		FROM #__forum_messages AS m 
		LEFT JOIN #__users AS u ON u.id=m.ID_MEMBER 
		LEFT JOIN #__sehirler AS s ON s.id=u.sehir
		WHERE m.ID_TOPIC=".$dbase->Quote($id)." ORDER BY m.posterTime ASC", $limitstart, $limit);
		$rows = $dbase->loadObjectList();
		
		foreach ($rows as $row) {
		if (!isset($context['messages'][$row->ID_MSG])) {
			$context['messages'][$row->ID_MSG] = array(
			'id' => $row->ID_MSG,
			'ID_TOPIC' => $row->ID_TOPIC,
			'ID_BOARD' => $row->ID_BOARD,
			'time' => Forum::timeformat($row->posterTime),
			'timestamp' => Forum::forum_time($row->posterTime),
			'member' => array(
				'id' => $row->ID_MEMBER,
				'name' => $row->posterName,
				'href' => 'index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER,
				'link' => '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER.'">'.$row->posterName.'</a>',
				'cinsiyet' => $row->cinsiyet ? 'Erkek' : 'Bayan',
				'profilimage' => $row->image ? SITEURL.'/images/'.$row->image : SITEURL.'/images/noimage.png',
				'imagelink' => $row->image ? '<img src="'.SITEURL.'/images/'.$row->image.'" />' : '<img src="'.SITEURL.'/images/noimage.png" width="100" height="100" />',
				'sehir' => $row->sehirAdi,
				'ip' => $row->posterIP,
				'lastvisit' => $row->lastvisit,
				'mezuniyet' => $row->myili
			),
			'subject' => $row->subject,
			'body' => $row->body
			);
		}
		}
		
		return $context['messages'];
	}
}
