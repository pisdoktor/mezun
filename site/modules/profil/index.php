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
}

function changePass() {
	global $dbase, $my;
	
	$password = mosGetParam($_POST, 'password');
	$password2 = mosGetParam($_POST, 'password2');
}

function saveImage() {
	global $dbase, $my;
	
	$row = new Users($dbase);
	$row->load($my->id);
	
	//eğer varsa önce eski resmi silelim
	if ($row->image) {
		@unlink(ABSPATH.'/images/'.$row->image);
	}
	
	//şimdi yeni resmi yükleyelim
	$image = mosGetParam($_FILES, 'image');
		
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

function editProfile() {
	global $dbase, $my;
	
}

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
		
	Profile::getProfile($row, $edit);
}
