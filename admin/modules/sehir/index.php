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
	getIlList();
	break;
	
	case 'add':
	editIl(0);
	break;
	
	case 'edit':
	editIl($cid[0]);
	break;
	
	case 'editx':
	editIl($id);
	break;
	
	case 'save':
	saveIl();
	break;
	
	case 'cancel':
	cancelIl();
	break;
	
	case 'delete':
	delIl($cid);
	break;
}

function delIl(&$cid) {
	global $dbase;

	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir il seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	ArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "DELETE FROM #__sehirler"
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	Redirect( 'index.php?option=admin&bolum=sehir', 'Seçili şehir(ler) silindi' );
}

function saveIl() {
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

function cancelIl() {
	global $dbase;
	
	$row = new mezunSehirler( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=sehir');
}

function getIlList() {
	 global $dbase, $limit, $limitstart;
	 
	 $dbase->setQuery("SELECT COUNT(*) FROM #__sehirler");
	 $total = $dbase->loadResult();
	 
	 $pageNav = new mezunPagenation( $total, $limitstart, $limit);
	 $query = "SELECT * FROM #__sehirler";
	
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	IlHTML::getIlList($rows, $pageNav);
}

function editIl($cid) {
	global $dbase;
	
	$row = new mezunSehirler($dbase);
	$row->load($cid);
	
	IlHTML::editIl($row);
}