<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include_once(ABSPATH.'/admin/includes/admin.html.php');
include(dirname(__FILE__). '/html.php');

mimport('tables.menus');

switch($task) {
	default:
	getMenus();
	break;
	
	case 'edit':
	editMenu($cid);
	break;
	
	case 'editx':
	editMenu($id);
	break;
	
	case 'new':
	editMenu(0);
	break;
	
	case 'save':
	saveMenu();
	break;
	
	case 'cancel':
	cancelMenu();
	break;
	
	case 'delete':
	deleteMenu($cid);
	break;
}

function deleteMenu($cid) {
	global $dbase;
	
	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir menü seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	ArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "DELETE FROM #__menu"
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	$dbase->query();
	
	$childs = 'parent='. implode(' OR parent=', $cid);
	$query = "DELETE FROM #__menu"
	. "\n WHERE ( $childs )"
	;
	
	$dbase->setQuery( $query );
	
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	Redirect('index.php?option=admin&bolum=menu');
}

function saveMenu() {
	global $dbase;
	
	$row = new mezunMenus($dbase);
	$row->bind($_POST);
	$row->store();
	$row->updateOrder('parent='.$row->parent);
	
	Redirect('index.php?option=admin&bolum=menu');
	
}

function cancelMenu() {
	Return('index.php?option=admin&bolum=menu');
}

function editMenu($cid) {
	global $dbase;
	
	$row = new mezunMenus($dbase);
	$row->load($cid);
	
	menusHTML::editMenu($row);	
}

function getMenus() {
	global $dbase, $limit, $limitstart;
	
	$dbase->setQuery("SELECT * FROM #__menu ORDER BY parent, ordering");
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
	
	$list = treeRecurse( 0, '', array(), $children, 9 );
	
	$total = count( $list );
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice( $list, $pageNav->limitstart, $pageNav->limit );
	
	menusHTML::getMenus($list, $pageNav);
}
