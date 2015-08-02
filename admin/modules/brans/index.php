<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('tables.branslar');

switch($task) {
	default:
	getBransList();
	break;
	
	case 'new':
	editBrans(0);
	break;
	
	case 'edit':
	editBrans($id);
	break;
	
	case 'save':
	saveBrans();
	break;
	
	case 'cancel':
	cancelBrans();
	break;
	
	case 'delete':
	delBrans($id);
	break;
}

function delBrans($id) {
	global $dbase;

	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunBranslar($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__branslar WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect( 'index.php?option=admin&bolum=brans' );
}

function saveBrans() {
	 global $dbase;
	
	$row = new mezunBranslar( $dbase );
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	Redirect('index.php?option=admin&bolum=brans', 'Branş kaydedildi');
	
}

function cancelBrans() {
	global $dbase;
	
	$row = new mezunBranslar( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=brans');
}

function getBransList() {
	 global $dbase, $limit, $limitstart;
	 
	 $dbase->setQuery("SELECT * FROM #__branslar");
	 $rows = $dbase->loadObjectList();
	 
	 $total = count($rows);
	 
	 $pageNav = new mezunPagenation( $total, $limitstart, $limit);
	 
	 $list = array_Slice($rows, $limitstart, $limit);
	 
	BransHTML::getBransList($list, $pageNav);
}

function editBrans($id) {
	global $dbase;
	
	$row = new mezunBranslar($dbase);
	$row->load($id);
	
	BransHTML::editBrans($row);
}