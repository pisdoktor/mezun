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
	
	case 'view':
	viewUserFriends($id);
	break;
	
	case 'delete':
	deleteArkadaslik($id);
	break;
}

/**
* İstenilen id değerindeki kullanıcının arkadaşlarını gösterir
* 
* @param mixed $id
*/
function viewUserFriends($id) {
	global $dbase, $limitstart, $limit;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$user = new mezunUsers($dbase);
	$user->load($id);
	
	if (!$user->id) {
		NotAuth();
		return;
	}
	
	$friends = mezunArkadasHelper::getUserFriends($user->id);
	$friends = implode(',', $friends);
	
	$query = "SELECT id, name, image, cinsiyet, unvan FROM #__users WHERE id IN (".$friends.")";
	$dbase->setQuery($query);
	
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	ArkadasHTML::viewUserFriends($user, $list, $pageNav);
	
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
	
	ArkadasHTML::getList($list, $pageNav);	
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