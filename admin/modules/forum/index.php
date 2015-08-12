<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));  
$catid = intval(getParam($_REQUEST, 'catid'));  
$limit = intval(getParam($_REQUEST, 'limit', 30));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

$step = intval(getParam($_REQUEST, 'step', 0));

include_once(ABSPATH.'/admin/includes/admin.html.php');
include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'categories':
	Categories();
	break;
	
	case 'editcat':
	editCategory($id);
	break;
	
	case 'deletecat':
	deleteCategory($id);
	break;
	
	case 'newcat':
	editCategory(0);
	break;
	
	case 'savecat':
	saveCategory();
	break;
	
	case 'cancelcat':
	cancelCategory();
	break;
	
	case 'boards':
	Boards();
	break;
	
	case 'editboard':
	editBoard($id);
	break;
	
	case 'deleteboard':
	deleteBoard($id);
	break;
	
	case 'newboard':
	editBoard(0);
	break;
	
	case 'saveboard':
	saveBoard();
	break;
	
	case 'cancelboard':
	cancelBoard();
	break;
	
	case 'topics':
	Topics();
	break;
	
	case 'messages':
	Messages();
	break;
	
	case 'recount':
	reCountBoards($step);
	break;
	
	case 'getboards':
	getBoards($catid, $id);
	break;
}

function reCountBoards($step) {
	global $dbase;
	
	$msg = '';
	
	if ($step == 0) {
	//önce hatalı numReplies olan tüm topicleri düzeltelim
	$query = "SELECT /*!40001 SQL_NO_CACHE */ t.ID_TOPIC, t.numReplies, COUNT(*) - 1 AS realNumReplies
		FROM (#__forum_topics AS t, #__forum_messages AS m)
		WHERE m.ID_TOPIC = t.ID_TOPIC
		GROUP BY t.ID_TOPIC
		HAVING realNumReplies != numReplies";
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
		foreach ($rows as $row) {
			$dbase->setQuery("UPDATE #__forum__topics
					SET numReplies = ".$row['realNumReplies']."
					WHERE ID_TOPIC = ".$row['ID_TOPIC']."
					LIMIT 1");
			$dbase->query();
		}
		
		$msg .= 'Hatalı numReplies olan tüm başlıklar düzeltiliyor...';
		 
		Redirect('index.php?option=admin&bolum=forum&task=recount&step=1');
		
	}
	
	if ($step == 1) {
	// Her board için post ve topic sayılarını güncelleyelim
	//sıfırla
	$dbase->setQuery("UPDATE #__forum_boards SET numPosts = 0, numTopics = 0");
	$dbase->query();
	
	$query = "SELECT /*!40001 SQL_NO_CACHE */ t.ID_BOARD, COUNT(*) AS realNumPosts, COUNT(DISTINCT t.ID_TOPIC) AS realNumTopics
		FROM (#__forum_topics AS t, #__forum_messages AS m)
		WHERE m.ID_TOPIC = t.ID_TOPIC
		GROUP BY t.ID_BOARD";
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
		foreach ($rows as $row) {
			$dbase->setQuery("UPDATE #__forum_boards
					SET numPosts = numPosts + ".$row['realNumPosts'].",
						numTopics = numTopics + ".$row['realNumTopics']."
					WHERE ID_BOARD = ".$row['ID_BOARD']."
					LIMIT 1");
			$dbase->query();
		}
		$msg .= 'Tüm boardlardaki hatalı numPost ve numTopic değerleri düzeltiliyor...';
		
	Redirect('index.php?option=admin&bolum=forum&task=recount&step=2');	
	}
	
	if ($step == 2) {
	//yanlış board içerisinde olan herhangi bir mesaj varsa onu düzeltelim
	$query = "SELECT /*!40001 SQL_NO_CACHE */ t.ID_BOARD, m.ID_MSG
		FROM (#__forum_messages AS m, #__forum_topics AS t)
		WHERE t.ID_TOPIC = m.ID_TOPIC
		AND m.ID_BOARD != t.ID_BOARD";
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
		$boards = array();
		
		foreach ($rows as $row) {
			$boards[$row['ID_BOARD']][] = $row['ID_MSG'];
		}
		
		foreach ($boards as $board_id => $messages) {
			$dbase->setQuery("UPDATE #__forum_messages
					SET ID_BOARD = ".$board_id."
					WHERE ID_MSG IN (" . implode(', ', $messages) . ")
					LIMIT " . count($messages));
			$dbase->query();
		}
		
		$msg .= 'Yanlış board içerisinde olan mesajlar düzeltiliyor...';
	
	Redirect('index.php?option=admin&bolum=forum&task=recount&step=3');	
	}
	
	if ($step == 3) {
		
		$dbase->setQuery("UPDATE #__forum_boards SET ID_LAST_MSG = 0, ID_MSG_UPDATED = 0");
		$dbase->query();
		
		$query = 'SELECT /*!40001 SQL_NO_CACHE */ b.ID_PARENT, t.ID_BOARD, MAX(ID_MSG) AS maxMsgID
			FROM (#__forum_topics AS t, #__forum_messages AS m, #__forum_boards AS b)
			WHERE m.ID_TOPIC = t.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
			GROUP BY t.ID_BOARD';
			$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
		foreach ($rows as $row) {
			$dbase->setQuery("UPDATE #__forum_boards
					SET ID_LAST_MSG = ID_LAST_MSG + ".$row['maxMsgID'].",
						ID_MSG_UPDATED = ID_MSG_UPDATED + ".$row['maxMsgID']."
					WHERE ID_BOARD = ".$row['ID_BOARD']."
					LIMIT 1");
			$dbase->query();
			
			//eğer boardun parent boardu varsa onu da değiştirelim
			if ($row['ID_PARENT']) {
			$dbase->setQuery("UPDATE #__forum_boards
					SET ID_LAST_MSG = ID_LAST_MSG + ".$row['maxMsgID'].",
						ID_MSG_UPDATED = ID_MSG_UPDATED + ".$row['maxMsgID']."
					WHERE ID_BOARD = ".$row['ID_PARENT']."
					LIMIT 1");
			$dbase->query();
			}
		}
		
		$msg .= 'Boardların ID_LAST_MSG, ID_MSG_UPDATED değerleri düzeltiliyor...';
		 
		Redirect('index.php?option=admin&bolum=forum&task=recount&step=4'); 
	}
	
	if ($step == 4) {
		$msg .= 'Tüm aşamalar tamamlandı...';
	}
	
	echo $msg;
				
}

function getBoards($catid, $id) {
	global $dbase;
	
	$dbase->setQuery("SELECT * FROM #__forum_boards WHERE ID_CAT=".$catid
	. ($id ? " AND ID_BOARD!=".$id : "")
	);
	$rows = $dbase->loadObjectList();
	
	$board[] = mezunHTML::makeOption('0', 'ANA');
	foreach ($rows as $row) {
		$board[] = mezunHTML::makeOption($row->ID_BOARD, $row->name);
	}
	
	echo mezunHTML::selectList($board, 'ID_PARENT', 'size="5"', 'value', 'text');	
}

function cancelBoard() {
	
	Redirect('index.php?option=admin&bolum=forum&task=boards');
}

function cancelCategory() {
	
	Redirect('index.php?option=admin&bolum=forum&task=categories');
}

function saveBoard() {
	global $dbase;
	
	mimport('tables.forumboard');
	$row = new mezunForumboard($dbase);
	$row->bind($_POST);
	$row->store();
	$row->updateOrder('ID_CAT='.$row->ID_CAT);
	
	Redirect('index.php?option=admin&bolum=forum&task=boards');
}

function editBoard($id) {
	global $dbase;
	
	mimport('tables.forumboard');
	$row = new mezunForumboard($dbase);
	$row->load($id);
	
	//cat
	$dbase->setQuery("SELECT * FROM #__forum_categories");
	$cats = $dbase->loadObjectList();
	
	foreach ($cats as $cats) {
		$cat[] = mezunHTML::makeOption($cats->ID_CAT, $cats->name);
	}
	
	$lists['cat'] = mezunHTML::selectList($cat, 'ID_CAT', 'id="ID_CAT" onchange="getBoards();" size="5" required', 'value', 'text', $row->ID_CAT);
	
	//board
	$dbase->setQuery("SELECT * FROM #__forum_boards"
	. ($row->ID_BOARD ? " WHERE ID_BOARD NOT IN (".$row->ID_BOARD.") AND ID_CAT=".$row->ID_CAT : "")
	);
	$boards = $dbase->loadObjectList();
	
	$b[] = mezunHTML::makeOption('0', 'ANA');
	foreach($boards as $board) {
		$b[] = mezunHTML::makeOption($board->ID_BOARD, $board->name);
	}
	
	//jquery ile board cat eşleştirmesi yapalım
	$nodes = array();
	foreach ($boards as $bo){
		if (!isset($nodes[$bo->ID_CAT])) {
		$nodes[$bo->ID_CAT]['id'] = $bo->ID_CAT;
		$nodes[$bo->ID_CAT]['boards'] = array();
		}
		
		if (!isset($nodes[$bo->ID_CAT]['boards'][$bo->ID_BOARD])) {
			$nodes[$bo->ID_CAT]['boards'][$bo->ID_BOARD] = $bo->ID_BOARD;
		}
	}
	
	$lists['parent'] = mezunHTML::selectList($b, 'ID_PARENT', 'id="ID_PARENT" size="5"', 'value', 'text', $row->ID_PARENT);
	
	ForumHTML::editBoard($row, $lists, $nodes);
}

function saveCategory() {
	global $dbase;
	
	mimport('tables.forumcategory');
	$row = new mezunForumcategory($dbase);
	$row->bind($_POST);
	$row->store();
	$row->updateOrder();
	
	Redirect('index.php?option=admin&bolum=forum&task=categories');
}

function editCategory($id) {
	global $dbase;
	
	mimport('tables.forumcategory');
	$row = new mezunForumcategory($dbase);
	$row->load($id);
	
	ForumHTML::editCategory($row);
}

function Categories() {
	global $dbase, $limit, $limitstart;
	
	$dbase->setQuery("SELECT COUNT(*) FROM #__forum_categories");
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$dbase->setQuery("SELECT * FROM #__forum_categories", $limitstart, $limit);
	$rows = $dbase->loadObjectList();

	ForumHTML::Categories($rows, $pageNav);
}

function Boards() {
	global $dbase, $limit, $limitstart;
	
	$query = "SELECT COUNT(b.ID_BOARD) FROM #__forum_boards AS b"
	. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT=b.ID_CAT"
	;
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$query = "SELECT b.ID_PARENT as parent, ID_BOARD AS id, b.*, c.name as catname FROM #__forum_boards AS b"
	. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT=b.ID_CAT"
	. "\n ORDER BY c.catOrder ASC, b.boardOrder ASC";
	$dbase->setQuery($query, $limitstart, $limit);
	
	$rows = $dbase->loadObjectList();
	
	// establish the hierarchy of the menu
	$children = array();
	// first pass - collect children
	foreach ($rows as $v ) {
		$pt = $v->parent;
		$list = @$children[$pt] ? $children[$pt] : array();
		array_push( $list, $v );
		$children[$pt] = $list;
	}
	// second pass - get an indent list of the items
	$list = treeRecurse( 0, '', array(), $children);
	
	ForumHTML::Boards($list, $pageNav);
}
