<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(mosGetParam($_REQUEST, 'id'));
$cid = mosGetParam($_REQUEST, 'id'); 

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'inbox':
	inBox(0);
	break;
	
	case 'outbox':
	inBox(1);
	break;
	
	case 'show':
	showMessage($id);
	break;
	
	case 'new':
	createMessage();
	break;
	
	case 'send':
	sendMessage();
	break;
	
	case 'cancel':
	cancelMessage();
	break;
	
	case 'delete':
	deleteMessage($cid);
	break;
	
	case 'unread':
	changeMessage($cid, 0);
	break;
	
	case 'read':
	changeMessage($cid, 1);
	break;
}

function createMessage() {
	global $dbase, $my;
	
	Message::createMsg();
}

function inBox($type) {
	global $dbase, $my;
	
	$where = $type ? ' WHERE gid='.$dbase->Quote($my->id) : ' WHERE aid='.$dbase->Quote($my->id);
	$query = "SELECT * FROM #__mesajlar"
	.$where
	. "\n ORDER BY okunma ASC, tarih DESC";
	$dbase->setQuery($query);
	
	$rows = $dbase->loadObjectList();
	
	Message::inBox($rows);
}