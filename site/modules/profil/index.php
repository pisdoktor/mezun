<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.profil.helper');

$id = intval(getParam($_REQUEST, 'id')); 

switch($task) {
	default:
	case 'my':
	getProfile($my->id);
	break;
	
	case 'edit':
	editProfile();
	break;
	
	case 'show':
	case 'view':
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
	
	case 'editimage':
	editImage();
	break;
	
	case 'cropsave':
	CropandSave();
	break;
}
/**
* Profil resmi kırpma fonksiyonu
*/
function CropandSave() {
	global $dbase, $my;
	
	mimport('helpers.image.helper');
	

	$query = "SELECT image FROM #__users WHERE id=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	$image = $dbase->loadResult();
	
	if (!$image) {
		return;
	}
	
	$src = ABSPATH.'/images/profil/'.$image;
	
	if (!mezunImageHelper::isImage($src)) {
		echo 'Resim yerinde yok';
		return;
	}
	
	//$src bilgileri
	$x = getParam($_POST, 'x');
	$y = getParam($_POST, 'y');
	$w = getParam($_POST, 'w');
	$h = getParam($_POST, 'h');
	
	//$dest bilgileri
	$minWidth = 200;
	$minHeight = 200;
	
	mezunImageHelper::crop($src, 0, 0, $x, $y, $minWidth, $minHeight, $w, $h);
	
	$akistext = 'Profil resmini düzenledi';
	mezunGlobalHelper::AkisTracker($akistext);
	
	Redirect('index.php?option=site&bolum=profil&task=my');
}

/**
* Profil resmi düzenleme
*/
function editImage() {
	global $dbase, $my;
	
	$query = "SELECT image FROM #__users WHERE id=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	$image = $dbase->loadResult();
	
	if (!$image) {
		return;
	} 
	
	$photo = array();
	$photo['name'] = $image;
	$photo['withpath'] = ABSPATH.'/images/profil/'.$image;
	$photo['withaddr'] = SITEURL.'/images/profil/'.$image;
	
	
	list($width, $height) = getimagesize($photo['withpath']);
	
	$type = pathinfo($photo['withpath']);
	$type = strtolower($type["extension"]);
	
	$minWidth = 200;
	$minHeight = 200;
	
	mezunProfilHTML::editImage($photo, $width, $height, $type, $minWidth, $minHeight);	
}
/**
* Profil resmi silme fonksiyonu
*/
function deleteImage() {
	global $dbase, $my;
	
	$query = "SELECT image FROM #__users WHERE id=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	
	$image = $dbase->loadResult();
	
	$src = ABSPATH.'/images/profil/'.$image;
	
	mimport('helpers.image.helper');
	
	if (mezunImageHelper::deleteImage($src)) {
		$dbase->setQuery("UPDATE #__users SET image='' WHERE id=".$dbase->Quote($my->id));
		$dbase->query();
	}
	
	$akistext = 'Profil resmini sildi';
	mezunGlobalHelper::AkisTracker($akistext);
	
	Redirect('index.php?option=site&bolum=profil&task=my');
}
/**
* Profil parolası değiştirme fonksiyonu
*/
function changePass() {
	global $dbase, $my;
	
	$password = getParam($_POST, 'password');
	$password2 = getParam($_POST, 'password2');
	
	if ($password == $password2) {
		
		$row = new mezunUsers($dbase);
		$salt = MakePassword(16);
		$crypt = md5($password.$salt);
		
		$password = $crypt.':'.$salt;
		
		$query = "UPDATE #__users SET password=".$dbase->Quote($password)." WHERE id=".$dbase->Quote($my->id);
		$dbase->setQuery($query);
		$dbase->query();
			
		Redirect('index.php?option=site&bolum=profil&task=my', 'Parolanız değiştirildi');
		
	} else {
		Redirect('index.php?option=site&bolum=profil&task=my', 'Parolalar uyuşmuyor!');
	}
}
/**
* Profil resmi ekleme/değiştirme fonksiyonu
*/
function saveImage() {
	global $dbase, $my;
	
	mimport('helpers.image.helper');
	
	$image = getParam($_FILES, 'image');
	
	if (!$image['name']) {
		Redirect('index.php?option=site&bolum=profil&task=my', 'Resim seçmemişsiniz');
	}
	
	$row = new mezunUsers($dbase);
	$row->load($my->id);
	
	//eğer varsa önce eski resmi silelim
	if ($row->image) {
		$src = ABSPATH.'/images/profil/'.$row->image;
		mezunImageHelper::deleteImage($src);
	}

	//şimdi yeni resmi yükleyelim
	$dest = ABSPATH.'/images/profil/';
	
	$minWidth = 200;
	$minHeight = 200;
	
	$maxWidth = 800;
	$maxHeight = 600;
	
	//uygun mu kontrol edelim
	mezunImageHelper::check($image['name']);
	
	//dosya adını değiştirelim
	$newname = mezunImageHelper::changeName($image['name']);
	//hedef dosya
	$targetfile= $dest.$newname;
	
	mimport('helpers.file.file');
	
	//upload image
	mezunFile::upload($image['tmp_name'], $targetfile);
	
		
	$query = "UPDATE #__users SET image=".$dbase->Quote($newname)
	. "\n WHERE id=".$dbase->Quote($row->id);
	$dbase->setQuery($query);
	$dbase->query();
	
	list($imgwidth, $imgheight) = getimagesize($targetfile);
	
	if ($imgwidth > $maxWidth) {
		$oran = round($maxWidth / $imgwidth, 2);
		
		$newwidth = $maxWidth;
		$newheight = round($oran * $imgheight);
		
	} else if ($imgheight > $maxHeight) {
		$oran = floor($maxHeight / $imgheight, 2);
		
		$newheight = $maxHeight;
		$newwidth = round($oran * $imgwidth);
		
	} else {
		$newheight = $imgheight;
		$newwidth = $imgwidth;
	}
		
	mezunImageHelper::resize($targetfile, $newwidth, $newheight, $imgwidth, $imgheight);
	
	$akistext = 'Profil resmi yükledi';
	mezunGlobalHelper::AkisTracker($akistext);
	
	if ($error) {
	Redirect('index.php?option=site&bolum=profil&task=my', $error);
	} else {
		if ($newwidth > $minWidth || $newheight > $minHeight) {
			Redirect('index.php?option=site&bolum=profil&task=editimage');
		} else {
			Redirect('index.php?option=site&bolum=profil&task=my');        
		}
	}
}
/**
* Profil güncellemesi için açılacak sayfa
*/
function editProfile() {
	global $dbase, $my;
	
	$row = new mezunUsers($dbase);
	$row->load($my->id);
	
	$user = new mezunProfilHelper($row);
	
	mezunProfilHTML::editProfile($row);
	
}
/**
* Profil güncelleme fonksiyonu
*/
function saveProfile() {
	global $dbase, $my;
	
	$row = new mezunUsers( $dbase );
	
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$row->id = $my->id;
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$akistext = 'Profilini düzenledi';
	mezunGlobalHelper::AkisTracker($akistext);

	Redirect('index.php?option=site&bolum=profil&task=my', 'Değişiklikler başarıyla kaydedildi');
}
/**
* Profil gösterimi
* @param mixed $id gösterim yapılacak kullanıcının id si
*/
function getProfile($id) {
	global $dbase, $my;
	
	mimport('helpers.modules.online.helper');
	mimport('helpers.modules.arkadas.helper');
	mimport('helpers.modules.istek.helper');
	mimport('global.likes');
	
	$canEdit = false;
	$canShow = false;
	$user = new mezunUsers($dbase);
	
	if ($id) {
		
	if ($id == $my->id) {
		$canEdit = true;
		$canShow = true;
		
		$user->load($my->id);
	} else {
		$user->load($id);
		
	if (!$user->activated) {
		NotAuth();
		return;
	}
	}
	
	if (($my->id == $user->id)) {
		$canSendMsg = false;
		$canSendIstem = false;
	} else {
		
	//Kullanıcı ile arkadaş ise
	if (mezunArkadasHelper::checkArkadaslik($user->id)) {
		$canSendIstem = false;
		$canSendMsg   = true;
		$canShow      = true;
	//Kullanıcı arada bir istek var ise
	} else if (mezunIstekHelper::checkIstek($user->id)) {
		$canSendIstem = false;
		$canSendMsg   = false;
		$canShow      = false;
	//Ne bir istek var ne de bir arkadaşlık
	} else if ((!mezunArkadasHelper::checkArkadaslik($user->id)) && (!mezunIstekHelper::checkIstek($user->id))) {
		$canSendIstem = true;
		$canSendMsg   = false;
		$canShow      = false;
	}
	}
	
	//Kullanıcı ile ilgili tüm bilgileri alalım
	$query = "SELECT u.*, s.name as sehiradi, ss.name as dogumyeri, b.name AS brans FROM #__users AS u"
	. "\n LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.dogumyeri"
	. "\n LEFT JOIN #__branslar AS b ON b.id=u.brans"
	. "\n WHERE u.id=".$dbase->Quote($user->id);
	$dbase->setQuery($query);
	
	$dbase->loadObject($row);
	
	if (!$row) {
		NotAuth();
		return;
	}
	
	} else {
		NotAuth();
		return;	
	}
	
	mezunProfilHTML::getProfile($row, $canEdit, $canSendMsg, $canSendIstem, $canShow);
}
