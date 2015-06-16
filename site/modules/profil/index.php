<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(mosGetParam($_REQUEST, 'id')); 


include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	case 'my':
	getProfile($my->id);
	break;
	
	case 'edit':
	editProfile();
	break;
	
	case 'show':
	getProfile($id);
	break;
	
	case 'save':
	saveProfile();
	break;
	
	case 'cancel':
	cancelProfile();
	break;
	
	case 'delete':
	delProfile();
	break;
	
	case 'changepass':
	changePass();
	break;
	
	case 'savepass':
	savePass();
	break;
	
	case 'saveimage':
	saveImage();
	break;
	
	case 'deleteimage':
	deleteImage();
	break;
}
/**
* Profil resmi silme fonksiyonu
*/
function deleteImage() {
	global $dbase, $my;
	
	$query = "SELECT image FROM #__users WHERE id=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	
	$image = $dbase->loadResult();
	
	if ($image && @unlink(ABSPATH.'/images/'.$image)) {
			$dbase->setQuery("UPDATE #__users SET image='' WHERE id=".$dbase->Quote($my->id));
			$dbase->query();
	} else {
		mosErrorAlert('Resim yok');
	}
	
	mosRedirect('index.php?option=site&bolum=profil&task=my');
}
/**
* Profil parolası değiştirme fonksiyonu
* Düzenleme yapılacak henüz işlevsel değil
*/
function changePass() {
	global $dbase, $my;
	
	$password = mosGetParam($_POST, 'password');
	$password2 = mosGetParam($_POST, 'password2');
}
/**
* Profil resmi ekleme/değiştirme fonksiyonu
*/
function saveImage() {
	global $dbase, $my;
	
	$image = mosGetParam($_FILES, 'image');
	
	if (!$image['name']) {
		mosRedirect('index.php?option=site&bolum=profil&task=my', 'Resim seçmemişsiniz');
	}
	
	$row = new Users($dbase);
	$row->load($my->id);
	
	//eğer varsa önce eski resmi silelim
	if ($row->image) {
		@unlink(ABSPATH.'/images/'.$row->image);
	}
	
	//şimdi yeni resmi yükleyelim
	$dest = ABSPATH.'/images/';
	$maxsize = '2048';
	$allow = array('png', 'gif', 'jpg', 'jpeg');
		
	$uzanti = pathinfo($image['name']);
	$uzanti = strtolower($uzanti["extension"]);
		
	if (!in_array($uzanti, $allow)) {
		$error = addslashes( $image['name'].' için dosya türü uygun değil');
	}
		
	if ($image['size'] > $maxsize*1024) {
		$error = addslashes($image['name'].' için dosya boyutu istenilenden büyük!');
	}
						
	$imagename = $row->id.$row->username.$row->createCode(6).'.'.$uzanti;
	$targetfile= $dest.$imagename;
		
	if (move_uploaded_file($image['tmp_name'], $targetfile)) {
		$error = 'Resminiz başarıyla yüklendi';
	} else {
		$error = addslashes( $image['name'].' yüklenemedi!');    
	}
	
	$query = "UPDATE #__users SET image=".$dbase->Quote($imagename)
	. "\n WHERE id=".$dbase->Quote($row->id);
	$dbase->setQuery($query);
	$dbase->query();
	
	mosRedirect('index.php?option=site&bolum=profil', $error);
}
/**
* Profil güncellemesi için açılacak sayfa
*/
function editProfile() {
	global $dbase, $my;
	
	$row = new Users($dbase);
	$row->load($my->id);
	
	Profile::editProfile($row);
	
}
/**
* Profil güncelleme fonksiyonu
*/
function saveProfile() {
	global $dbase, $my;
	
	$row = new Users( $dbase );
	
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$row->id = $my->id;
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	mosRedirect('index.php?option=site&bolum=profil&task=my', 'Değşiklikler başarıyla kaydedildi');
}
/**
* Profil gösterimi
* @param mixed $id gösterim yapılacak kullanıcının id si
* Mesaj ve arkadaşlık isteği gönderimi konusunda daha iyi bir kodlama yapılmalı!
*/
function getProfile($id) {
	global $dbase, $my;
	
	$edit = false;
	$user = new Users($dbase);
	if ($id) {
		
	if ($id == $my->id) {
		$edit = true;
		$user->load($my->id);
	} else {
		$user->load($id);
		
		if (!$user->activated) {
		mosNotAuth();
		exit();
	}
	}
	
	if (($my->id == $user->id)) {
		$msg = false;
		$istem = false;
	} else {
		
		$istek = new Istekler($dbase);
	if ($istek->checkDurum($my->id, $user->id, 1) == true) {
		$istem = false;
		$msg = true;
	} else if ($istek->checkDurum($my->id, $user->id, 0) == true) {
		$istem = false;
		$msg = false;
	} else if (($istek->checkDurum($my->id, $user->id, 1) == false) && ($istek->checkDurum($my->id, $user->id, 0) == false)) {
		$istem = true;
		$msg = false;
	}
	}
	$query = "SELECT u.*, s.name as sehiradi, ss.name as dogumyeri FROM #__users AS u"
	. "\n LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.dogumyeri"
	. "\n WHERE u.id=".$dbase->Quote($user->id);
	$dbase->setQuery($query);
	
	$dbase->loadObject($row);
	
	} else {
		mosNotAuth();
		exit();	
	}
	
	if (!$row) {
		mosNotAuth();
		exit();
	}
		
	Profile::getProfile($row, $edit, $msg, $istem);
}
