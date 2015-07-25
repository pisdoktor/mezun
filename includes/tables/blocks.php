<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunBlocks extends mezunTable {
	
	var $id     = null;
	
	var $title = null;
	
	var $content   = null;
	
	var $ordering  = null;
	
	var $position = null;
	
	var $published = null;
	
	var $block = null;
	
	var $showtitle = null;
	
	var $iscore = null;
	
	function mezunBlocks( &$db ) {
		$this->mezunTable( '#__blocks', 'id', $db );
	}
}

class mezunBlocksMenu extends mezunTable {
	
	var $blockid     = null;
	
	var $bolum = null;
	
	function mezunBlocksMenu( &$db ) {
		$this->mezunTable( '#__blocks_menu', 'blockid', $db );
	}
}