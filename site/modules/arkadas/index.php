<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(getParam($_REQUEST, 'id')); 
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.arkadas.helper');
mimport('helpers.modules.online.helper');

switch($task) {
	default:
	getArkadasList();
	break;
	
	case 'delete':
	deleteArkadaslik($id);
	break;
}

/**
* Arkadaş listesi gösterimi
*/
function getArkadasList() {
	global $dbase, $my, $limit, $limitstart;
	
	$rows = mezunArkadasHelper::getMyFriends();
	
	$total = count($rows);
	
	$query = "SELECT u.*, s.name as sehiradi, ss.name as dogumyeriadi, b.name as bransadi FROM #__users AS u"
	. "\n LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.dogumyeri"
	. "\n LEFT JOIN #__branslar AS b ON b.id=u.brans"
	. "\n WHERE u.id IN (".implode(', ', $rows).")";
	$dbase->setQuery($query, $limitstart, $limit);
	$list = $dbase->loadObjectList();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	Arkadas::getList($list, $pageNav);	
}

/**
* Arkadaşlıktan çıkarma fonksiyonu
* 
* @param mixed $id : Çıkarılacak kullanıcı adı
*/
function deleteArkadaslik($id) {
	global $dbase, $my;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__istekler WHERE (gid=".$dbase->Quote($id)." AND aid=".$dbase->Quote($my->id).") OR (gid=".$dbase->Quote($my->id)." AND aid=".$dbase->Quote($id).") AND durum=1");
	$dbase->query();
	
	Redirect('index.php?option=site&bolum=arkadas');
}