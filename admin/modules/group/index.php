<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'group':
	getGroupList();
	break;
	
	case 'editgroup':
	editGroup($id);
	break;
	
	case 'savegroup':
	saveGroup();
	break;
	
	case 'deletegroup':
	deleteGroup($id);
	break;
	
	case 'messages':
	getMessagesList();
	break;
	
	case 'editmessage':
	editMessage($id);
	break;
	
	case 'savemessage':
	saveMessage();
	break;
	
	case 'deletemessage':
	deleteMessage($id);
	break;
	
	case 'recount':
	reCountMessages();
	break;
}

function reCountMessages() {
	global $dbase;
}

function getGroupList() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT g.*, u.name AS olusturan, COUNT(m.id) AS totalmessage FROM #__groups AS g"
	. "\n LEFT JOIN #__users AS u ON u.id=g.creator"
	. "\n LEFT JOIN #__groups_messages AS m ON m.groupid=g.id"
	. "\n GROUP BY g.id ORDER BY g.creationdate DESC");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	
	adminGroupHTML::getGroupList($list, $pageNav);
}

function getMessagesList() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT m.*, u.name as gonderen, g.name as groupname FROM #__groups_messages AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.userid"
	. "\n LEFT JOIN #__groups AS g ON g.id=m.groupid");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	
	adminGroupHTML::getMessagesList($list, $pageNav);
}
