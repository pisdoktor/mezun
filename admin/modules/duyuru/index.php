<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 5));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('tables.duyurular');

switch($task) {
	default:
	getDuyuruList();
	break;
	
	case 'new':
	editDuyuru(0);
	break;
	
	case 'edit':
	editDuyuru($id);
	break;
	
	case 'save':
	saveDuyuru();
	break;
	
	case 'cancel':
	cancelDuyuru();
	break;
	
	case 'delete':
	delDuyuru($id);
	break;
}

function delDuyuru($id) {
	global $dbase;

	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunDuyurular($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__duyurular WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect( 'index.php?option=admin&bolum=duyuru' );
}

function saveDuyuru() {
	 global $dbase;
	
	$row = new mezunDuyurular( $dbase );
	
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
	
	Redirect('index.php?option=admin&bolum=duyuru', 'Duyuru kaydedildi');
	
}

function cancelDuyuru() {
	global $dbase;
	
	$row = new mezunDuyurular( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=duyuru');
}

function getDuyuruList() {
	 global $dbase, $limit, $limitstart;
	 
	 $dbase->setQuery("SELECT * FROM #__duyurular");
	 $rows = $dbase->loadObjectList();
	 
	 $total = count($rows);
	 
	 $pageNav = new mezunPagenation( $total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	DuyuruHTML::getDuyuruList($list, $pageNav);
}

function editDuyuru($id) {
	global $dbase;
	
	$row = new mezunDuyurular($dbase);
	$row->load($id);
	
	DuyuruHTML::editDuyuru($row);
}