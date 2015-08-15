<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	Templates();
	break;
	
	case 'editcss':
	editTemplateCSS($id);
	break;
	
	case 'delete':
	deleteTemplate($id);
	break;
	
	case 'new':
	newTemplate();
	break;
}

function Templates() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT * FROM #__templates");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	adminTemplatesHTML::Templates($list, $pageNav);
}

