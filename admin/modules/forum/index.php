<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));  
$catid = intval(getParam($_REQUEST, 'catid'));  
$limit = intval(getParam($_REQUEST, 'limit', 30));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

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
	reCountBoards();
	break;
	
	case 'getboards':
	getBoards($catid, $id);
	break;
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
	
	echo mezunHTML::selectList($board, 'ID_PARENT', '', 'value', 'text');	
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
	
	Redirect('index.php?option=admin&bolum=forum&task=boards');
}
/**
* Kategori seçimine göre board kısıtlaması yapılacak
* 
* @param mixed $id
*/
function editBoard($id) {
	global $dbase;
	
	mimport('tables.forumboard');
	$row = new mezunForumboard($dbase);
	$row->load($id);
	
	//cat
	$dbase->setQuery("SELECT * FROM #__forum_categories");
	$cats = $dbase->loadObjectList();
	
	$cat[] = mezunHTML::makeOption('', 'Kategori Seçin');
	foreach ($cats as $cats) {
		$cat[] = mezunHTML::makeOption($cats->ID_CAT, $cats->name);
	}
	
	$lists['cat'] = mezunHTML::selectList($cat, 'ID_CAT', 'id="ID_CAT" onchange="getBoards();"', 'value', 'text', $row->ID_CAT);
	
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
	
	$lists['parent'] = mezunHTML::selectList($b, 'ID_PARENT', 'id="ID_PARENT"', 'value', 'text', $row->ID_PARENT);
	
	ForumHTML::editBoard($row, $lists, $nodes);
}

function saveCategory() {
	global $dbase;
	
	mimport('tables.forumcategory');
	$row = new mezunForumcategory($dbase);
	$row->bind($_POST);
	$row->store();
	
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
