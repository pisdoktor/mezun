<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$limit = intval(mosGetParam($_REQUEST, 'limit', 10));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));
$cid = mosGetParam($_REQUEST, 'cid');
$id = intval(mosGetParam($_REQUEST, 'id'));
$type = intval(mosGetParam($_REQUEST, 'type'));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'inbox':
	inBox(0);
	break;
	
	case 'outbox':
	inBox(1);
	break;
	
	case 'delete':
	deleteDurum($cid);
	break;
	
	case 'onayla':
	changeDurum($cid, 1);
	break;
	
	case  'red':
	changeDurum($cid, -1);
	break;
	
	case 'send':
	sendIstek($id);
	break;
}

function changeDurum($cid, $status) {
	global $dbase, $my;
	
	if ($type) {
		return false;
	}
	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir istek seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "UPDATE #__istek SET durum=".$dbase->Quote($status)
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	mosRedirect('index.php?option=site&bolum=istek&task=inbox');
	
}

function deleteDurum($cid) {
	global $dbase, $my;
	
	//giden istek silme işlemi 
	if ($type) {
		$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir istek seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "DELETE FROM #__istek"
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	//gelen istek silme işlemi varsa reddet 
	} else {
		return false;
	}
	
	mosRedirect('index.php?option=site&bolum=istek&task=outbox');
}

function sendIstek($id) {
	global $dbase, $my;
	
	$istek = new Istekler($dbase);
	
	if ($my->id == $id) {
		mosNotAuth();
		exit();
	}
	
	$onceki = $istek->checkDurum($my->id, $id, 0);
	$varolan = $istek->checkDurum($my->id, $id, 1);
	
	if ($onceki == false && $varolan == false) {
		
	$istek->gid = $my->id;
	$istek->aid = $id;
	$istek->tarih = date('Y-m-d H:i:s');
	$istek->durum = 0;

	$istek->store();
	
	mosRedirect('index.php?option=site&bolum=istek&task=outbox', 'Arkadaşlık isteği gönderildi');
	} else {
		if ($onceki) {
			$msg = 'Daha önceden istek gönderilmiş';
		}
		if ($varolan) {
			$msg = 'Bu kişiyle zaten bir arkadaşlığınız var';
		}
	mosRedirect('index.php?option=site&bolum=istek&task=inbox', $msg);    
	}
}

function inBox($type) {
	global $dbase, $my, $limit, $limitstart;
	
	$where = $type ? "WHERE i.gid=".$my->id." AND i.durum=0" : "WHERE i.aid=".$my->id." AND i.durum=0";
	
	$query = "SELECT COUNT(*) FROM #__istekler AS i "
	.$where;
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	$query = "SELECT i.*, u.name AS gonderen, uu.name as giden FROM #__istekler AS i"
	. "\n LEFT JOIN #__users AS u ON u.id=i.gid"
	. "\n LEFT JOIN #__users AS uu ON uu.id=i.aid "
	.$where
	;
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	Istek::inBox($rows, $pageNav, $type);
}
