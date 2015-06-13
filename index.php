<?php
//  ;)
define('ERISIM', 1);

require( dirname( __FILE__ ) . '/global.php' );
require( dirname( __FILE__ ) . '/config.php' );

require_once( dirname( __FILE__ ) . '/includes/base.php' );
require_once(dirname( __FILE__ ) . '/includes/functions.php');

$option = strval(strtolower(mosGetParam($_REQUEST, 'option')));
$bolum = strval(strtolower(mosGetParam($_REQUEST, 'bolum')));
$task = strval(strtolower(mosGetParam($_REQUEST, 'task')));
$return = strval( mosGetParam( $_REQUEST, 'return', NULL ) );

$mosmsg = mosGetParam($_REQUEST, 'mosmsg');

$mainframe = new mainFrame( $dbase, $option );
$mainframe->initSession();

$my = $mainframe->getUser();

$mainframe->detect();

switch($option) {

	case 'login':
	Login();
	break;    
	
	case 'logout':
	Logout();
	break;
	
	case 'cookiecheck':
	CookieCheck();
	break;
	
	case 'reguser':
	registerUser();
	break;
}

function registerUser() {
	global $dbase;
	
	$new = new Users($dbase);
	
	
	
	if ( $return ) {
		mosRedirect( $return );
	} else {
		mosRedirect( 'index.php' );
	}
	
}

function Login() {
	global $mainframe;
	
	$mainframe->login();

if ( $return ) {
		if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
			mosRedirect( $return );
		} else {
			mosRedirect( 'index.php?option=cookiecheck&return=' . urlencode( $return ) );
		}
	} else {
		if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
			mosRedirect( 'index.php' );
		} else {
			mosRedirect( 'index.php?option=cookiecheck&return=' . urlencode( 'index.php' ) );
		}
	}
}

function Logout() {
	global $mainframe;
	
	$mainframe->logout();

	if ( $return ) {
		mosRedirect( $return );
	} else {
		mosRedirect( 'index.php' );
	}
}

function CookieCheck() {
	global $mainframe;
	if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
		mosRedirect( $return );
	} else {
		mosErrorAlert( 'Çerezler belirlenmemiş!' );
	}
}


ob_start();

ob_end_clean();

header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

initGzip();

//ziyaretçi
if (!$my->id) {
	if ($option == '') {
		include_once(ABSPATH. '/site/templates/'.SITETEMPLATE.'/login.php');    
	} else {
		$reg = new Users($dbase);
		include_once(ABSPATH. '/site/templates/'.SITETEMPLATE.'/register.php');    
	}
}
//kayıtlı kullanıcı
else {
	//sistem yöneticisi ise 
	if ($my->access_type == 'admin') {    
		require_once(ABSPATH.'/admin/includes/functions.php');    
			
		include_once(ABSPATH.'/admin/templates/'.ADMINTEMPLATE.'/index.php');
			
	} else {		
		include_once(ABSPATH.'/site/templates/'.SITETEMPLATE.'/index.php');
	}
}

if (DEBUGMODE) {
	
	echo '<div id="sql">';
	echo '<strong>'. $dbase->_ticker . ' sorgu çalıştırıldı</strong>';
	echo '<pre>';
	 foreach ($dbase->_log as $k=>$sql) {
		 echo $k+1 . "\n" . $sql . '<hr />';
	}
	echo '</pre></div>';
}
	
doGzip();