<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class BoardTopic extends DBTable {
	
	var $ID_TOPIC = null;
	
	var $isSticky = null;
	
	var $ID_BOARD = null;
	
	var $ID_FIRST_MSG = null;
	
	var $ID_LAST_MSG = null;
	
	var $numReplies = null;
	
	var $numViews = null;
	
	var $locked = null;
	
	var $icon = null;
	
	function BoardTopic(&$db) {
		$this->DBTable( '#__forum_topics', 'ID_TOPIC', $db );
	}
	
	function TopicInfo($id) {
		global $my;
		
		$this->_db->setQuery("SELECT t.ID_TOPIC, t.ID_BOARD, t.icon, t.numReplies, t.numViews, t.locked, ms.subject, t.isSticky, t.ID_FIRST_MSG, t.ID_LAST_MSG, IFNULL(lt.ID_MSG, -1) + 1 AS new_from
	FROM (#__forum_topics AS t, #__forum_messages AS ms)
	LEFT JOIN #__forum_log_topics AS lt ON (lt.ID_TOPIC = ".$id." AND lt.ID_MEMBER = ".$my->id.")
	WHERE t.ID_TOPIC = ".$id." AND ms.ID_MSG = t.ID_FIRST_MSG LIMIT 1");
		$this->_db->loadObject($topic_info);
		
		return $topic_info;
	}
	
	function TopicIndex($id, $topicstart, $topiclimit) {
		global $my;
		
		$this->_db->setQuery("SELECT m.*, t.ID_LAST_MSG, u.myili, u.lastvisit, u.image, u.name as posterName, u.cinsiyet, s.name AS sehirAdi 
		FROM #__forum_messages AS m 
		LEFT JOIN #__forum_topics AS t ON t.ID_TOPIC=m.ID_TOPIC
		LEFT JOIN #__users AS u ON u.id=m.ID_MEMBER 
		LEFT JOIN #__sehirler AS s ON s.id=u.sehir
		WHERE m.ID_TOPIC=".$this->_db->Quote($id)." ORDER BY m.posterTime ASC", $topicstart, $topiclimit);
		$rows = $this->_db->loadObjectList();
		
		foreach ($rows as $row) {
		if (!isset($context['topic'])) {
			$context['topic'] = array(
			'ID_TOPIC' => $row->ID_TOPIC,
			'ID_BOARD' => $row->ID_BOARD,
			'lastMsg' => $row->ID_LAST_MSG,
			'messages' => array()
			);    
		}	
		
		if (!isset($context['topic']['messages'][$row->ID_MSG])) {
			$context['topic']['messages'][$row->ID_MSG] = array (
			'id' => $row->ID_MSG,
			'time' => Forum::timeformat($row->posterTime),
			'timestamp' => Forum::forum_time($row->posterTime),
			'member' => array(
				'id' => $row->ID_MEMBER,
				'name' => $row->posterName,
				'href' => sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER),
				'link' => '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER).'">'.$row->posterName.'</a>',
				'cinsiyet' => $row->cinsiyet == 1 ? 'Erkek' : 'Bayan',
				'profilimage' => $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png',
				'imagelink' => $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" width="100" height="100" />' : '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" width="100" height="100" />',
				'sehir' => $row->sehirAdi,
				'ip' => $row->posterIP,
				'lastvisit' => $row->lastvisit,
				'mezuniyet' => $row->myili
			),
			'subject' => $row->subject,
			'body' => $row->body
			);
		}
		
		} //end foreach
		
		return $context['topic'];
	}
}
