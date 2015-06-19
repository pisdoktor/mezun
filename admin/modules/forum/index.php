<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$cid = mosGetParam($_REQUEST, 'cid');
$id = intval(mosGetParam($_REQUEST, 'id'));  
$limit = intval(mosGetParam($_REQUEST, 'limit', 30));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'categories':
	Categories();
	break;
	
	case 'editcat':
	editCategory($id);
	break;
	
	case 'addcat':
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
	
	case 'addboard':
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
}
function saveBoard() {
	global $dbase;
	
	$row = new Boards($dbase);
	$row->bind($_POST);
	$row->store();
	
	mosRedirect('index.php?option=admin&bolum=forum&task=boards');
}
/**
* Kategori seçimine göre board kısıtlaması yapılacak
* 
* @param mixed $id
*/
function editBoard($id) {
	global $dbase;
	
	$row = new Boards($dbase);
	$row->load($id);
	
	//cat
	$dbase->setQuery("SELECT * FROM #__forum_categories");
	$cats = $dbase->loadObjectList();
	
	$cat[] = mosHTML::makeOption('', 'Kategori Seçin');
	foreach ($cats as $cats) {
		$cat[] = mosHTML::makeOption($cats->ID_CAT, $cats->name);
	}
	
	$lists['cat'] = mosHTML::selectList($cat, 'ID_CAT', 'class="inputbox" size="1"', 'value', 'text', $row->ID_CAT);
	
	//board
	$dbase->setQuery("SELECT * FROM #__forum_boards");
	$boards = $dbase->loadObjectList();
	
	$b[] = mosHTML::makeOption('0', 'ANA');
	foreach($boards as $board) {
		$b[] = mosHTML::makeOption($board->ID_BOARD, $board->name);
	}
	
	$lists['parent'] = mosHTML::selectList($b, 'ID_PARENT', 'class="inputbox" size="1"', 'value', 'text', $row->ID_PARENT);
	
	ForumHTML::editBoard($row, $lists);
}

function saveCategory() {
	global $dbase;
	
	$row = new BoardCategories($dbase);
	$row->bind($_POST);
	$row->store();
	
	mosRedirect('index.php?option=admin&bolum=forum&task=categories');
}

function editCategory($id) {
	global $dbase;
	
	$row = new BoardCategories($dbase);
	$row->load($id);
	
	ForumHTML::editCategory($row);
}

function Categories() {
	global $dbase, $limit, $limitstart;
	
	$dbase->setQuery("SELECT COUNT(*) FROM #__forum_categories");
	$total = $dbase->loadResult();
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
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
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	$query = "SELECT b.ID_PARENT as parent, ID_BOARD AS id, b.*, c.name as catname FROM #__forum_boards AS b"
	. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT=b.ID_CAT"
	. "\n ORDER BY b.boardOrder DESC";
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
