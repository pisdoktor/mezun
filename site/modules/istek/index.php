<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));
$type = intval(getParam($_REQUEST, 'type'));

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.istek.helper');

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
	
	case 'send':
	sendIstek($id);
	break;
}
/**
* Gelen istekleri değerlendirme fonksiyon
* @param mixed $cid gelen isteklerin id değeri
* @param mixed $status gelen isteğe ne yapılacağı 1: onayla -1: reddet
*/
function changeDurum($cid, $status) {
	global $dbase, $my, $type;
	
	if ($type) {
		return false;
	}
	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Bu işlem için listeden bir seçim yapın'); window.history.go(-1);</script>\n";
		exit;
	}

	ArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "UPDATE #__istekler SET durum=".$dbase->Quote($status)
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	Redirect('index.php?option=site&bolum=istek&task=inbox');
	
}

/**
* Gönderilmiş bir isteği silme fonksiyonu
* 
* @param mixed $cid gönderilmiş isteklerin id değeri
*/
function deleteDurum($cid) {
	global $dbase, $my;
	
	$type = getParam($_REQUEST, 'type');
	
		$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir istek seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	ArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "DELETE FROM #__istekler"
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if ($type) {
	Redirect('index.php?option=site&bolum=istek&task=outbox');    
	} else {
	Redirect('index.php?option=site&bolum=istek&task=inbox');
	}
}

/**
* İstek gönderim fonksiyonu
* 
* @param mixed $id istek gönderilecek kullanıcının id si
*/
function sendIstek($id) {
	global $dbase, $my;
	
	$istek = new Istekler($dbase);
	
	if ($my->id == $id) {
		NotAuth();
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
	
	Redirect('index.php?option=site&bolum=istek&task=outbox', 'Arkadaşlık isteği gönderildi');
	} else {
		if ($onceki) {
			$msg = 'Daha önceden istek gönderilmiş';
		}
		if ($varolan) {
			$msg = 'Bu kişiyle zaten bir arkadaşlığınız var';
		}
	Redirect('index.php?option=site&bolum=istek&task=inbox', $msg);    
	}
}

/**
* İstekler Kutusu
* @param mixed $type 1: giden istekler kutusu 0: gelen istekler kutusu
*/
function inBox($type) {
	global $dbase, $my, $limit, $limitstart;
	
	mimport('helpers.modules.forum.helper');
	
	$where = $type ? "WHERE i.gid=".$my->id." AND i.durum=0" : "WHERE i.aid=".$my->id." AND i.durum=0";
	
	$query = "SELECT COUNT(*) FROM #__istekler AS i "
	.$where;
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$query = "SELECT i.*, u.name AS gonderen, uu.name as giden FROM #__istekler AS i"
	. "\n LEFT JOIN #__users AS u ON u.id=i.gid"
	. "\n LEFT JOIN #__users AS uu ON uu.id=i.aid "
	.$where
	;
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	Istek::inBox($rows, $pageNav, $type);
}
