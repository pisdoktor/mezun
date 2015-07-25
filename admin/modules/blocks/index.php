<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$cid = getParam($_REQUEST, 'cid');
$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');
include_once(ABSPATH.'/admin/includes/admin.html.php');

mimport('tables.blocks');

switch($task) {
	default:
	getBlocks();
	break;
	
	case 'edit':
	editBlock($cid);
	break;
	
	case 'editx':
	editBlock($id);
	break;
	
	case 'new':
	editBlock(0);
	break;
	
	case 'save':
	saveBlock();
	break;
	
	case 'cancel':
	cancelBlock();
	break;
	
	case 'delete':
	deleteBlock($cid);
	break;
}

//tamamlanacak!!!!
function deleteBlock($cid) {
	
}

function saveBlock() {
	global $dbase;
	
	$row = new mezunBlocks($dbase);
	$row->bind($_POST);
	$row->store();
	$row->updateOrder('position='.$row->position);
	
	Redirect('index.php?option=admin&bolum=blocks');
	
}

function editBlock($cid) {
	global $dbase;
	
	$row = new mezunBlocks($dbase);
	$row->load($cid);
	
	BlocksHTML::editBlock($row);
}

function getBlocks() {
	global $dbase, $limit, $limitstart;
	
	$dbase->setQuery("SELECT * FROM #__blocks ORDER BY position, ordering ASC");
	
	$rows = $dbase->loadObjectList();
	
	foreach ($rows as $row) {
		$dbase->setQuery("SELECT bolum FROM #__blocks_menu WHERE blockid=".$dbase->Quote($row->id));
		$bolums = $dbase->loadResultArray();
		
		$row->bolum = implode(',', $bolums);
	}
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	BlocksHTML::getBlocks($rows, $pageNav);
}

function cancelBlock() {
	Return('index.php?option=admin&bolum=blocks');
}

