<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(mosGetParam($_REQUEST, 'id'));
$limit = intval(mosGetParam($_REQUEST, 'limit', 10));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	ForumIndex();
	break;
	
	case 'board':
	Board($id);
	break;
	
	case 'topic':
	Topic($id);
	break;
	
	case 'message':
	Message($id);
	break;
	
	case 'newtopic':
	createNewTopic();
	break;
	
	case 'newmessage':
	createNewMessage();
	break;
}

function createNewMessage() {
	global $dbase, $my;
	
	$msgOptions = new stdClass();
	$content = $_POST;
	
	$msgOptions->ID_MSG = null;
	$msgOptions->ID_TOPIC = $content['ID_TOPIC'];
	$msgOptions->ID_BOARD = $content['ID_BOARD'];
	$msgOptions->posterTime = time();
	$msgOptions->ID_MEMBER = $my->id;
	$msgOptions->ID_MSG_MODIFIED = null;
	$msgOptions->posterIP = $_SERVER['REMOTE_ADDR'];
	$msgOptions->subject = $content['subject'];
	$msgOptions->body = $content['body'];
	
	$query = "INSERT INTO #__forum_messages (ID_TOPIC, ID_BOARD, posterTime, ID_MEMBER, posterIP, subject, body) VALUES (".$dbase->Quote($msgOptions->ID_TOPIC).", ".$dbase->Quote($msgOptions->ID_BOARD).",".$dbase->Quote($msgOptions->posterTime).",".$dbase->Quote($msgOptions->ID_MEMBER).",".$dbase->Quote($msgOptions->posterIP).",".$dbase->Quote($msgOptions->subject).",".$dbase->Quote($msgOptions->body).")";
	$dbase->setQuery($query);
	$dbase->query();
	$msgOptions->ID_MSG = $dbase->insertid();
	
	if (isset($msgOptions->ID_MSG)) {
	//Updateler
	$dbase->setQuery("UPDATE #__forum_messages 
	ID_MSG_MODIFIED = ".$dbase->Quote($msgOptions->ID_MSG)." WHERE ID_MSG=".$dbase->Quote($msgOptions->ID_MSG)."");
	$dbase->query();
	
	//topic sayacı
	$dbase->setQuery("UPDATE #__forum_topics SET ID_LAST_MSG = ".$dbase->Quote($msgOptions->ID_MSG).", numReplies = numReplies + 1 WHERE ID_TOPIC = ".$dbase->Quote($msgOptions->ID_TOPIC)."");
	$dbase->query();
	
	//board sayacı
	$dbase->setQuery("UPDATE #__forum_boards SET ID_LAST_MSG = ".$dbase->Quote($msgOptions->ID_MSG).", ID_MSG_UPDATED = ".$dbase->Quote($msgOptions->ID_MSG).", numPosts = numPosts + 1 WHERE ID_BOARD = ".$dbase->Quote($msgOptions->ID_BOARD)."");
	$dbase->query();
	}
	
	//logları güncelle
	$dbase->setQuery("UPDATE #__forum_log_boards SET ID_MEMBER = ".$dbase->Quote($msgOptions->ID_MEMBER).", ID_BOARD = ".$dbase->Quote($msgOptions->ID_BOARD).", ID_MSG = ".$dbase->Quote($msgOptions->ID_MSG)."");
	$dbase->query();
	
	$dbase->setQuery("UPDATE #__forum_log_topics SET ID_TOPIC = ".$dbase->Quote($msgOptions->ID_TOPIC).", ID_MEMBER = ".$dbase->Quote($msgOptions->ID_MEMBER).", ID_MSG = ".$dbase->Quote($msgOptions->ID_MSG)."");
	$dbase->query();
	
	mosRedirect('index.php?option=site&bolum=forum&task=topic&id='.$msgOptions->ID_TOPIC);
}

function createNewTopic() {
	global $dbase, $my;
	
	$msgOptions = new stdClass();
	$topicOptions = new stdClass();
	$content = $_POST;
	
	$msgOptions->ID_MSG = null;
	$msgOptions->ID_TOPIC = null;
	$msgOptions->ID_BOARD = $content['ID_BOARD'];
	$msgOptions->posterTime = time();
	$msgOptions->ID_MEMBER = $my->id;
	$msgOptions->ID_MSG_MODIFIED = null;
	$msgOptions->posterIP = $_SERVER['REMOTE_ADDR'];
	$msgOptions->subject = $content['subject'];
	$msgOptions->body = $content['body'];
	
	$topicOptions->ID_TOPIC = null;
	$topicOptions->isSticky = $content['isSticky'];
	$topicOptions->ID_BOARD = $content['ID_BOARD'];
	$topicOptions->ID_FIRST_MSG = $my->id;
	$topicOptions->ID_LAST_MSG = $my->id;
	$topicOptions->numReplies = null;
	$topicOptions->numViews = null;
	$topicOptions->locked = $content['locked'];
	
	$new_topic = empty($topicOptions->ID_TOPIC);
	//mesajı sokalım
	$query = "INSERT INTO #__forum_messages "
	. "\n (ID_BOARD, posterTime, ID_MEMBER, posterIP, subject, body) "
	. "\n VALUES(".$dbase->Quote($msgOptions->ID_BOARD).", ".$dbase->Quote($msgOptions->posterTime).", ".$dbase->Quote($msgOptions->ID_MEMBER).", ".$dbase->Quote($msgOptions->posterIP).", ".$dbase->Quote($msgOptions->subject).", ".$dbase->Quote($msgOptions->body).")";
	$dbase->setQuery($query);
	$dbase->query();
	$msgOptions->ID_MSG = $dbase->insertid();
	
	if (empty($msgOptions->ID_MSG)) {
		return false;
	}
	//başlığı sokalım
	$query = "INSERT INTO #__forum_topics "
	. "\n (ID_BOARD, ID_FIRST_MSG, ID_LAST_MSG, locked, isSticky, numViews) "
	. "\n VALUES (".$dbase->Quote($topicOptions->ID_BOARD).", ".$dbase->Quote($msgOptions->ID_MSG).", ".$dbase->Quote($msgOptions->ID_MSG).", ".$dbase->Quote($topicOptions->locked).", ".$dbase->Quote($topicOptions->isSticky).", ".$dbase->Quote($topicOptions->numViews).")"
	;
	$dbase->setQuery($query);
	$dbase->query();		
	$topicOptions->ID_TOPIC = $dbase->insertid();
	
	//başlık oluşturulamazsa eklenen mesajı da silelim
	if (empty($topicOptions->ID_TOPIC)) {
	// We should delete the post that did work, though...
	$dbase->setQuery("DELETE FROM #__forum_messages WHERE ID_MSG = ".$msgOptions->ID_MSG." LIMIT 1");
	$dbase->query();
	return false;
	}
	
	//mesajı düzeltelim
	$dbase->setQuery("UPDATE #__forum_messages SET ID_TOPIC = ".$topicOptions->ID_TOPIC.", ID_MSG_MODIFIED = ".$msgOptions->ID_MSG." WHERE ID_MSG = ".$msgOptions->ID_MSG." LIMIT 1");
	$dbase->query();
	
	//board sayaçlarını düzeltelim
	$dbase->setQuery("UPDATE #__forum_boards
		SET numPosts = numPosts + 1, numTopics = numTopics + 1, ID_LAST_MSG = ".$msgOptions->ID_MSG.", ID_MSG_UPDATED = ".$msgOptions->ID_MSG."
		WHERE ID_BOARD = ".$topicOptions->ID_BOARD." LIMIT 1");
		$dbase->query();
		
		//log alalım
		$dbase->setQuery("REPLACE INTO #__forum_log_topics 
		(ID_TOPIC, ID_MEMBER, ID_MSG) 
		VALUES ($topicOptions->ID_TOPIC, $my->id, $msgOptions->ID_MSG + 1)");
		$dbase->query();
	
	mosRedirect('index.php?option=site&bolum=forum&task=topic&id='.$topicOptions->ID_TOPIC);
}

function Topic($id) {
	global $dbase, $limit, $limitstart, $my;
	
	$topics = new BoardTopics($dbase);
	
	$rows = $topics->TopicIndex($id, $limitstart, $limit);
	$topic_info = $topics->TopicInfo($id);

	$total = $topic_info->numReplies;
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages");
		$maxMsg = $dbase->loadResult();
		if (!$maxMsg) {
		  $maxMsg = 1;
		}
		
		/**
		* Topic kaydı yapalım 
		*/
		$query = "REPLACE INTO #__forum_log_topics "
		. "\n (ID_MSG, ID_MEMBER, ID_TOPIC) "
		. "\n VALUES (".$dbase->Quote($maxMsg).", ".$my->id.", ".$dbase->Quote($id).")";
		$dbase->setQuery($query);
		$dbase->query();
		
		//Okunmayı arttıralım
		if (empty($_COOKIE['last_read_topic']) || $_COOKIE['last_read_topic'] != $id) {
		$dbase->setQuery("UPDATE #__forum_topics SET numViews = numViews + 1 WHERE ID_TOPIC = ".$id." LIMIT 1");
		$dbase->query();

		setcookie('last_read_topic', $id);
	}
	
	ForumHTML::TopicSeen($rows, $pageNav, $topic_info);
	
}

function ForumIndex() {
	global $dbase;
	
	$categories = new BoardCategories($dbase);
	
	$context['categories'] = $categories->ForumIndex();
	
	ForumHTML::BoardIndex($context);
}

function Board($id) {
	global $dbase, $limit, $limitstart, $my;
	
	$boards = new BoardCategories($dbase);
	$topics = new Boards($dbase);
	
	$context['boards'] = $boards->Board($id);
	$context['topics'] = $topics->BoardTopics($id, $limitstart, $limit);
	$board_info = $topics->BoardInfo($id);
	
	$total = $board_info->numTopics;
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages");
	$maxMsg = $dbase->loadResult();
	if (!$maxMsg) {
		$maxMsg = 1;
	}
	
	$dbase->setQuery("REPLACE INTO #__forum_log_boards "
	. "\n (ID_MSG, ID_MEMBER, ID_BOARD) VALUES (".$dbase->Quote($maxMsg).", ".$dbase->Quote($my->id).", ".$dbase->Quote($id).")");
	$dbase->query();
	
	if (!empty($board_info->parent_boards)) {
	$dbase->setQuery("UPDATE #__forum_log_boards "
	. "\n SET ID_MSG = ".$maxMsg
	. "\n WHERE ID_MEMBER = ".$my->id
	. "\n AND ID_BOARD IN (" . implode(', ', array_keys($board_info->parent_boards)) . ")"
	. "\n LIMIT " . count($board_info->parent_boards));
	$dbase->query();
	}
		
	ForumHTML::BoardSeen($context, $pageNav, $board_info);
}
