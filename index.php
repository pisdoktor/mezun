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
$code = strval(mosGetParam($_REQUEST, 'code'));

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
	
	case 'forgot':
	resendPassword();
	break;
	
	case 'activate':
	activeUser($code);
	break;
}

function activeUser($code) {
	global $dbase;
	
	$code = htmlspecialchars($code);
	$code = stripslashes($code);
	$code = trim($code);
	
	$query = "SELECT id FROM #__users WHERE activation=".$dbase->Quote($code);
	$dbase->setQuery($query);
	
	$exist = $dbase->loadResult();
	
	if ($exist) {
		$row = new Users($dbase);
		$row->activateUser($exist);
		mosRedirect('index.php', 'Kullanıcı başarıyla aktive edildi. Artık giriş yapabilirsiniz!');
	} else {
		mosRedirect('index.php', 'Kullanıcı yok yada daha önce aktive edilmiş!');
	}
}

function resendPassword() {
	global $dbase;
	
	$mail = mosGetParam($_REQUEST, 'email');
	
	$mail = htmlspecialchars($mail);
	$mail = stripslashes($mail);
	$mail = trim($mail);
	
	$query = "SELECT id FROM #__users WHERE email=".$dbase->Quote($mail);
	$dbase->setQuery($query);
	
	$exist = $dbase->loadResult();
	
	if ($exist) {		
		$user = new Users($dbase);
		$user->load($exist);
		
		$passwd = mosMakePassword(8);
		$salt = mosMakePassword(16);
		$crypt = md5($passwd.$salt);
		
		$password = $crypt.':'.$salt;
		
	   $query    = 'UPDATE #__users SET password = '.$dbase->Quote($password)
	   . ' WHERE id = '.(int)$user->id;
	   $dbase->setQuery($query);
	   $dbase->query();
	   
	   $body = "İsteğiniz üzerine parolanız sıfırlandı.\n\n";
	   $body.= "----------------------------------------\n";
	   $body.= "Yeni Parolanız: ".$passwd."\n";
	   $body.= "----------------------------------------\n";
	   $body.= "Site: ".SITEURL."\n";
	   $body.= "Lütfen siteye giriş yaparak parolanızı tekrar değiştiriniz.\n";
	   
	   mosMail(MAILFROM, MAILFROMNAME, $mail, 'Yeni Parola', $body);
	   mosRedirect('index.php', 'Yeni parola verdiğiniz e-posta adresine gönderildi!'.$passwd);
	} else {
		mosRedirect('index.php', 'Verilen e-postaya ait bir kullanıcı yok!');
	}
}

function registerUser() {
	global $dbase;
	
	$row = new Users($dbase);
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	
	$salt = mosMakePassword(16);
	$crypt = md5($row->password.$salt);
		
	$row->password = $crypt.':'.$salt;
	
	$row->registerDate = date('Y-m-d H:i:s');
	$row->activated = 0;
	$row->activation = $row->createCode();
	
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$alink = '<a href="'.SITEURL.'/index.php?option=active&code='.$row->activation.'">Aktive Et</a>';
	
	$body = "Üyelik talebiniz başarıyla kaydedildi.\n\n";
	$body.= "----------------------------------------\n";
	$body.= "Aktivasyon Linki: ".$alink."\n";
	$body.= "Aktivasyon Kodu: ".$row->activation."\n";
	$body.= "----------------------------------------\n";
	$body.= "Site: ".SITEURL."\n";
	$body.= "Siteye giriş yapmak için yukarıdaki aktivasyon linkine tıklayınız.\n";
	   
	mosMail(MAILFROM, MAILFROMNAME, $row->email, 'Yeni Üyelik', $body, 1);
		
	mosRedirect( 'index.php', 'Kayıt talebiniz kaydedildi. E-posta adresinize aktivasyon linki gönderildi. Lütfen önce aktivasyonu gerçekleştiriniz!' );

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