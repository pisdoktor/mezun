<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$itemid = intval(getParam($_REQUEST, 'itemid'));
$nerede = strval(getParam($_REQUEST, 'nerede'));

mimport('global.likes');

switch($task) {
	default:
	break;
	
	case 'like':
	Like($id, $nerede, 1);
	break;
	
	case 'unlike':
	Like($id, $nerede, 0);
	break;
}

function Like($itemid, $nerede, $status) {
	global $dbase, $my;
	
	$errors         = '';      // array to hold validation errors
	$data           = array();      // array to pass back data
	
	if (empty($itemid)) {
		$errors = 'ID yok';
	}
	if (empty($nerede)) {
		$errors = 'Bölüm yok';
	}
	
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['button']  = $errors;
	} else {
	
	//Beğeni gir
	if ($status) {
		
		$dbase->setQuery("INSERT INTO #__likes "
		."\n (itemid, userid, bolum) VALUES (".$dbase->Quote($itemid).",".$dbase->Quote($my->id).",".$dbase->Quote($nerede).")");
		$dbase->query();
		
		$data['count'] = mezunGlobalLikes::totalLikes($itemid, $nerede);
		
		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['itemid'] = $itemid;
		$data['button'] = 'Beğeni Kaldır';
		$data['url'] = 'index2.php?option=site&bolum=like&task=unlike&id='.$itemid.'&nerede='.$nerede;
		
		
	} 
	//Beğeni kaldır
	else {
		
		$dbase->setQuery("DELETE FROM #__likes WHERE itemid=".$dbase->Quote($itemid)." AND bolum=".$dbase->Quote($nerede)." AND userid=".$dbase->Quote($my->id));
		
		$dbase->query();
		
		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['itemid'] = $itemid;
		$data['button'] = 'Beğen';
		$data['url'] = 'index2.php?option=site&bolum=like&task=like&id='.$itemid.'&nerede='.$nerede;
		
		$data['count'] = mezunGlobalLikes::totalLikes($itemid, $nerede);
		
	}
	
	}
	
		// return all our data to an AJAX call
	echo json_encode($data);
}
