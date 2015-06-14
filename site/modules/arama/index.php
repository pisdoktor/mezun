<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	searchForm();
	break;
	
	case 'search':
	searchResults();
	break;
}

function searchForm() {
	
	
}

function searchResults() {
	
}
