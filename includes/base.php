<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

require(dirname(__FILE__).'/importer.php');

//database sınıfını import edelim
mimport('helpers.database.database');
mimport('helpers.database.table');
//version
mimport('global.version');
//mail
mimport('global.mail');
//mainframe
mimport('helpers.mainframe');
//genel kullanılan fonksiyonları alalım
mimport('global.helper');
//mail
mimport('helpers.phpmailer.phpmailer');
//filter 
mimport('helpers.filter.inputfilter');
//stats
mimport('helpers.stats.helper');
//html
mimport('helpers.html.html');
//pagenation
mimport('helpers.html.pagenation');


$dbase = new mezunDatabase( DB_HOST, DB_USER, DB_PASS, DB, DB_PREFIX );

//config bilgilerini alalım
$dbase->setQuery("SELECT * FROM #__config");
$configs = $dbase->loadObjectList();
//define edelim
foreach ($configs as $config) {
	define($config->name, $config->var);
}

ini_set('magic_quotes_runtime', 0);

if ( ERROR_REPORT === 0 || ERROR_REPORT === '0' ) {
	error_reporting( 0 );
} else if (ERROR_REPORT > 0) {
	error_reporting( E_ALL );
}

if ($dbase->getErrorNum()) {
	$systemError = $dbase->getErrorNum();
	$systemErrorMsg = $dbase->getErrorMsg();
	include ABSPATH . '/includes/global/closed.php';
	exit();
}

$dbase->debug( DEBUGMODE );

// platform neurtral url handling
if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$request_uri = $_SERVER['REQUEST_URI'];
} else {
	$request_uri = $_SERVER['SCRIPT_NAME'];
	// Append the query string if it exists and isn't null
	if ( isset( $_SERVER['QUERY_STRING'] ) && !empty( $_SERVER['QUERY_STRING'] ) ) {
		$request_uri .= '?' . $_SERVER['QUERY_STRING'];
	}
}
$_SERVER['REQUEST_URI'] = $request_uri;

// current server time
$now = date( 'Y-m-d H:i:s', time() );

DEFINE( '_CURRENT_SERVER_TIME', $now );
DEFINE( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' );

// Non http/https URL Schemes
$url_schemes = 'data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:, mms:';
DEFINE( '_URL_SCHEMES', $url_schemes );

// disable strict mode in MySQL 5
if (!defined( '_SET_SQLMODE' )) {
	/** ensure that functions are declared only once */
	define( '_SET_SQLMODE', 1 );

	// if running mysql 5, set sql-mode to mysql40 - thereby circumventing strict mode problems
	if ( strpos( $dbase->getVersion(), '5' ) === 0 ) {
		$query = "SET sql_mode = 'MYSQL40'";
		$dbase->setQuery( $query );
		$dbase->query();
	}
}
/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _NOTRIM prevents trim, _ALLOWHTML allows safe html, _ALLOWRAW allows raw input
 */
define( "_NOTRIM", 0x0001 );
define( "_ALLOWHTML", 0x0002 );
define( "_ALLOWRAW", 0x0004 );
function getParam( &$arr, $name, $def=null, $mask=0 ) {
	static $noHtmlFilter 	= null;
	static $safeHtmlFilter 	= null;

	$return = null;
	if (isset( $arr[$name] )) {
		$return = $arr[$name];

		if (is_string( $return )) {
			// trim data
			if (!($mask&_NOTRIM)) {
				$return = trim( $return );
			}

			if ($mask&_ALLOWRAW) {
				// do nothing
			} else if ($mask&_ALLOWHTML) {
				// do nothing - compatibility mode
			} else {
				// send to inputfilter
				if (is_null( $noHtmlFilter )) {
					$noHtmlFilter = new mezunInputFilter( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */ );
				}
				$return = $noHtmlFilter->process( $return );

				if (!empty($return) && is_numeric($def)) {
				// if value is defined and default value is numeric set variable type to integer
					$return = intval($return);
				}
			}

			// account for magic quotes setting
			if (!get_magic_quotes_gpc()) {
				$return = addslashes( $return );
			}
		}

		return $return;
	} else {
		return $def;
	}
}
/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function mezunStripslashes( &$value ) {
	$ret = '';
	if (is_string( $value )) {
		$ret = stripslashes( $value );
	} else {
		if (is_array( $value )) {
			$ret = array();
			foreach ($value as $key => $val) {
				$ret[$key] = mosStripslashes( $val );
			}
		} else {
			$ret = $value;
		}
	}
	return $ret;
}
/**
* Copy the named array content into the object as properties
* only existing properties of object are filled. when undefined in hash, properties wont be deleted
* @param array the input array
* @param obj byref the object to fill of any class
* @param string
* @param boolean
*/
function BindArrayToObject( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) {
	if (!is_array( $array ) || !is_object( $obj )) {
		return (false);
	}

	$ignore = ' ' . $ignore . ' ';
	foreach (get_object_vars($obj) as $k => $v) {
		if( substr( $k, 0, 1 ) != '_' ) {			// internal attributes of an object are ignored
			if (strpos( $ignore, ' ' . $k . ' ') === false) {
				if ($prefix) {
					$ak = $prefix . $k;
				} else {
					$ak = $k;
				}
				if (isset($array[$ak])) {
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes( $array[$ak] ) : $array[$ak];
				}
			}
		}
	}

	return true;
}
/**
* Utility function redirect the browser location to another url
*
* Can optionally provide a message.
* @param string The file system path
* @param string A filter for the names
*/
function Redirect( $url, $msg='' ) {

	// specific filters
	$iFilter = new mezunInputFilter();
	$url = $iFilter->process( $url );
	if (!empty($msg)) {
		$msg = $iFilter->process( $msg );
	}

	// Strip out any line breaks and throw away the rest
	$url = preg_split("/[\r\n]/", $url);
	$url = $url[0];

	if ($iFilter->badAttributeValue( array( 'href', $url ))) {
		$url = SITEURL;
	}

	if (trim( $msg )) {
		if (strpos( $url, '?' )) {
			$url .= '&mosmsg=' . urlencode( $msg );
		} else {
			$url .= '?mosmsg=' . urlencode( $msg );
		}
	}
	
	if (SEF) {
		$url = sefLink($url);
	}

	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ". $url );
	}
	exit();
}

function treeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) {

	if (@$children[$id] && $level <= $maxlevel) {
		foreach ($children[$id] as $v) {
			$id = $v->id;

			if ( $type ) {
				$pre 	= '<sup>L</sup>&nbsp;';
				$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			} else {
				$pre 	= '- ';
				$spacer = '&nbsp;&nbsp;';
			}

			if ( $v->parent == 0 ) {
				$txt 	= $v->name;
			} else {
				$txt 	= $pre . $v->name;
			}
			$pt = $v->parent;
			$list[$id] = $v;
			$list[$id]->treename = "$indent$txt";
			$list[$id]->children = count( @$children[$id] );

			$list = treeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
		}
	}
	return $list;
}

function ObjectToArray($p_obj) {
	$retarray = null;
	if(is_object($p_obj))
	{
		$retarray = array();
		foreach (get_object_vars($p_obj) as $k => $v)
		{
			if(is_object($v))
			$retarray[$k] = ObjectToArray($v);
			else
			$retarray[$k] = $v;
		}
	}
	return $retarray;
}
/**
* Üstünde çalışmak gerekiyor...!!!!
* 
* @param mixed $text
* @param mixed $action
* @param mixed $mode
*/
function ErrorAlert( $text, $action='window.history.go(-1);', $mode=1 ) {
	$text = nl2br( $text );
	$text = addslashes( $text );
	$text = strip_tags( $text );

	switch ( $mode ) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._ISO."\" />";
			echo "<script>alert('$text'); $action</script> \n";
			//echo '<noscript>';
			//mosRedirect( @$_SERVER['HTTP_REFERER'], $text );
			//echo '</noscript>';
			break;
	}

	exit;
}

/**
* Makes a variable safe to display in forms
*
* Object parameters that are non-string, array, object or start with underscore
* will be converted
* @param object An object to be parsed
* @param int The optional quote style for the htmlspecialchars function
* @param string|array An optional single field name or array of field names not
*					 to be parsed (eg, for a textarea)
*/
function MakeHtmlSafe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
	if (is_object( $mixed )) {
		foreach (get_object_vars( $mixed ) as $k => $v) {
			if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' ) {
				continue;
			}
			if (is_string( $exclude_keys ) && $k == $exclude_keys) {
				continue;
			} else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys )) {
				continue;
			}
			$mixed->$k = htmlspecialchars( $v, $quote_style );
		}
	}
}
/**
* Returns formated date according to current local and adds time offset
* @param string date in datetime format
* @param string format optional format for strftime
* @param offset time offset if different than global one
* @returns formated date
*/
function FormatDate( $date, $format="", $offset=NULL ){
	
	if ( $format == '' ) {
		// %Y-%m-%d %H:%M:%S
		$format = '%d-%m-%Y %H:%M:%S';
	}
	if ( is_null($offset) ) {
		$offset = OFFSET;
	}
	if ( $date && preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $regs ) ) {
		$date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
		$date = $date > -1 ? strftime( $format, $date + ($offset*60*60) ) : '-';
	}
	return $date;
}
/**
 * Initialise GZIP
 */
function initGzip() {
	global $do_gzip_compress;

	$do_gzip_compress = FALSE;
	if (GZIPCOMP == 1) {
		$phpver 	= phpversion();
		$useragent 	= getParam( $_SERVER, 'HTTP_USER_AGENT', '' );
		$canZip 	= getParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

		$gzip_check 	= 0;
		$zlib_check 	= 0;
		$gz_check		= 0;
		$zlibO_check	= 0;
		$sid_check		= 0;
		if ( strpos( $canZip, 'gzip' ) !== false) {
			$gzip_check = 1;
		}
		if ( extension_loaded( 'zlib' ) ) {
			$zlib_check = 1;
		}
		if ( function_exists('ob_gzhandler') ) {
			$gz_check = 1;
		}
		if ( ini_get('zlib.output_compression') ) {
			$zlibO_check = 1;
		}
		if ( ini_get('session.use_trans_sid') ) {
			$sid_check = 1;
		}

		if ( $phpver >= '4.0.4pl1' && ( strpos($useragent,'compatible') !== false || strpos($useragent,'Gecko')	!== false ) ) {
			// Check for gzip header or northon internet securities or session.use_trans_sid
			if ( ( $gzip_check || isset( $_SERVER['---------------']) ) && $zlib_check && $gz_check && !$zlibO_check && !$sid_check ) {
				// You cannot specify additional output handlers if
				// zlib.output_compression is activated here
				ob_start( 'ob_gzhandler' );
				return;
			}
		} else if ( $phpver > '4.0' ) {
			if ( $gzip_check ) {
				if ( $zlib_check ) {
					$do_gzip_compress = TRUE;
					ob_start();
					ob_implicit_flush(0);

					header( 'Content-Encoding: gzip' );
					return;
				}
			}
		}
	}
	ob_start();
}

/**
* Perform GZIP
*/
function doGzip() {
	global $do_gzip_compress;
	if ( $do_gzip_compress ) {
		/**
		*Borrowed from php.net!
		*/
		$gzip_contents = ob_get_contents();
		ob_end_clean();

		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);

		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	} else {
		ob_end_flush();
	}
}

/**
* Random password generator
* @return password
*/
function MakePassword($length=8) {
	$salt 		= "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	$makepass	= '';
	mt_srand(10000000*(double)microtime());
	for ($i = 0; $i < $length; $i++)
		$makepass .= $salt[mt_rand(0,58)];
	return $makepass;
}

/**
* Displays a not authorised message
*
* If the user is not logged in then an addition message is displayed.
*/
function NotAuth() {
	global $my;

	echo 'Uppss!!! Sanırım birşeyler yanlış gitti.';
	if ($my->id < 1) {
		echo "<br />" . 'Önce giriş yapın!';
	}
}

/**
* Replaces &amp; with & for xhtml compliance
*
* Needed to handle unicode conflicts due to unicode conflicts
*/
function ampReplace( $text ) {
	$text = str_replace( '&&', '*--*', $text );
	$text = str_replace( '&#', '*-*', $text );
	$text = str_replace( '&amp;', '&', $text );
	$text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
	$text = str_replace( '*-*', '&#', $text );
	$text = str_replace( '*--*', '&&', $text );

	return $text;
}
/**
 * Function to convert array to integer values
 * @param array
 * @param int A default value to assign if $array is not an array
 * @return array
 */
function ArrayToInts( &$array, $default=null ) {
	if (is_array( $array )) {
		foreach( $array as $key => $value ) {
			$array[$key] = (int) $value;
		}
	} else {
		if (is_null( $default )) {
			$array = array();
			return array(); // Kept for backwards compatibility
		} else {
			$array = array( (int) $default );
			return array( $default ); // Kept for backwards compatibility
		}
	}
}

/**
* Arraydan stringe: soner ekledi
* 
* @param mixed $array
* @param mixed $default
*/
function ArrayToStrings( &$array, $default=null ) {
	if (is_array( $array )) {
		foreach( $array as $key => $value ) {
			$array[$key] = (string) $value;
		}
	} else {
		if (is_null( $default )) {
			$array = array();
			return array(); // Kept for backwards compatibility
		} else {
			$array = array( (string) $default );
			return array( $default ); // Kept for backwards compatibility
		}
	}
}

/*
* Function to handle an array of integers
* Added 1.0.11
*/
function GetArrayInts( $name, $type=NULL ) {
	if ( $type == NULL ) {
		$type = $_POST;
	}

	$array = getParam( $type, $name, array(0) );

	ArrayToInts( $array );

	if (!is_array( $array )) {
		$array = array(0);
	}

	return $array;
}

/**
 * Provides a secure hash based on a seed
 * @param string Seed string
 * @return string
 */
function mezunHash( $seed ) {
	return md5( SECRETWORD . md5( $seed ) );
}

/**
 * Format a backtrace error
 */
function mezunBackTrace() {
	if (function_exists( 'debug_backtrace' )) {
		echo '<div align="left">';
		foreach( debug_backtrace() as $back) {
			if (@$back['file']) {
				echo '<br />' . str_replace( ABSPATH, '', $back['file'] ) . ':' . $back['line'];
			}
		}
		echo '</div>';
	}
}

function spoofCheck( $header=NULL, $alt=NULL , $method = 'post') {
	switch(strtolower($method)) {
		case "get":
			$validate 	= getParam( $_GET, spoofValue($alt), 0 );
			break;
		case "request":
			$validate 	= getParam( $_REQUEST, spoofValue($alt), 0 );
			break;
		case "post":
		default:
			$validate 	= getParam( $_POST, spoofValue($alt), 0 );
			break;
	}

	// probably a spoofing attack
	if (!$validate) {
		header( 'HTTP/1.0 403 Forbidden' );
		ErrorAlert( 'Yetkiniz yok' );
		return;
	}

	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:
	if (!isset( $_SERVER['HTTP_USER_AGENT'] )) {
		header( 'HTTP/1.0 403 Forbidden' );
		ErrorAlert( 'Yetkiniz yok' );
		return;
	}

	// Make sure the form was indeed POST'ed:
	//  (requires your html form to use: action="post")
	if (!$_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header( 'HTTP/1.0 403 Forbidden' );
		ErrorAlert( 'Yetkiniz yok' );
		return;
	}

	if ($header) {
	// Attempt to defend against header injections:
		$badStrings = array(
			'Content-Type:',
			'MIME-Version:',
			'Content-Transfer-Encoding:',
			'bcc:',
			'cc:'
		);

		// Loop through each POST'ed value and test if it contains
		// one of the $badStrings:
		_spoofCheck( $_POST, $badStrings );
	}
}

function _spoofCheck( $array, $badStrings ) {
	// Loop through each $array value and test if it contains
	// one of the $badStrings
	foreach( $array as $v ) {
		if (is_array( $v )) {
			_spoofCheck( $v, $badStrings );
		} else {
			foreach ( $badStrings as $v2 ) {
				if ( stripos( $v, $v2 ) !== false ) {
					header( 'HTTP/1.0 403 Forbidden' );
					ErrorAlert( 'Yetkiniz yok' );
					exit(); // ErrorAlert dies anyway, double check just to make sure
				}
			}
		}
	}
}

/**
 * Method to determine a hash for anti-spoofing variable names
 *
 * @return	string	Hashed var name
 * @static
 */
function spoofValue($alt=NULL) {
	global $mainframe, $my, $db;

	if ($alt) {
		if ( $alt == 1 ) {
			$random		= date( 'Ymd' );
		} else {
			$random		= $alt . date( 'Ymd' );
		}
	} else {
		$random		= date( 'dmY' );
	}
	// the prefix ensures that the hash is non-numeric
	// otherwise it will be intercepted by globals.php
	$validate 	= 'j' . mezunHash( $db . $random . $my->id );

	return $validate;
}

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
	include(ABSPATH.'/includes/global/footer.php');
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

function mezunHashPassword($password) {
	// Salt and hash the password
	$salt    = MakePassword(16);
	$crypt    = md5($password.$salt);
	$hash    = $crypt.':'.$salt;

	return $hash;
}

