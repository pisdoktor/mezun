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
