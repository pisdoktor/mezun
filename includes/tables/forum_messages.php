<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class BoardMessages extends DBTable {
	
	var $ID_MSG = null;
	
	var $ID_TOPIC = null;
	
	var $ID_BOARD = null;
	
	var $posterTime = null;
	
	var $ID_MEMBER = null;
	
	var $ID_MSG_MODIFIED = null;
	
	var $posterIP = null;
	
	var $subject = null;
	
	var $body = null;
	
	function BoardMessages( &$db ) {
		$this->DBTable( '#__forum_messages', 'ID_MSG', $db );
	}
	
	function maxMsgID() {
		$this->_db->setQuery("SELECT MAX(ID_MSG) FROM ".$this->_tbl);
		
		return $this->_db->loadResult();
	}
	
	function latestMessages($showlatestcount=5, $limit) {
		
	$query = "
		SELECT
			m.posterTime, m.subject, m.ID_TOPIC, m.ID_MEMBER, m.ID_MSG,
			mem.name AS posterName, t.ID_BOARD, b.name AS bName,
			LEFT(m.body, 384) AS body, t.numReplies
		FROM (#__forum_messages AS m, #__forum_topics AS t, #__forum_boards AS b)
			LEFT JOIN #__users AS mem ON (mem.id = m.ID_MEMBER)
		WHERE m.ID_MSG >= " . max(0, $this->maxMsgID() - 20 * $showlatestcount) . "
			AND t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
		ORDER BY m.ID_MSG DESC
		LIMIT ".$showlatestcount;
		$this->_db->setQuery($query);
		$request = $this->_db->query();
		
	$posts = array();
	
	while ($row = mysql_fetch_assoc($request)) {
		if (strlen($row['body']) > 128)
			$row['body'] = substr($row['body'], 0, 128) . '...';

		// Build the array.
		$posts[] = array(
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['bName'],
				'href' => 'index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD'],
				'link' => '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD'] . '">' . $row['bName'] . '</a>'
			),
			'topic' => $row['ID_TOPIC'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : 'index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER'] . '">' . $row['posterName'] . '</a>'
			),
			'subject' => $row['subject'],
			'short_subject' => Forum::shorten_subject($row['subject'], 24),
			'preview' => $row['body'],
			'time' => Forum::timeformat($row['posterTime']),
			'timestamp' => Forum::forum_time(true, $row['posterTime']),
			'raw_timestamp' => $row['posterTime'],
			'href' => 'index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC']. ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new',
			'link' => '<a href="index.php?option=site&bolum=forum&task=topic&id='.$row['ID_TOPIC']. ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new">' . $row['subject'] . '</a>'
		);
	}
	mysql_free_result($request);

	return $posts;
	}
}