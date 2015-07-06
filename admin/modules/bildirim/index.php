<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = getParam($_REQUEST, 'id');
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
$limit = intval(getParam($_REQUEST, 'limit', 20));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	getBildirim();
	break;
	
	case 'show':
	showBildirim($id);
	break;
}

function showBildirim($id) {
	global $dbase;
	
	$msg = new Mesajlar($dbase);
	
	$dbase->setQuery("SELECT m.*, u.name as gonderen FROM #__mesajlar AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.gid"
	. "\n WHERE m.id=".$dbase->Quote($id));
	$dbase->loadObject($row);
	$row->baslik = $msg->cryptionText($row->baslik, 'decode');
	$row->text = $msg->cryptionText($row->text, 'decode');
	$row->text = nl2br($row->text);
	
	Bildirim::showBildirim($row);
}

function getBildirim() {
	global $dbase, $limitstart, $limit;
	
	$crypt = new Mesajlar($dbase);
	
	$query = "SELECT COUNT(m.id) FROM #__mesajlar AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.gid"
	. "\n WHERE m.aid='-1'";
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new pageNav($total, $limitstart, $limit);
		
	$query = "SELECT m.*, u.name AS gonderen FROM #__mesajlar AS m"
	. "\n LEFT JOIN #__users AS u ON u.id=m.gid"
	. "\n WHERE m.aid='-1'";
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	Bildirim::getBildirim($rows, $pageNav, $crypt);
}
