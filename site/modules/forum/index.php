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
	//yeniler aşagıda
	case 'sticky':
	stickyTopic($id, $limit, $limitstart, 1);
	break;
	
	case 'unsticky':
	stickyTopic($id, $limit, $limitstart, 0);
	break;
	
	case 'lock':
	lockTopic($id, $limit, $limitstart, 1);
	break;
	
	case 'unlock':
	lockTopic($id, $limit, $limitstart, 0);
	break;
	
	case 'editmessage':
	editMessage($id, $limit, $limitstart);
	break;
	
	case 'savemessage2':
	saveMessage2();
	break;
	
	case 'deletemessage':
	deleteMessage($id);
	break;
	
	case 'deletetopic':
	deleteTopic($id);
	break;
	
	case 'cancelmessage2':
	cancelMessage2();
	break;
	
	case 'cancelmessage':
	cancelMessage();
	break;
}

function cancelMessage() {
	global $dbase;
	
	mimport('tables.forumtopic');
	
	$row = new mezunForumtopic($dbase);
	$row->bind($_POST);
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$row->ID_TOPIC);
}

function cancelMessage2() {
	global $dbase;
	
	mimport('tables.forummessage');
	
	$row = new mezunForummessage($dbase);
	$row->bind($_POST);
	
	$limit = intval(getParam($_REQUEST, 'limit'));
	$limitstart = intval(getParam($_REQUEST, 'limitstart'));
	
	
	if ($limit || $limitstart) {
		$link = '&limit='.$limit.'&limitstart='.$limitstart;
	} else {
		$link = '';
	}
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$row->ID_TOPIC.$link);
}

function saveTopic2() {
	
}

function saveMessage2() {
	global $dbase;
	
	mimport('tables.forummessage');
	
	$limit = intval(getParam($_REQUEST, 'limit'));
	$limitstart = intval(getParam($_REQUEST, 'limitstart'));
	
	$row = new mezunForummessage($dbase);
	$row->bind($_POST);
	
	$row->store();
	
	if ($limit || $limitstart) {
		$link = '&limit='.$limit.'&limitstart='.$limitstart;
	} else {
		$link = '';
	}
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$row->ID_TOPIC.$link.'#msg'.$row->ID_MSG);
}


function editMessage($id, $limit, $limitstart) {
	global $dbase;
	
	mimport('tables.forummessage');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunForummessage($dbase);
	$row->load($id);
	
	if (!$row->ID_MSG) {
		NotAuth();
		return;
	}
	
	if (!mezunForumHelper::canEditMessage($row->ID_MSG)) {
		NotAuth();
		return;
	}
	
	$topic_info = mezunForum::TopicInfo($row->ID_TOPIC);
	$board_info = mezunForum::BoardInfo($row->ID_BOARD);
	
	ForumHTML::editMessage($row, $topic_info, $board_info, $limit, $limitstart);
}

function deleteTopic($id) {
	global $dbase, $my;
	
	mimport('tables.forumtopic');
	mimport('tables.forummessage');
	mimport('tables.forumboard');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$topic = new mezunForumtopic($dbase);
	$topic->load($id);
	
	if (!$topic->ID_TOPIC) {
		NotAuth();
		return;
	}
	
	$board = new mezunForumboard($dbase);
	$board->load($topic->ID_BOARD);
	
	//topic içerisindeki mesajların bilgilerini alalım
	//toplam mesaj sayısı
	$dbase->setQuery("SELECT COUNT(ID_MSG) FROM #__forum_messages WHERE ID_TOPIC=".$dbase->Quote($topic->ID_TOPIC));
	
	$totalmsg = $dbase->loadResult();
	
	//topic delete!!
	$dbase->setQuery("DELETE FROM #__forum_topics WHERE ID_TOPIC=".$dbase->Quote($topic->ID_TOPIC));
	$dbase->query();
	
	//topic içerisindeki tüm mesajları silelim
	$dbase->setQuery("DELETE FROM #__forum_messages WHERE ID_TOPIC=".$dbase->Quote($topic->ID_TOPIC));
	$dbase->query();	
	
	//board içerisindeki max ID_MSG değerini bulalım
	$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages WHERE ID_BOARD=".$dbase->Quote($board->ID_BOARD));
	$maxmsgid = $dbase->loadResult();
	
	//maxmsgid sıfır ise o zaman alt forumlardaki en son mesajı bulalım
	if (!$maxmsgid) {
		//child forumları bulalım, dizi şeklinde id lerini alalım
		$dbase->setQuery("SELECT ID_BOARD FROM #__forum_boards WHERE ID_PARENT=".$board->ID_BOARD);
		$childs = $dbase->loadResultArray();

		foreach ($childs as $child) {
			$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages WHERE ID_BOARD=".$dbase->Quote($child));
			$maxmsg = $dbase->loadResult();
			$maxmsgid = max($maxmsgid, $maxmsg);
		}		
	}
	
	//board güncellemesi yapalım
	$board->numPosts = $board->numPosts-$totalmsg;
	$board->numTopics = $board->numTopics-1;
	$board->ID_LAST_MSG = $maxmsgid;
	$board->ID_MSG_UPDATED = $maxmsgid;
	$board->store();
	
	//board un parent boardlarında da güncelleme yapalım
	//parent boardların da ID_LAST_MSG ve ID_MSG_MODIFIED değerlerini değiştirelim
	$parents = mezunForumHelper::getBoardParents($board->ID_BOARD);
	foreach ($parents as $parent) {
		if ($parent>0) {
		$p = new mezunForumboard($dbase);
		$p->load($parent);
		$p->ID_LAST_MSG = $maxmsgid;
		$p->ID_MSG_UPDATED = $maxmsgid;
		$p->store();
		}
	}	
	
	//board geri dönüşü
	Redirect('index.php?option=site&bolum=forum&task=board&id='.$board->ID_BOARD);	
}

function deleteMessage($id) {
	global $dbase, $my;
	
	mimport('tables.forumtopic');
	mimport('tables.forummessage');
	mimport('tables.forumboard');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$msg = new mezunForummessage($dbase);
	$msg->load($id);
	
	if (!$msg->ID_MSG) {
		NotAuth();
		return;
	}
	
	if ($msg->ID_MEMBER != $my->id) {
		NotAuth();
		return;
	}
	
	$topic = new mezunForumtopic($dbase);
	$topic->load($msg->ID_TOPIC);
	
	$board = new mezunForumboard($dbase);
	$board->load($msg->ID_BOARD);
	
	//önce mesajı silelim
	$dbase->setQuery("DELETE FROM #__forum_messages WHERE ID_MSG=".$dbase->Quote($msg->ID_MSG));
	$dbase->query();
	
	//topic içerisindeki son mesajı bulalım
	$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages WHERE ID_TOPIC=".$dbase->Quote($topic->ID_TOPIC));
	$maxmsgid = $dbase->loadResult();
		
	//numReplies -1
	$topic->numReplies = $topic->numReplies-1;
	//ID_LAST_MSG değiştirelim
	$topic->ID_LAST_MSG = $maxmsgid;
	
	$topic->store();
	
	//numPost -1
	$board->numPosts = $board->numPosts-1;
	$board->ID_LAST_MSG = $maxmsgid;
	$board->ID_MSG_UPDATED = $maxmsgid;
	$board->store();
	
	//parent boardların da ID_LAST_MSG ve ID_MSG_MODIFIED değerlerini değiştirelim
	$parents = mezunForumHelper::getBoardParents($board->ID_BOARD);
	foreach ($parents as $parent) {
		if ($parent>0) {
		$p = new mezunForumboard($dbase);
		$p->load($parent);
		$p->ID_LAST_MSG = $maxmsgid;
		$p->ID_MSG_UPDATED = $maxmsgid;
		$p->store();
		}
	}
	
	
	//topic geri dönüş...
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$topic->ID_TOPIC);	
}

function stickyTopic($id, $limit, $limitstart, $status) {
	global $dbase, $my;
	
	mimport('tables.forumtopic');
	mimport('tables.forummessage');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunForumtopic($dbase);
	$row->load($id);
	
	if (!$row->ID_TOPIC) {
		NotAuth();
		return;
	}
	
	$msg = new mezunForummessage($dbase);
	$msg->load($row->ID_FIRST_MSG);
	
	if ($msg->ID_MEMBER == $my->id) {
		$row->isSticky = $status;
		$row->store();
	}
	
	if ($limit || $limitstart) {
		$link = '&limit='.$limit.'&limitstart='.$limitstart;
	} else {
		$link = '';
	}
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$row->ID_TOPIC.$link);	
}

function lockTopic($id, $limit, $limitstart, $status) {
	global $dbase, $my;
	
	mimport('tables.forumtopic');
	mimport('tables.forummessage');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunForumtopic($dbase);
	$row->load($id);
	
	if (!$row->ID_TOPIC) {
		NotAuth();
		return;
	}
	
	$msg = new mezunForummessage($dbase);
	$msg->load($row->ID_FIRST_MSG);
	
	if ($msg->ID_MEMBER == $my->id) {
		$row->locked = $status;
		$row->store();
	}
	
	if ($limit || $limitstart) {
		$link = '&limit='.$limit.'&limitstart='.$limitstart;
	} else {
		$link = '';
	}
	
	Redirect('index.php?option=site&bolum=forum&task=topic&id='.$row->ID_TOPIC.$link); 
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
		
		//üst boardların ID_LAST_MSG ile ID_MSG_UPDATED değerlerini güncelleyelim
		$parents = mezunForumHelper::getBoardParents($bupdate->ID_BOARD);
		foreach ($parents as $parent) {
			if ($parent>0) {
				$p = new mezunForumboard($dbase);
				$p->load($parent);
				$p->ID_LAST_MSG = $ID_MSG;
				$p->ID_MSG_UPDATED = $ID_MSG;
				$p->store();
			}
		}
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
	$board->load($boardid);
	$board->numPosts = $board->numPosts+1;
	$board->numTopics = $board->numTopics+1;
	$board->ID_LAST_MSG = $ID_MSG;
	$board->ID_MSG_UPDATED = $ID_MSG;
	$board->store();
	
	//üst boardların ID_LAST_MSG ile ID_MSG_UPDATED değerlerini güncelleyelim
	$parents = mezunForumHelper::getBoardParents($board->ID_BOARD);
	foreach ($parents as $parent) {
		if ($parent>0) {
		$p = new mezunForumboard($dbase);
		$p->load($parent);
		$p->ID_LAST_MSG = $ID_MSG;
		$p->ID_MSG_UPDATED = $ID_MSG;
		$p->store();
		}
	}
	
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
	
	
	$topiclink = array();
	
	if (mezunForumHelper::canStickyTopic($topic_info->ID_TOPIC)) {
		if ($topic_info->isSticky) {
			$topiclink['sticky'] = '<a href="index.php?option=site&bolum=forum&task=unsticky&id='.$topic_info->ID_TOPIC.($topic_info->numReplies > $topiclimit ? '&limit='.$topiclimit.'&limitstart='.$topicstart : '').'">Yapışkanı Kaldır</a>';
		} else {
			$topiclink['sticky'] = '<a href="index.php?option=site&bolum=forum&task=sticky&id='.$topic_info->ID_TOPIC.($topic_info->numReplies > $topiclimit ? '&limit='.$topiclimit.'&limitstart='.$topicstart : '').'">Yapışkan Yap</a>';
		}
	} else {
		$topiclink['sticky'] = '';
	}
	
	if (mezunForumHelper::canLockTopic($topic_info->ID_TOPIC)) {
		if ($topic_info->locked) {
			$topiclink['lock'] = '<a href="index.php?option=site&bolum=forum&task=unlock&id='.$topic_info->ID_TOPIC.($topic_info->numReplies > $topiclimit ? '&limit='.$topiclimit.'&limitstart='.$topicstart : '').'">Kilidi Kaldır</a>';
		} else {
			$topiclink['lock'] = '<a href="index.php?option=site&bolum=forum&task=lock&id='.$topic_info->ID_TOPIC.($topic_info->numReplies > $topiclimit ? '&limit='.$topiclimit.'&limitstart='.$topicstart : '').'">Kilitle</a>';
		}
	} else {
		$topiclink['lock'] = '';
	}
	
	if (mezunForumHelper::canDeleteTopic($topic_info->ID_TOPIC)) {
		$topiclink['delete'] = '<a href="index.php?option=site&bolum=forum&task=deletetopic&id='.$topic_info->ID_TOPIC.'">Başlığı Sil</a>';
	} else {
		$topiclink['delete'] = '';
	}
	
	ForumHTML::TopicSeen($context, $pageNav, $topic_info, $board_info, $topiclink, $topiclimit, $topicstart);
	
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
