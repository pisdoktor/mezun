<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Boards extends DBTable {
	
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
	
	function Boards( &$db ) {
		$this->DBTable( '#__forum_boards', 'ID_BOARD', $db );
	}
	
	function BoardInfo($oid) {
		global $dbase;
		$dbase->setQuery("SELECT * FROM #__forum_boards WHERE ID_BOARD=".$oid);
		$dbase->loadObject($board_info);
		
		$board_info->parent_boards = Forum::getBoardParents($board_info->ID_BOARD);
	
		return $board_info;
	}
	
	function BoardTopics($id, $limitstart, $limit) {
		global $dbase, $my;
		
		$query = "SELECT t.ID_TOPIC, t.numReplies, t.locked, t.numViews, t.isSticky, "
		. "\n IFNULL(lt.ID_MSG, IFNULL(lmr.ID_MSG, -1)) + 1 AS new_from, "
		. "\n t.ID_LAST_MSG, ml.posterTime AS lastPosterTime, ml.ID_MSG_MODIFIED, "
		. "\n ml.subject AS lastSubject, meml.name AS lastMemberName, "
		. "\n ml.ID_MEMBER AS lastID_MEMBER, meml.name AS lastDisplayName, "
		. "\n t.ID_FIRST_MSG, mf.posterTime AS firstPosterTime, "
		. "\n mf.subject AS firstSubject, memf.name AS firstMemberName, "
		. "\n mf.ID_MEMBER AS firstID_MEMBER, memf.name AS firstDisplayName, "
		. "\n LEFT(ml.body, 384) AS lastBody, LEFT(mf.body, 384) AS firstBody "
		. "\n FROM (#__forum_topics AS t, #__forum_messages AS ml, #__forum_messages AS mf) "
		. "\n LEFT JOIN #__users AS meml ON (meml.id = ml.ID_MEMBER) "
		. "\n LEFT JOIN #__users AS memf ON (memf.id = mf.ID_MEMBER) " 
		. "\n LEFT JOIN #__forum_log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = ".$my->id.")"
		. "\n LEFT JOIN #__forum_log_mark_read AS lmr ON (lmr.ID_BOARD = ".$id." AND lmr.ID_MEMBER = ".$my->id.")"
		. "\n WHERE t.ID_BOARD = ".$id." AND ml.ID_MSG = t.ID_LAST_MSG AND mf.ID_MSG = t.ID_FIRST_MSG "
		. "\n ORDER BY t.isSticky DESC, ml.posterTime DESC";
		
		$dbase->setQuery($query, $limitstart, $limit);
		$result = $dbase->query();
		
		while ($row = mysql_fetch_assoc($result)) {
		
		// Limit them to 128 characters - do this FIRST because it's a lot of wasted censoring otherwise.
			if (strlen($row['firstBody']) > 128)
				$row['firstBody'] = substr($row['firstBody'], 0, 128) . '...';

			if (strlen($row['lastBody']) > 128)
				$row['lastBody'] = substr($row['lastBody'], 0, 128) . '...';

			// Don't censor them twice!
			if ($row['ID_FIRST_MSG'] == $row['ID_LAST_MSG'])
			{
				$row['lastSubject'] = $row['firstSubject'];
				$row['lastBody'] = $row['firstBody'];
			}
			// 'Print' the topic info.
			$context['topics'][$row['ID_TOPIC']] = array(
				'id' => $row['ID_TOPIC'],
				'first_post' => array(
					'id' => $row['ID_FIRST_MSG'],
					'member' => array(
						'username' => $row['firstMemberName'],
						'name' => $row['firstDisplayName'],
						'id' => $row['firstID_MEMBER'],
						'href' => !empty($row['firstID_MEMBER']) ? 'index.php?option=site&bolum=profil&task=show&id=' . $row['firstID_MEMBER'] : '',
						'link' => !empty($row['firstID_MEMBER']) ? '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row['firstID_MEMBER'] . '" title="' . $row['firstDisplayName'] . '">' . $row['firstDisplayName'] . '</a>' : $row['firstDisplayName']
					),
					'time' => Forum::timeformat($row['firstPosterTime']),
					'timestamp' => Forum::forum_time($row['firstPosterTime']),
					'subject' => $row['firstSubject'],
					'preview' => $row['firstBody'],
					'href' => 'index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'],
					'link' => '<a href="index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . '">' . $row['firstSubject'] . '</a>'
				),
				'last_post' => array(
					'id' => $row['ID_LAST_MSG'],
					'member' => array(
						'username' => $row['lastMemberName'],
						'name' => $row['lastDisplayName'],
						'id' => $row['lastID_MEMBER'],
						'href' => !empty($row['lastID_MEMBER']) ? 'index.php?option=site&bolum=profil&task=show&id=' . $row['lastID_MEMBER'] : '',
						'link' => !empty($row['lastID_MEMBER']) ? '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row['lastID_MEMBER'] . '">' . $row['lastDisplayName'] . '</a>' : $row['lastDisplayName']
					),
					'time' => Forum::timeformat($row['lastPosterTime']),
					'timestamp' => Forum::forum_time($row['lastPosterTime']),
					'subject' => $row['lastSubject'],
					'preview' => $row['lastBody'],
					//son mesaja link verebilmeyi yapalım
					'href' => 'index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new',
					'link' => '<a href="index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . ($row['numReplies'] > $limit ? '&msgid=' . $row['ID_LAST_MSG'] : '') . '#new">' . $row['lastSubject'] . '</a>'
				),
				'is_sticky' => !empty($row['isSticky']),
				'is_locked' => !empty($row['locked']),
				'is_hot' => $row['numReplies'] >= hotTopicPosts,
				'is_very_hot' => $row['numReplies'] >= hotTopicVeryPosts,
				'is_posted_in' => false,
				'subject' => $row['firstSubject'],
				'new' => $row['new_from'] <= $row['ID_MSG_MODIFIED'],
				'new_from' => $row['new_from'],
				'newtime' => $row['new_from'],
				'new_href' => 'index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . '&msgid=' . $row['new_from'] . '#new',
				'replies' => $row['numReplies'],
				'views' => $row['numViews'],
				'pages' => ($row['numReplies'] > $limit ? 'Sayfalar:'.Forum::constructPageIndex('index.php?option=site&bolum=forum&task=topic&id='.$row['ID_TOPIC'], $row['numReplies'], $limitstart, $limit) : '')
			);
		}
		if (isset($context['topics'])) {
			return $context['topics']; 
		} else {
			return false;
		} 
		mysql_free_result($result);
		
	}
}