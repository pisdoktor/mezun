<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(getParam($_REQUEST, 'id'));

$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));


include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.forum.forum');
mimport('helpers.modules.forum.helper');

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
	
	$topicid = intval(getParam($_REQUEST, 'topic'));
	
	mimport('tables.forumtopic');
	$topic = new mezunForumtopic($dbase);
	$topic->load($topicid);
	
	if (!$topic->ID_TOPIC) {
		NotAuth();
		return;
	}
	
	$topic_info = mezunForum::TopicInfo($topic->ID_TOPIC);
	$board_info = mezunForum::BoardInfo($topic->ID_BOARD);
	
	ForumHTML::newMessage($topic, $my, $topic_info, $board_info);
}

function newTopic() {
	global $dbase, $my;
	
	$boardid = intval(getParam($_REQUEST, 'board'));
	
	mimport('tables.forumboard');
	$board = new mezunForumboard($dbase);
	$board->load($boardid);
	
	if (!$board->ID_BOARD) {
		NotAuth();
		return;
	}
	
	$board_info = mezunForum::BoardInfo($board->ID_BOARD);
	
	$icon = array();
	$icon[] = mezunHTML::makeOption('xx', 'Standart');
	$icon[] = mezunHTML::makeOption('thumbup', 'Thumb Up');
	$icon[] = mezunHTML::makeOption('thumbdown', 'Thumb Down');
	$icon[] = mezunHTML::makeOption('exclamation', 'Exclamation');
	$icon[] = mezunHTML::makeOption('question', 'Question');
	$icon[] = mezunHTML::makeOption('lamp', 'Lamp');
	$icon[] = mezunHTML::makeOption('smiley', 'Smiley');
	$icon[] = mezunHTML::makeOption('angry', 'Angry');
	$icon[] = mezunHTML::makeOption('cheesy', 'Cheesy');
	$icon[] = mezunHTML::makeOption('grin', 'Grin');
	$icon[] = mezunHTML::makeOption('sad', 'Sad');
	$icon[] = mezunHTML::makeOption('wink', 'Wink');
	$icon[] = mezunHTML::makeOption('solved', 'Solved');
	
	$list['icons'] = mezunHTML::selectList($icon, 'icon', 'id="icon" onchange="showimage()"', 'value', 'text', 'xx');
	
	ForumHTML::newTopic($board, $my, $board_info, $list);
}

function createNewMessage() {
	global $dbase, $my, $limit, $limitstart;
	
	$ID_TOPIC = intval(getParam($_REQUEST, 'ID_TOPIC'));
	$ID_BOARD = intval(getParam($_REQUEST, 'ID_BOARD'));
	$subject = trim(getParam($_REQUEST, 'subject'));
	$body = nl2br(getParam($_REQUEST, 'body', '', _ALLOWRAW));
	$body = mezunForumHelper::makesafeHTML($body);
	
	mimport('tables.forummessage');
	$msg = new mezunForummessage($dbase);
	
	$msg->ID_TOPIC = $ID_TOPIC;
	$msg->ID_BOARD = $ID_BOARD;
	$msg->posterTime = time();
	$msg->ID_MEMBER = $my->id;
	$msg->posterIP = $_SERVER['REMOTE_ADDR'];
	$msg->subject = $subject;
	$msg->body = $body;
	
	$msg->store();
	
	$ID_MSG = $dbase->insertid();
	
	if (isset($ID_MSG)) {
		$update = new mezunForummessage($dbase);
		$update->ID_MSG = $ID_MSG;
		$update->ID_MSG_MODIFIED = $ID_MSG;
		$update->store();
		
		mimport('tables.forumtopic');
		$topic = new mezunForumtopic($dbase);
		$topic->load($ID_TOPIC);
		
		$tupdate = new mezunForumtopic($dbase);
		$tupdate->ID_TOPIC = $ID_TOPIC;
		$tupdate->ID_LAST_MSG = $ID_MSG;
		$tupdate->numReplies = $topic->numReplies+1;
		$tupdate->store();
		
		mimport('tables.forumboard');
		$board = new mezunForumboard($dbase);
		$board->load($ID_BOARD);
		
		$bupdate = new mezunForumboard($dbase);
		$bupdate->ID_BOARD = $ID_BOARD;
		$bupdate->ID_LAST_MSG = $ID_MSG;
		$bupdate->ID_MSG_UPDATED = $ID_MSG;
		$bupdate->numPosts = $board->numPosts+1;
		$bupdate->store();
	}
	
	//logları güncelle
	$dbase->setQuery("UPDATE #__forum_log_boards SET ID_MEMBER = ".$dbase->Quote($msg->ID_MEMBER).", ID_BOARD = ".$dbase->Quote($ID_BOARD).", ID_MSG = ".$dbase->Quote($ID_MSG)."");
	$dbase->query();
	
	$dbase->setQuery("UPDATE #__forum_log_topics SET ID_TOPIC = ".$dbase->Quote($ID_TOPIC).", ID_MEMBER = ".$dbase->Quote($msg->ID_MEMBER).", ID_MSG = ".$dbase->Quote($ID_MSG)."");
	$dbase->query();
	
	$topic_info = mezunForum::TopicInfo($ID_TOPIC);
	
	$link = 'index.php?option=site&bolum=forum&task=topic&id=' . $ID_TOPIC . ($topic_info->numReplies > $limit ? '&limit='.$limit.'&limitstart='.((floor($topic_info->numReplies / $limit)) * $limit) : '') . '#new';
	
	$akistext = '<a href="index.php?option=site&bolum=forum&task=topic&id='.$ID_TOPIC.'">'.$topic_info->subject.'</a> başlığına bir mesaj gönderdi';
			mezunGlobalHelper::AkisTracker($akistext);
	
	Redirect($link);
}

function createNewTopic() {
	global $dbase, $my;
	
	$subject = trim(getParam($_REQUEST, 'subject'));
	
	$icon = getParam($_REQUEST, 'icon');
	
	$body = nl2br(getParam($_REQUEST, 'body', '', _ALLOWRAW));
	
	$body = mezunForumHelper::makesafeHTML($body);
	
	$boardid = intval(getParam($_REQUEST, 'ID_BOARD'));
	
	$isSticky = intval(getParam($_REQUEST, 'isSticky', 0));
	
	$locked = intval(getParam($_REQUEST, 'locked', 0));
	
	//mesaj initleyelim
	mimport('tables.forummessage');
	$msg = new mezunForummessage($dbase);
	
	$msg->ID_BOARD = $boardid;
	$msg->posterTime = time();
	$msg->ID_MEMBER = $my->id;
	$msg->posterIP = $_SERVER['REMOTE_ADDR'];
	$msg->subject = $subject;
	$msg->body = $body;
	$msg->store();
	
	$ID_MSG = $dbase->insertid();
	
	if (empty($ID_MSG)) {
		return false;
	}
	//topic initleyelim
	mimport('tables.forumtopic');
	$topic = new mezunForumtopic($dbase);
	
	$topic->ID_BOARD = $boardid;
	$topic->ID_FIRST_MSG = $ID_MSG;
	$topic->ID_LAST_MSG = $ID_MSG;
	$topic->numReplies = 1;
	$topic->numViews = 0;
	$topic->locked = $locked;
	$topic->isSticky = $isSticky;
	$topic->icon = $icon;
	$topic->store();
	
	$ID_TOPIC = $dbase->insertid();
	
	//başlık oluşturulamazsa eklenen mesajı da silelim
	if (empty($ID_TOPIC)) {
		$msg->delete($ID_MSG);
		return false;
	}
	
	//mesajı güncelleyelim
	$update = new mezunForummessage($dbase);
	$update->ID_MSG = $ID_MSG;	
	$update->ID_TOPIC = $ID_TOPIC;
	$update->ID_MSG_MODIFIED = $ID_MSG;
	$update->store();
	
	//board sayaçlarını güncelleyelim
	mimport('tables.forumboard');
	$board = new mezunForumboard($dbase);
	$board->ID_BOARD = $boardid;
	$board->numPosts = $board->numPosts+1;
	$board->numTopics = $board->numTopics+1;
	$board->ID_LAST_MSG = $ID_MSG;
	$board->ID_MSG_UPDATED = $ID_MSG;
	$board->store();
	
	//log alalım
	$dbase->setQuery("REPLACE INTO #__forum_log_topics 
	(ID_TOPIC, ID_MEMBER, ID_MSG) 
	VALUES ($ID_TOPIC, $my->id, $ID_MSG + 1)");
	$dbase->query();
	
	$akistext = '<a href="index.php?option=site&bolum=forum&task=topic&id='.$ID_TOPIC.'">'.$subject.'</a> başlıklı bir konu oluşturdu';
			mezunGlobalHelper::AkisTracker($akistext);
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$ID_TOPIC);
}

function Topic($id) {
	global $dbase, $my;
	
	mimport('helpers.modules.online.helper');
	
	$topiclimit = intval(getParam($_REQUEST, 'limit', 10));
	$topicstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	//topic var mı yok mu?
	mimport('tables.forumtopic');
	$topic = new mezunForumtopic($dbase);
	$oid = $topic->load($id);
	
	if (!$oid) {
		NotAuth();
		return;
	}
	
	//topic içerisindeki mesajları alalım	
	$context['topic'] = mezunForum::TopicIndex($topic->ID_TOPIC, $topicstart, $topiclimit);
	//topic bilgileri
	$topic_info = mezunForum::TopicInfo($topic->ID_TOPIC);
	//board bilgileri
	$board_info = mezunForum::BoardInfo($topic_info->ID_BOARD);
	//topic içerisindeki toplam mesaj sayısı
	$total = $topic_info->numReplies;
	
	$pageNav = new mezunPagenation($total, $topicstart, $topiclimit);
	
	$maxMsg = mezunForum::maxMsgID();
	
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
	
	$context['categories'] = mezunForum::ForumIndex();
	
	$context['latestmsg'] = mezunForum::latestMessages(latestPostCount, $limit);
	
	ForumHTML::BoardIndex($context);
}

function Board($id) {
	global $dbase, $my, $limit, $limitstart;
	
	$topiclimit = intval(getParam($_REQUEST, 'topiclimit', 10));
	$topicstart = intval(getParam($_REQUEST, 'topicstart', 0));
	
	mimport('tables.forumboard');
	$board = new mezunForumboard($dbase);
	
	$board->load($id);
	if (!$board->ID_BOARD) {
		NotAuth();
		return;
	}
	//board bilgileri
	$board_info = mezunForum::BoardInfo($id);
	
	//Board içerisindeki alt boardlar
	$context['boards'] = mezunForum::BoardIndex($board->ID_BOARD);
	
	//Board içerisindeki topicler
	$context['topics'] = mezunForum::BoardTopics($board->ID_BOARD, $topicstart, $topiclimit, $limitstart, $limit);
	
	//Board içerisindeki topicleri sayfalandıralım
	$total = $board_info->numTopics;
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	//max msg id değerini alalım
	$maxMsg = mezunForum::maxMsgID();
	
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
