<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

if (SEF) {
	
	$parts = explode('/index.php/', $_SERVER['REQUEST_URI']);
	
	$qstring = '';
	
	if (!empty($parts[1])) {
	$upart = explode('/', $parts[1]);
	
		for($i=0; $i<count($upart);$i++) {
			$part = str_replace('/', '', $upart[$i]);
			
			if (!empty($part)) {
				list($key, $value) = explode(',', $part);
					if ($i==0) {
						$_GET[$key] = $value;
						$_REQUEST[$key] = $value;
						$qstring .= $key.'='.$value;
					} else {
						$_GET[$key] = $value;
						$_REQUEST[$key] = $value;
						$qstring .= '&'.$key.'='.$value;
					}
			}
		}
		
		$_SERVER['QUERY_STRING'] = $qstring;
		$REQUEST_URI             = $parts[0].'/index.php?'.$qstring;
		$_SERVER['REQUEST_URI']  = $REQUEST_URI; 
	} else {
		$_SERVER['REQUEST_URI']  = '';
	}
	
	//var_dump($upart);
	//var_dump($qstring);
	//var_dump($_SERVER['REQUEST_URI']);
	
	if (defined('RG_EMULATION') && RG_EMULATION == 1) {
			// Extract to globals
			while(list($key,$value)=each($_GET)) {
				if ($key!="GLOBALS") {
					$GLOBALS[$key]=$value;
				}
			}
	}
}

function sefLink( $string ) {

	// SEF URL Handling
	if (SEF) {
		// Replace all &amp; with &
		$string = str_replace( '&amp;', '&', $string );

		// Home index.php
		if ($string == 'index.php') {
			$string = '';
		}

		// break link into url component parts
		$url = parse_url( $string );

		//var_dump($url);
		// check if link contained fragment identifiers (ex. #foo)
		$fragment = '';
		if ( isset($url['fragment']) ) {
			// ensure fragment identifiers are compatible with HTML4
			if (preg_match('@^[A-Za-z][A-Za-z0-9:_.-]*$@', $url['fragment'])) {
				$fragment = '#'. $url['fragment'];
			}
		}

		// check if link contained a query component
		if ( isset($url['query']) ) {
			// special handling for javascript
			$url['query'] = stripslashes( str_replace( '+', '%2b', $url['query'] ) );
			// clean possible xss attacks
			$url['query'] = preg_replace( "'%3Cscript[^%3E]*%3E.*?%3C/script%3E'si", '', $url['query'] );

			// break url into component parts
			$parts = explode('&', $url['query'] );

			$sefstring = '';
			
			foreach ($parts as $part) {
				$part = explode('=', $part);
				$sefstring .= $part[0].','.$part[1].'/';
			}
			
			$string = $sefstring;
		// no query given. Empty $string to get only the fragment
		// index.php#anchor or index.php?#anchor
		} else {
			$string = '';
		}

		// allows SEF without mod_rewrite
		// comment line below if you dont have mod_rewrite
		//return SITEURL .'/'. $string . $fragment;

		// allows SEF without mod_rewrite
		// uncomment Line 512 and comment out Line 514

		// uncomment line below if you dont have mod_rewrite
		return SITEURL .'/index.php/'. $string . $fragment;
		// If the above doesnt work - try uncommenting this line instead
		// return SITEURL .'/index.php?/'. $string . $fragment;
	} else {
		return $string;
	}
} 
