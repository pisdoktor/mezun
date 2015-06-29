<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = getParam($_REQUEST, 'id');
$cid = getParam($_REQUEST, 'cid'); 
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
$type = intval(getParam($_REQUEST, 'type'));

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
	deleteMessage($cid, $type);
	break;
	
	case 'unread':
	changeMessage($cid, 0);
	break;
	
	case 'read':
	changeMessage($cid, 1);
	break;
}
/**
* Mesaj silme
*/
function deleteMessage($cid, $type) {
	
}
/**
* Mesaj durumu değiştirme
*/
function changeMessage($cid, $status) {
	global $dbase, $my;
	
	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Listeden bir seçim yapın'); window.history.go(-1);</script>\n";
		exit;
	}

	ArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "UPDATE #__mesajlar SET okunma=".$dbase->Quote($status)
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if ($status) {
		$msg = 'Seçili mesajlar okundu olarak işaretlendi!';
	} else {
		$msg = 'Seçili mesajlar okunmadı olarak işaretlendi!';
	}
	Redirect( 'index.php?option=site&bolum=mesaj', $msg);
}
/**
* Mesaj gösterim fonksiyonu
* @param mixed $id gönderim yapılacak kullanıcının id değeri
*/
function showMessage($id) {
	global $dbase, $my;
	
	$row = new Mesajlar($dbase);
	$row->load($id);
	
	if ($row->aid == $my->id || $row->gid == $my->id) {
		
		$dbase->setQuery("SELECT m.*, u.name as gonderen FROM #__mesajlar AS m LEFT JOIN #__users AS u ON u.id=m.gid WHERE m.id=".$dbase->Quote($row->id));
		
		$dbase->loadObject($msg);
	
	$msg->baslik = base64_decode($row->baslik);
	$msg->text = base64_decode($row->text);
	
	if ($row->aid == $my->id) {
	$dbase->setQuery("UPDATE #__mesajlar SET okunma=1 WHERE id=".$dbase->Quote($msg->id));
	$dbase->query();
	}
	} else {
		NotAuth();
		exit;
	}
	
	if ($row->aid == $my->id) {
		$type = 0;
	} else {
		$type = 1;
	}
	
	Message::showMsg($msg, $type);
}
/**
* Mesaj gönderim fonksiyonu
* Arkadaşlık durumuna göre mesaj gönderiyor
*/
function sendMessage() {
	global $dbase, $my;
	
	$row = new Mesajlar( $dbase );
	$istek = new Istekler($dbase);
	
	$row->id = $row->createID();
	
	$row->gid = $my->id;
	$row->aid = intval(getParam($_REQUEST, 'aid'));
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if ($row->aid == $my->id) {
		Redirect('index.php?option=site&bolum=mesaj', 'Kendinize mesaj gönderemezsiniz');
	}
	
	if (!$istek->checkDurum($my->id, $row->aid, 1)) {
		Redirect('index.php?option=site&bolum=mesaj', 'Arkadaşlığınız olmayan birisine mesaj gönderemezsiniz');
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
	
	Redirect('index.php?option=site&bolum=mesaj', 'Mesajınız başarıyla gönderildi');
}
/**
* Mesaj oluşturma
* Sadece arkadaş listesindeki üyelere
*/
function createMessage() {
	global $dbase, $my;
	
	$query = "SELECT u.id, u.name, u.username FROM #__istekler AS i"
	. "\n LEFT JOIN #__users AS u ON u.id=i.aid"
	. "\n WHERE i.gid=".$dbase->Quote($my->id)." AND durum=1"
	;
	$dbase->setQuery($query);
	$rows1 = $dbase->loadObjectList();
	
	$query = "SELECT u.id, u.name, u.username FROM #__istekler AS i"
	. "\n LEFT JOIN #__users AS u ON u.id=i.gid"
	. "\n WHERE i.aid=".$dbase->Quote($my->id)." AND durum=1"
	;
	$dbase->setQuery($query);
	$rows2 = $dbase->loadObjectList();
	
	$rows = array_merge($rows1, $rows2);
	
	foreach ($rows as $row) {
		$user[] = mosHTML::makeOption($row->id, $row->name.' ('.$row->username.')');
	}
	
	$userlist = mosHTML::selectList($user, 'aid', 'id="aid" required size="10"', 'value', 'text');
	
	Message::createMsg($my, $userlist);
}
/**
* Mesaj kutusu gösterimi
* @param mixed $type 0 ise gelen 1 ise giden kutusu
*/
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
	
	$query = "SELECT m.*, u.name as gonderen, uu.name as giden FROM #__mesajlar AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.gid"
	. "\n LEFT JOIN #__users AS uu ON uu.id=m.aid"
	.$where
	.$where2
	. "\n ORDER BY m.okunma ASC, m.tarih DESC";
	$dbase->setQuery($query, $limitstart, $limit);
	
	$rows = $dbase->loadObjectList();
	
	Message::inBox($rows, $pageNav, $type);
}