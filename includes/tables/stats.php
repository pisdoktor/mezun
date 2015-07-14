<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunStats extends mezunTable {
	
	var $id = null;
	
	var $uri = null;
	
	var $referer = null;
	
	var $referer_host = null;
	
	var $agent  = null;
	
	var $browser = null;
	
	var $os = null;
	
	var $remote_add = null;
	
	var $domain = null;
	
	var $date_time = null;
	
	function mezunStats( &$db ) {
		$this->mezunTable( '#__stats', 'id', $db );
	}
}
/**
* Blocklist bağlayıcısı
*/
class Analytics_BlockList extends mezunTable {
	
	var $id = null;
	
	var $block = null;
	
	function Analytics_BlockList(&$db) {
		$this->mezunTable('#__stats_blocklist', 'id', $db);
	}
}
  
?>
