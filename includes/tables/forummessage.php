<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForummessage extends mezunTable {
	
	var $ID_MSG = null;
	
	var $ID_TOPIC = null;
	
	var $ID_BOARD = null;
	
	var $posterTime = null;
	
	var $ID_MEMBER = null;
	
	var $ID_MSG_MODIFIED = null;
	
	var $posterIP = null;
	
	var $subject = null;
	
	var $body = null;
	
	function mezunForummessage( &$db ) {
		$this->mezunTable( '#__forum_messages', 'ID_MSG', $db );
	}
}