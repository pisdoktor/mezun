<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

mimport('tables.stats');

class mezunStatsHelper {
	
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
		$this->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Yok';
		$this->segments     = parse_url($this->referer);
		$this->refererHost  = isset($this->segments['host']) ? $this->segments['host'] : 'Yok';
		$this->agent        = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Bilinmiyor';
		$this->browser      = $this->GetBrowser($this->agent);
		$this->os           = $this->GetOS($this->agent);
		$this->remoteAddr   = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Bilinmiyor';
		$this->domain       = @gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
		// domainin son elementini dışarı alalım
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
		global $dbase, $my;
		
		
		if (STATS == 0) {
			return;
		}
		
		if ($my->access_type == 'admin') {
			return;
		}
		
		$dbase->setQuery("SELECT COUNT(*) FROM #__stats_blocklist "
			. "\n WHERE    block=".$dbase->Quote($this->remoteAddr)
			. "\n OR block=".$dbase->Quote($this->refererHost)
			. "\n OR block=".$dbase->Quote($this->agent)
			. "\n OR block=".$dbase->Quote($this->browser)
			. "\n OR block=".$dbase->Quote($this->os)
			. "\n OR block=".$dbase->Quote($this->domain)
			);
			
			if($dbase->loadResult()){
				return;
			}

				$row = new mezunStats($dbase);
				
				$time = time() + (OFFSET*3600);
				$date_time = date('Y-m-d H:i:s', $time);
				
				$row->uri           = $this->requestUrl;
				$row->referer       = $this->referer;
				$row->referer_host  = $this->refererHost;
				$row->agent         = $this->agent;
				$row->browser       = $this->browser;
				$row->os            = $this->os;
				$row->remote_add    = $this->remoteAddr;
				$row->domain        = $this->domain;
				$row->date_time     = $date_time;
				
				$row->store();
				
			if (COUNTSTATS) {
				$this->Analytics_CountStats();
			}
				
	}
	/**
	* Browser detect function
	* 
	* @param mixed $agent
	* @return mixed
	*/
	public static function GetBrowser( $agent ) {
	
	require( ABSPATH .'/includes/helpers/stats/agent_browser.php' );

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

	require( ABSPATH .'/includes/helpers/stats/agent_os.php' );

	foreach ($osSearchOrder as $key) {
		if (preg_match( "/$key/i", $agent )) {
			return $osAlias[$key];
			break;
		}
	}

	return 'Bilinmiyor';
	}
	
	public function Analytics_CountStats() {
		global $dbase;
		
		if (COUNTSTATS == 1) {
			if (getParam( $_COOKIE, 'mosvisitor', 0 )) {
				return;
			}
			setcookie( 'mosvisitor', 1 );

			/**
			* Type=0 Browser
			* 
			* @var mixed
			*/
			$query = "SELECT COUNT(*)"
			. "\n FROM #__stats_counts"
			. "\n WHERE agent = " . $dbase->Quote( $this->browser )
			. "\n AND type = 0"
			;
			$dbase->setQuery( $query );
			
			if ($dbase->loadResult()) {
				$query = "UPDATE #__stats_counts"
				. "\n SET hits = ( hits + 1 )"
				. "\n WHERE agent = " . $dbase->Quote( $this->browser )
				. "\n AND type = 0"
				;
				$dbase->setQuery( $query );
			} else {
				$query = "INSERT INTO #__stats_counts"
				. "\n ( agent, type, hits )"
				. "\n VALUES ( " . $dbase->Quote( $this->browser ) . ", 0, 1 )"
				;
				$dbase->setQuery( $query );
			}
			$dbase->query();

			/**
			* Type=1 Operation System
			* 
			* @var mixed
			*/
			$query = "SELECT COUNT(*)"
			. "\n FROM #__stats_counts"
			. "\n WHERE agent = " . $dbase->Quote( $this->os )
			. "\n AND type = 1"
			;
			$dbase->setQuery( $query );
			
			if ($dbase->loadResult()) {
				$query = "UPDATE #__stats_counts"
				. "\n SET hits = ( hits + 1 )"
				. "\n WHERE agent = " . $dbase->Quote( $this->os )
				. "\n AND type = 1"
				;
				$dbase->setQuery( $query );
			} else {
				$query = "INSERT INTO #__stats_counts"
				. "\n ( agent, type, hits )"
				. "\n VALUES ( " . $dbase->Quote( $this->os ) . ", 1, 1 )"
				;
				$dbase->setQuery( $query );
			}
			$dbase->query();

			/**
			* Type=2 Domain
			* 
			* @var mixed
			*/
			$query = "SELECT COUNT(*)"
			. "\n FROM #__stats_counts"
			. "\n WHERE agent = " . $dbase->Quote( $this->domain )
			. "\n AND type = 2"
			;
			$dbase->setQuery( $query );
			
			if ($dbase->loadResult()) {
				$query = "UPDATE #__stats_counts"
				. "\n SET hits = ( hits + 1 )"
				. "\n WHERE agent = " . $dbase->Quote( $this->domain )
				. "\n AND type = 2"
				;
				$dbase->setQuery( $query );
			} else {
				$query = "INSERT INTO #__stats_counts"
				. "\n ( agent, type, hits )"
				. "\n VALUES ( " . $dbase->Quote( $this->domain ) . ", 2, 1 )"
				;
				$dbase->setQuery( $query );
			}
			$dbase->query();
		}
	}
}