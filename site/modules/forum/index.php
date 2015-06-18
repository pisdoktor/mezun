<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(mosGetParam($_REQUEST, 'id'));
$msgid = intval(mosGetParam($_REQUEST, 'msgid'));
$limit = intval(mosGetParam($_REQUEST, 'limit', 10));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	BoardIndex();
	break;
	
	case 'board':
	Board($id);
	break;
	
	case 'topic':
	Topic($id, $msgid);
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

function createNewTopic() {
	global $dbase, $my;
}

function BoardIndex() {
	global $dbase;
	
	$categories = new BoardCategories($dbase);
	
	$context['categories'] = $categories->BoardIndex();
	
	ForumHTML::BoardIndex($context);
}

function Board($id) {
	global $dbase, $limit, $limitstart;
	
	$boards = new BoardCategories($dbase);
	$topics = new Boards($dbase);
	
	$context['boards'] = $boards->Board($id);
	$context['topics'] = $topics->BoardTopics($id, $limitstart, $limit);
	
	$total = count($context['topics']);
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	ForumHTML::BoardSeen($context, $pageNav, $id);
}
