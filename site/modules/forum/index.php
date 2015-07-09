<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(getParam($_REQUEST, 'id'));

$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));


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
	newTopic();
	break;
	
	case 'newmessage':
	newMessage();
	break;
	
	case 'savetopic':
	createNewTopic();
	break;
	
	case 'savemessage':
	createNewMessage();
	break;
}

function newMessage() {
	global $dbase, $my;
	
	$ID_TOPIC = intval(getParam($_REQUEST, 'topic'));
	
	$topic = new BoardTopic($dbase);
	$board = new Boards($dbase);
	
	$topic->load($ID_TOPIC);
	
	if (!$topic->ID_TOPIC) {
		NotAuth();
		return;
	}
	
	$topic_info = $topic->TopicInfo($ID_TOPIC);
	$board_info = $board->BoardInfo($topic->ID_BOARD);
	
	ForumHTML::newMessage($topic, $my, $topic_info, $board_info);
}

function newTopic() {
	global $dbase, $my;
	
	$ID_BOARD = intval(getParam($_REQUEST, 'board'));
	
	$board = new Boards($dbase);
	$board->load($ID_BOARD);
	
	if (!$board->ID_BOARD) {
		NotAuth();
		return;
	}
	
	$board_info = $board->BoardInfo($board->ID_BOARD);
	
	$icon = array();
	$icon[] = mosHTML::makeOption('xx', 'Standart');
	$icon[] = mosHTML::makeOption('thumbup', 'Thumb Up');
	$icon[] = mosHTML::makeOption('thumbdown', 'Thumb Down');
	$icon[] = mosHTML::makeOption('exclamation', 'Exclamation');
	$icon[] = mosHTML::makeOption('question', 'Question');
	$icon[] = mosHTML::makeOption('lamp', 'Lamp');
	$icon[] = mosHTML::makeOption('smiley', 'Smiley');
	$icon[] = mosHTML::makeOption('angry', 'Angry');
	$icon[] = mosHTML::makeOption('cheesy', 'Cheesy');
	$icon[] = mosHTML::makeOption('grin', 'Grin');
	$icon[] = mosHTML::makeOption('sad', 'Sad');
	$icon[] = mosHTML::makeOption('wink', 'Wink');
	$icon[] = mosHTML::makeOption('solved', 'Solved');
	
	$list['icons'] = mosHTML::selectList($icon, 'icon', 'id="icon" onchange="showimage()"', 'value', 'text', 'xx');
	
	ForumHTML::newTopic($board, $my, $board_info, $list);
}

function createNewMessage() {
	global $dbase, $my, $limit, $limitstart;
	
	
	$msgOptions = new stdClass();
	$content = $_POST;
	
	$msgOptions->ID_MSG = null;
	$msgOptions->ID_TOPIC = $content['ID_TOPIC'];
	$msgOptions->ID_BOARD = $content['ID_BOARD'];
	$msgOptions->posterTime = time();
	$msgOptions->ID_MEMBER = $my->id;
	$msgOptions->ID_MSG_MODIFIED = null;
	$msgOptions->posterIP = $_SERVER['REMOTE_ADDR'];
	$msgOptions->subject = trim($content['subject']);
	$msgOptions->body = nl2br($content['body']);
	
	$query = "INSERT INTO #__forum_messages (ID_TOPIC, ID_BOARD, posterTime, ID_MEMBER, posterIP, subject, body) VALUES (".$dbase->Quote($msgOptions->ID_TOPIC).", ".$dbase->Quote($msgOptions->ID_BOARD).",".$dbase->Quote($msgOptions->posterTime).",".$dbase->Quote($msgOptions->ID_MEMBER).",".$dbase->Quote($msgOptions->posterIP).",".$dbase->Quote($msgOptions->subject).",".$dbase->Quote($msgOptions->body).")";
	$dbase->setQuery($query);
	$dbase->query();
	$msgOptions->ID_MSG = $dbase->insertid();
	
	if (isset($msgOptions->ID_MSG)) {
	//Updateler
	$dbase->setQuery("UPDATE #__forum_messages SET ID_MSG_MODIFIED = ".$dbase->Quote($msgOptions->ID_MSG)." WHERE ID_MSG=".$dbase->Quote($msgOptions->ID_MSG)."");
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
	
	$topic = new BoardTopic($dbase);
	$topic_info = $topic->TopicInfo($msgOptions->ID_TOPIC);
	
	$link = 'index.php?option=site&bolum=forum&task=topic&id=' . $msgOptions->ID_TOPIC . ($topic_info->numReplies > $limit ? '&limit='.$limit.'&limitstart='.((floor($topic_info->numReplies/ $limit)) * $limit) : '') . '#new';
	
	Redirect($link);
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
	$msgOptions->subject = trim($content['subject']);
	$msgOptions->body = nl2br($content['body']);
	
	$topicOptions->ID_TOPIC = null;
	$topicOptions->isSticky = $content['isSticky'];
	$topicOptions->ID_BOARD = $content['ID_BOARD'];
	$topicOptions->ID_FIRST_MSG = $my->id;
	$topicOptions->ID_LAST_MSG = $my->id;
	$topicOptions->numReplies = 1;
	$topicOptions->numViews = null;
	$topicOptions->locked = $content['locked'];
	$topicOptions->icon = $content['icon'];
	
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
	. "\n (ID_BOARD, ID_FIRST_MSG, ID_LAST_MSG, locked, isSticky, numViews, numReplies, icon) "
	. "\n VALUES (".$dbase->Quote($topicOptions->ID_BOARD).", ".$dbase->Quote($msgOptions->ID_MSG).", ".$dbase->Quote($msgOptions->ID_MSG).", ".$dbase->Quote($topicOptions->locked).", ".$dbase->Quote($topicOptions->isSticky).", ".$dbase->Quote($topicOptions->numViews).", ".$dbase->Quote($topicOptions->numReplies).", ".$dbase->Quote($topicOptions->icon).")"
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
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$topicOptions->ID_TOPIC);
}

function Topic($id) {
	global $dbase, $my;
	
	$topiclimit = intval(getParam($_REQUEST, 'limit', 10));
	$topicstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$topics = new BoardTopic($dbase);
	$controlid = $topics->load($id);
	
	if (!$controlid) {
		NotAuth();
		return;
	}
	$board = new Boards($dbase);
		
	$context['topic'] = $topics->TopicIndex($id, $topicstart, $topiclimit);
	$topic_info = $topics->TopicInfo($id);
	$board_info = $board->BoardInfo($topic_info->ID_BOARD);


	$total = $topic_info->numReplies;
	
	$pageNav = new pageNav($total, $topicstart, $topiclimit);
	
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
		
		//board kaydı yapalım
		$dbase->setQuery("REPLACE INTO #__forum_log_boards "
	. "\n (ID_MSG, ID_MEMBER, ID_BOARD) VALUES (".$dbase->Quote($maxMsg).", ".$dbase->Quote($my->id).", ".$dbase->Quote($topic_info->ID_BOARD).")");
	$dbase->query();
	
	if (!empty($board_info->parent_boards)) {
	$dbase->setQuery("UPDATE #__forum_log_boards "
	. "\n SET ID_MSG = ".$maxMsg
	. "\n WHERE ID_MEMBER = ".$my->id
	. "\n AND ID_BOARD IN (" . implode(', ', array_keys($board_info->parent_boards)) . ")"
	. "\n LIMIT " . count($board_info->parent_boards));
	$dbase->query();
	}
		
		//Okunmayı arttıralım
		if (empty($_COOKIE['last_read_topic']) || $_COOKIE['last_read_topic'] != $id) {
		$dbase->setQuery("UPDATE #__forum_topics SET numViews = numViews + 1 WHERE ID_TOPIC = ".$id." LIMIT 1");
		$dbase->query();

		setcookie('last_read_topic', $id);
	}
	
	ForumHTML::TopicSeen($context, $pageNav, $topic_info, $board_info);
	
}

function ForumIndex() {
	global $dbase, $limit;
	
	$categories = new BoardCategories($dbase);
	$latestposts = new BoardMessages($dbase);
	$context['latestmsg'] = $latestposts->latestMessages(latestPostCount, $limit);
	
	$context['categories'] = $categories->ForumIndex();
	
	ForumHTML::BoardIndex($context);
}

function Board($id) {
	global $dbase, $my, $limit, $limitstart;
	
	$topiclimit = intval(getParam($_REQUEST, 'topiclimit', 10));
	$topicstart = intval(getParam($_REQUEST, 'topicstart', 0));
	
	$boards = new BoardCategories($dbase);
	$topics = new Boards($dbase);
	$maxID = new BoardMessages($dbase);
	
	$topics->load($id);
	if (!$topics->ID_BOARD) {
		NotAuth();
		return;
	}
	
	//Board içerisindeki alt boardlar
	$context['boards'] = $boards->Board($id);
	
	//Board içerisindeki topicler
	$context['topics'] = $topics->BoardTopics($id, $topicstart, $topiclimit, $limitstart, $limit);
	$board_info = $topics->BoardInfo($id);
	
	//Board içerisindeki topicleri sayfalandıralım
	$total = $board_info->numTopics;
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	//max msg id değerini alalım
	$maxMsg = $maxID->maxMsgID();
	
	if (!$maxMsg) {
		$maxMsg = 1;
	}
	
	//board u ve üst bordları okundu olarak işaretleyelim
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
		
	//içeriği gösterelim    
	ForumHTML::BoardSeen($context, $pageNav, $board_info);
}
