<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 25));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('tables.akis');

switch($task) {
	default:
	getAkisList();
	break;
	
	case 'edit':
	editAkis($id);
	break;
	
	case 'save':
	saveAkis();
	break;
	
	case 'cancel':
	cancelAkis();
	break;
	
	case 'delete':
	delAkis($id);
	break;
}

function delAkis($id) {
	global $dbase;

	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunAkis($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__akis WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect( 'index.php?option=admin&bolum=akis' );
}

function saveAkis() {
	 global $dbase;
	
	$row = new mezunAkis( $dbase );
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	Redirect('index.php?option=admin&bolum=akis', 'Akış kaydedildi');
	
}

function cancelAkis() {
	global $dbase;
	
	$row = new mezunAkis( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=akis');
}

function getAkisList() {
	 global $dbase, $limit, $limitstart;
	 
	 $dbase->setQuery("SELECT a.*, u.name FROM #__akis AS a LEFT JOIN #__users AS u ON u.id=a.userid ORDER BY a.tarih DESC");
	 $rows = $dbase->loadObjectList();
	 
	 $total = count($rows);
	 
	 $pageNav = new mezunPagenation( $total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	AkisHTML::getAkisList($list, $pageNav);
}

function editAkis($id) {
	global $dbase;
	
	$row = new mezunAkis($dbase);
	$row->load($id);
	
	AkisHTML::editAkis($row);
}