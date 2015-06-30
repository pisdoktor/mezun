<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Analytics {
	
	var $requestUrl = null;
	
	var $referer = null;
	
	var $segments = null;
	
	var $refererHost = null;
	
	var $agent = null;
	
	var $browser = null;
	
	var $os = null;
	
	var $remoteAddr = null;
	
	var $domain = null;
	
	/**
	* Constructor function 
	* 
	*/
	public function __construct() {
		
		$this->requestUrl   = $_SERVER['REQUEST_URI'];
		$this->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Bilinmiyor';
		$this->segments     = parse_url($this->referer);
		$this->refererHost  = isset($this->segments['host']) ? $this->segments['host'] : 'Bilinmiyor';
		$this->agent        = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Bilinmiyor';
		$this->browser      = $this->GetBrowser($this->agent);
		$this->os           = $this->GetOS($this->agent);
		$this->remoteAddr   = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Bilinmiyor';
		$this->domain       = @gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
		// domainin son elementini dışarı atalım
		$this->domain = explode( '.', $this->domain );
		$this->domain = $this->domain[count( $this->domain )-1];

		if (is_numeric( $this->domain )) {
		$this->domain = "Bilinmiyor";
		}
	}
	/**
	* Stats tracker function
	* 
	*/
	public function tracker() {
		global $dbase, $option;
		
		
		if (STATS == 0) {
			return;
		}
		
		if ($option == 'admin') {
			return;
		}
		
		$dbase->setQuery("SELECT 1 FROM #__stats_blocklist "
			. "\n WHERE	block=".$this->remoteAddr
			. "\n OR block=".$this->refererHost
			. "\n OR block=".$this->agent
			. "\n OR block=".$this->browser
			. "\n OR block=".$this->os
			. "\n OR block=".$this->domain
			);
			$dbase->loadObject($block);

			if($block){
				return;
			}

				$row = new Stats($dbase);
				
				$row->uri           = $this->requestUrl;
				$row->referer       = $this->referer;
				$row->referer_host  = $this->refererHost;
				$row->agent         = $this->agent;
				$row->browser       = $this->browser;
				$row->os            = $this->os;
				$row->remote_add    = $this->remoteAddr;
				$row->domain        = $this->domain;
				$row->date_time     = date('Y-m-d H:i:s');
				
				$row->store();
				
	}

	/**
	* Browser detect function
	* 
	* @param mixed $agent
	* @return mixed
	*/
	public static function GetBrowser( $agent ) {
	
	require( ABSPATH .'/includes/stats/agent_browser.php' );

	if (preg_match( "/msie[\/\sa-z]*([\d\.]*)/i", $agent, $m )
	&& !preg_match( "/webtv/i", $agent )
	&& !preg_match( "/omniweb/i", $agent )
	&& !preg_match( "/opera/i", $agent )) {
		// IE
		return "MS Internet Explorer $m[1]";
	} else if (preg_match( "/netscape.?\/([\d\.]*)/i", $agent, $m )) {
		// Netscape 6.x, 7.x ...
		return "Netscape $m[1]";
	} else if ( preg_match( "/mozilla[\/\sa-z]*([\d\.]*)/i", $agent, $m )
	&& !preg_match( "/gecko/i", $agent )
	&& !preg_match( "/compatible/i", $agent )
	&& !preg_match( "/opera/i", $agent )
	&& !preg_match( "/galeon/i", $agent )
	&& !preg_match( "/safari/i", $agent )) {
		// Netscape 3.x, 4.x ...
		return "Netscape $m[1]";
	} else {
		// Other
		$found = false;
		foreach ($browserSearchOrder as $key) {
			if (preg_match( "/$key.?\/([\d\.]*)/i", $agent, $m )) {
				$name = "$browsersAlias[$key] $m[1]";
				return $name;
				break;
			}
		}
	}

	return 'Bilinmiyor';
	}
	
	/**
	* OS Detect function
	* 
	* @param mixed $agent
	*/
	public static function GetOS( $agent ) {

	require( ABSPATH .'/includes/stats/agent_os.php' );

	foreach ($osSearchOrder as $key) {
		if (preg_match( "/$key/i", $agent )) {
			return $osAlias[$key];
			break;
		}
	}

	return 'Bilinmiyor';
	}
}

/**
* Veritabanı bağlayıcısı
*/
class Stats extends DBTable {
	
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
	
	function Stats( &$db ) {
		$this->DBTable( '#__stats', 'id', $db );
	}
}
/**
* Blocklist bağlayıcısı
*/
class BlockList extends DBTable {
	
	var $id = null;
	
	var $block = null;
	
	function BlockList(&$db) {
		$this->DBTable('#__stats_blocklist', 'id', $db);
	}
}
