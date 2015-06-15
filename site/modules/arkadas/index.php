<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(mosGetParam($_REQUEST, 'id')); 

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	getArkadasList();
	break;
	
	case 'durum':
	changeDurum();
	break;
}

function getArkadasList() {
	
}