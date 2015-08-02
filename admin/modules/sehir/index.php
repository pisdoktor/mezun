<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('tables.sehirler');

switch($task) {
	default:
	getSehirList();
	break;
	
	case 'new':
	editSehir(0);
	break;
	
	case 'edit':
	editSehir($id);
	break;
	
	case 'save':
	saveSehir();
	break;
	
	case 'cancel':
	cancelSehir();
	break;
	
	case 'delete':
	delSehir($id);
	break;
}

function delSehir($id) {
	global $dbase;

	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunSehirler($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__sehirler WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect( 'index.php?option=admin&bolum=sehir' );
}

function saveSehir() {
	 global $dbase;
	
	$row = new mezunSehirler( $dbase );
	
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
	
	Redirect('index.php?option=admin&bolum=sehir', 'Şehir kaydedildi');
	
}

function cancelSehir() {
	global $dbase;
	
	$row = new mezunSehirler( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=sehir');
}

function getSehirList() {
	 global $dbase, $limit, $limitstart;
	 
	 $dbase->setQuery("SELECT * FROM #__sehirler");
	 $rows = $dbase->loadObjectList();
	 
	 $total = count($rows);
	 
	 $pageNav = new mezunPagenation( $total, $limitstart, $limit);
	 
	 $list = array_slice($rows, $limitstart, $limit);
	 
	IlHTML::getIlList($list, $pageNav);
}

function editSehir($id) {
	global $dbase;
	
	$row = new mezunSehirler($dbase);
	$row->load($id);
	
	IlHTML::editIl($row);
}