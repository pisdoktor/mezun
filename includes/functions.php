<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

function loadSiteModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	case 'site':
	initModule($bolum);
	break;
	
	case 'admin':
	convertAdmin();
	break;
	}
	
}

function initModule($bolum) {
	global $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	if ($bolum) {
		include_once(ABSPATH.'/site/modules/'.$bolum.'/index.php');
	} else {
		include_once(ABSPATH.'/site/modules/akis/index.php');
	}
} 

function convertAdmin() {
	global $mainframe, $dbase, $my;
	
	if ($my->id == 1) {
	$session = new mezunSession($dbase);
	$session->load($mainframe->_session->session);

	$session->access_type = 'admin';
	$session->update();
	
	Redirect('index.php');
	} else {
		NotAuth();
	}    
}

function getFooter() {
	include(ABSPATH.'/site/templates/'.SITETEMPLATE.'/footer.php');
}

function initBlocks() {
	global $dbase, $my;
		
		$query = "SELECT id, title, block, position, content, showtitle"
		. "\n FROM #__blocks AS b"
		. "\n INNER JOIN #__blocks_menu AS bm ON bm.blockid = b.id"
		. "\n WHERE b.published = 1"
		. "\n AND (bm.bolum = ".$dbase->Quote($my->nerede)." OR bm.bolum='')"
		. "\n ORDER BY b.ordering";

		$dbase->setQuery( $query );
		$blocks = $dbase->loadObjectList();

		foreach ($blocks as $block) {
			$mezunblocks[$block->position][] = $block;
		}
	if (empty($mezunblocks)) {
		$mezunblocks = '';
	}
	return $mezunblocks;
}

function LoadBlocks( $position='left' ) {

	mimport('global.block');

	$allBlocks = initBlocks();
	
	if (isset( $allBlocks[$position] )) {
		$blocks = $allBlocks[$position];
	} else {
		$blocks = array();
	}

	$prepend = '<div class="panel panel-default">';
	$postpend = '</div>';

	foreach ($blocks as $block) {
		
		echo $prepend;

		if ((substr("$block->block",0,6))=='block_') {
		// normal blocks
			mezunGlobalBlock::normalblock($block);
		} else {
		// custom or new blocks
			mezunGlobalBlock::htmlblock($block);
		}

		echo $postpend;
	}
}

function CountBlocks( $position='left' ) {
	global $dbase;

	$blocks = initBlocks();
	
	if (isset( $blocks[$position] )) {
		return count( $blocks[$position] );
	} else {
		return 0;
	}
}