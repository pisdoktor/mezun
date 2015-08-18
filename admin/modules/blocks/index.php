<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

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
	deleteBlock($id);
	break;
}


function deleteBlock($id) {
	global $dbase;
	
	$row = new mezunBlocks($dbase);
	$row->load($id);
	
	if ($row->block) {
		mimport('helpers.file.file');
		
		mezunFile::delete(ABSPATH.'/site/blocks/'.$row->block.'.php');
	}
	
	
	$dbase->setQuery("DELETE FROM #__blocks WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	//menüden kaldır
	$dbase->setQuery("DELETE FROM #__blocks_menu WHERE blockid=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect('index.php?option=admin&bolum=blocks');	
}

function saveBlock() {
	global $dbase;
	
	$row = new mezunBlocks($dbase);
	$row->bind($_POST);
	$row->store();
	$row->updateOrder('position='.$row->position);
	
	//block_menu
	$block_menus = getParam($_REQUEST, 'block_menus');
	
	$dbase->setQuery("DELETE FROM #__blocks_menu WHERE blockid=".$row->id);
	$dbase->query();
	
	
	foreach($block_menus as $block_menu) {
		$dbase->setQuery("INSERT INTO #__blocks_menu (`blockid`, `bolum`) VALUES ('".$row->id."', '".$block_menu."')");
		$dbase->query();
	}
	
	Redirect('index.php?option=admin&bolum=blocks');
}

function editBlock($id) {
	global $dbase;
	
	$row = new mezunBlocks($dbase);
	$row->load($id);
	
	//block_menu
	$dbase->setQuery("SELECT bolum AS value FROM #__blocks_menu WHERE blockid=".$row->id);
	$row->block_menus = $dbase->loadObjectList();
	
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
	Redirect('index.php?option=admin&bolum=blocks');
}

