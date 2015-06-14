<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = mosGetParam($_REQUEST, 'id');
$cid = mosGetParam($_REQUEST, 'id'); 
$limit = intval(mosGetParam($_REQUEST, 'limit', 10));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'inbox':
	inBox(0);
	break;
	
	case 'outbox':
	inBox(1);
	break;
	
	case 'show':
	showMessage($id);
	break;
	
	case 'new':
	createMessage();
	break;
	
	case 'send':
	sendMessage();
	break;
	
	case 'cancel':
	cancelMessage();
	break;
	
	case 'delete':
	deleteMessage($cid);
	break;
	
	case 'unread':
	changeMessage($cid, 0);
	break;
	
	case 'read':
	changeMessage($cid, 1);
	break;
}

function showMessage($id) {
	global $dbase, $my;
	
	$row = new Mesajlar($dbase);
	$row->load($id);
	
	Message::showMsg($row, $my);
}

function sendMessage() {
	global $dbase, $my;
	
	$row = new Mesajlar( $dbase );
	$istek = new Istekler($dbase);
	
	$row->id = $row->createID();
	
	$row->gid = $my->id;
	$row->aid = intval(mosGetParam($_REQUEST, 'aid'));
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if ($row->aid == $my->id) {
		mosRedirect('index.php?option=site&bolum=mesaj', 'Kendinize mesaj gönderemezsiniz');
	}
	
	if (!$istek->checkDurum($my->id, $row->aid, 1)) {
		mosRedirect('index.php?option=site&bolum=mesaj', 'Arkadaşlığınız olmayan birisine mesaj gönderemezsiniz');
	}
	
	$row->baslik = base64_encode($row->baslik);
	$row->text = base64_encode($row->text);
	$row->tarih = date('Y-m-d H:i:s');
	$row->okunma = 0;
	$row->gsilinme = 0;
	$row->asilinme = 0;
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	mosRedirect('index.php?option=site&bolum=mesaj', 'Mesajınız başarıyla gönderildi');
}

function createMessage() {
	global $dbase, $my;
	
	Message::createMsg($my);
}

function inBox($type) {
	global $dbase, $my, $limit, $limitstart;
	
	$where = $type ? ' WHERE m.gid='.$dbase->Quote($my->id) : ' WHERE m.aid='.$dbase->Quote($my->id);
	$where2 = $type ? 'AND m.gsilinme=0' : 'AND m.asilinme=0';
	
	$query = "SELECT COUNT(*) FROM #__mesajlar AS m"
	.$where
	.$where2
	. "\n ORDER BY m.okunma ASC, m.tarih DESC";
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new pageNav( $total, $limitstart, $limit);
	
	$query = "SELECT m.*, u.name as gonderen FROM #__mesajlar AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.gid"
	.$where
	.$where2
	. "\n ORDER BY m.okunma ASC, m.tarih DESC";
	$dbase->setQuery($query, $limitstart, $limit);
	
	$rows = $dbase->loadObjectList();
	
	Message::inBox($rows, $pageNav);
}