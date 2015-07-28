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
mimport('tables.istekler');
mimport('helpers.modules.arkadas.helper');
mimport('helpers.modules.online.helper');

switch($task) {
	default:
	case 'inbox':
	inBox(0);
	break;
	
	case 'outbox':
	inBox(1);
	break;
	
	case 'delete':
	changeDurum($id, 0);
	break;
	
	case 'onayla':
	changeDurum($id, 1);
	break;
	
	case 'send':
	sendIstek($id);
	break;
	
	case 'sendx':
	sendIstekX($id);
	break;
	
	case 'checkistek':
	checkIstek();
	break;
}

function checkIstek() {
	
	$total = mezunIstekHelper::totalWaiting();
	
	if ($total) {
		echo '<a href="index.php?option=site&bolum=istek&task=inbox">'.$total.'</a>';
	} else {
		echo 0;
	}
}
/**
* AJAX ile blok üzerinden istek gönderimi fonksiyon
* 
* @param mixed $id : istek gönderilen kullanıcı id
*/
function sendIstekX($id) {
	global $dbase, $my;
	
	$errors         = '';      // array to hold validation errors
	$data           = array();      // array to pass back data
	
	if (!$id) {
		$errors = 'ID değeri yok';
	}
	
	mimport('helpers.modules.arkadas.helper');
	
	$arkadasmisin = mezunArkadasHelper::checkArkadaslik($id);
	$istekvarmi = mezunIstekHelper::checkIstek($id);
	
	if ($istekvarmi) {
		$errors = 'Daha önceden istek gönderilmiş';
	}
		
	if ($arkadasmisin) {
		$errors = 'Bu kişiyle zaten bir arkadaşlığınız var';
	}
	
	if ($my->id == $id) {
		$errors = 'Kendinize istek gönderemezsiniz';
	}
	
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['message']  = $errors;
	} else {
		
		$istek = new mezunIstekler($dbase);
		$istek->gid = $my->id;
		$istek->aid = $id;
		$istek->tarih = date('Y-m-d H:i:s');
		$istek->durum = 0;
		$istek->store();
		
		$data['success'] = true;
		$data['message'] = $id;
	}
	
		// return all our data to an AJAX call
	echo json_encode($data['message']);
	
}
/**
* Gelen veya giden istek silme fonksiyonu
* 
* @param mixed $id : isteğin db deki id değeri
* @param mixed $status : 1: onaylanacak, 0: silinecek
*/
function changeDurum($id, $status) {
	global $dbase, $my, $type;
	
	$row = new mezunIstekler($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	if ($status) {
		$row->set('durum', 1);
		$row->store();
	
	$user = new mezunUsers($dbase);
	if ($my->id == $row->gid) {
		$user->load($row->aid);
	}
	
	if ($my->id == $row->aid) {
		$user->load($row->gid);
	}
		
	$akistext = '<a href="index.php?option=site&bolum=profil&task=show&id='.$user->id.'">'.$user->name.'</a> ile arkadaş oldu';
	mezunGlobalHelper::AkisTracker($akistext);
		
	} else {
		$row->delete($row->id);
	}
	
	$task = $type ? 'outbox':'inbox';

	Redirect('index.php?option=site&bolum=istek&task='.$task);
	
}

/**
* İstek gönderim fonksiyonu
* 
* @param mixed $id istek gönderilecek kullanıcının id si
*/
function sendIstek($id) {
	global $dbase, $my;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$istek = new mezunIstekler($dbase);
	
	if ($my->id == $id) {
		NotAuth();
		return;
	}
	
	mimport('helpers.modules.arkadas.helper');
	
	$arkadasmisin = mezunArkadasHelper::checkArkadaslik($id);
	$istekvarmi = mezunIstekHelper::checkIstek($id);
	
	if ($arkadasmisin == false && $istekvarmi == false) {
		
	$istek->gid = $my->id;
	$istek->aid = $id;
	$istek->tarih = date('Y-m-d H:i:s');
	$istek->durum = 0;

	$istek->store();
	
	Redirect('index.php?option=site&bolum=istek&task=outbox', 'Arkadaşlık isteği gönderildi');
	} else {
		if ($istekvarmi) {
			$msg = 'Daha önceden istek gönderilmiş';
		}
		if ($arkadasmisin) {
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
	
	$where = $type ? "WHERE i.gid=".$my->id." AND i.durum=0" : "WHERE i.aid=".$my->id." AND i.durum=0";
	
	$query = "SELECT COUNT(*) FROM #__istekler AS i "
	.$where;
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$query = "SELECT i.*, u.name AS gonderen, uu.name as giden, u.image AS gonderenimage, uu.image AS gidenimage FROM #__istekler AS i"
	. "\n LEFT JOIN #__users AS u ON u.id=i.gid"
	. "\n LEFT JOIN #__users AS uu ON uu.id=i.aid "
	.$where
	. "\n ORDER BY i.tarih DESC"
	;
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	Istek::inBox($rows, $pageNav, $type);
}
