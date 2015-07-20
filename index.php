<?php
/**
* Sistemin ana dosyası.
* İçerisinden geçmeyen tüm dosyalar hata mesajı verecek ;)
*/
define('ERISIM', 1);

/**
* Global ve Config Dosyalarını önce alalım
*/
require( dirname( __FILE__ ) . '/global.php' );
require( dirname( __FILE__ ) . '/config.php' );

/**
* Gerekli olan Base dosyalarını alalım
*/
require_once(dirname( __FILE__ ) . '/includes/base.php' );
/**
* Gerekli olan fonksiyonları alalım
*/
require_once(dirname( __FILE__ ) . '/includes/functions.php');

/**
* Sef desteği sağlayalım
* NOT: Henüz tam olarak aktif değil. Üzerinde çalışılması gerekiyor :( 
*/
require(dirname(__FILE__).'/includes/sef.php');

/**
* Genel döngü değerlerini alalım
*/
$option = strval(strtolower(getParam($_REQUEST, 'option')));
$bolum = strval(strtolower(getParam($_REQUEST, 'bolum')));
$task = strval(strtolower(getParam($_REQUEST, 'task')));
$return = strval( getParam( $_REQUEST, 'return', NULL ) );
$code = strval(getParam($_REQUEST, 'code'));
$mosmsg = getParam($_REQUEST, 'mosmsg');

/**
* Çatıyı oluşturalım, oturumu başlatalım
* 
* @var mezunMainFrame
*/
$mainframe = new mezunMainFrame( $dbase );
$mainframe->initSession();
/**
* mezunUsers tablosundan kullanıcının bilgilerini çekelim
* 
* @var mezunUsers
*/
$my = $mainframe->getUser();
/**
* Site ziyaretçilerinin bilgilerini alalım ve istatistik oluşturalım
* 
* @var mezunStatsHelper
*/
$stats = new mezunStatsHelper();
$stats->tracker();

/**
* $option ile döngüleri alalım
*/
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

/**
* Kullanıcı aktivasyon fonksiyonu
* 
* @param mixed $code kullanıcı tarafından girilen kod
*/
function activeUser($code) {
	global $dbase;
	
	spoofCheck(NULL,1);
	
	$code = htmlspecialchars($code);
	$code = stripslashes($code);
	$code = trim($code);
	
	$query = "SELECT id FROM #__users WHERE activation=".$dbase->Quote($code);
	$dbase->setQuery($query);
	
	$exist = $dbase->loadResult();
	
	if ($exist) {
		$row = new Users($dbase);
		$row->activateUser($exist);
		Redirect('index.php', 'Kullanıcı başarıyla aktive edildi. Artık giriş yapabilirsiniz!');
	} else {
		Redirect('index.php', 'Kullanıcı yok yada daha önce aktive edilmiş!');
	}
}

/**
* Kullanıcı parola sıfırlama fonksiyonu
*/
function resendPassword() {
	global $dbase;
	
	spoofCheck(NULL,1);
	
	$mail = getParam($_REQUEST, 'email');
	
	$mail = htmlspecialchars($mail);
	$mail = stripslashes($mail);
	$mail = trim($mail);
	
	if (!$mail) {
		Redirect('index.php', 'E-posta adresi girmemişsiniz!');
	}
	
	if (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $mail) == false) {
		Redirect('index.php', 'Geçerli bir e-posta adresi girmelisiniz!');
	}
	
	$query = "SELECT id FROM #__users WHERE email=".$dbase->Quote($mail);
	$dbase->setQuery($query);
	
	$exist = $dbase->loadResult();
	
	if ($exist) {		
		$user = new mezunUsers($dbase);
		$user->load($exist);
		
		$passwd = MakePassword(8);
		$salt = MakePassword(16);
		$crypt = md5($passwd.$salt);
		
		$password = $crypt.':'.$salt;
		
	   $query    = 'UPDATE #__users SET password = '.$dbase->Quote($password)
	   . ' WHERE id = '.(int)$user->id;
	   $dbase->setQuery($query);
	   $dbase->query();
	   
	   $body = "İsteğiniz üzerine parolanız sıfırlandı.\n";
	   $body.= "----------------------------------------\n";
	   $body.= "Yeni Parolanız: ".$passwd."\n";
	   $body.= "----------------------------------------\n";
	   $body.= "Site: ".SITEURL."\n";
	   $body.= "Lütfen siteye giriş yaparak parolanızı tekrar değiştiriniz.\n";
	   
	   mosMail(MAILFROM, MAILFROMNAME, $mail, 'Yeni Parola', $body, 1, '', '', '', MAILFROM, MAILFROMNAME);
	   Redirect('index.php', 'Yeni parola verdiğiniz e-posta adresine gönderildi!');
	} else {
		Redirect('index.php', 'Verilen e-postaya ait bir kullanıcı yok!');
	}
}
/**
* Kullanıcı kayıt fonksiyonu
*/
function registerUser() {
	global $dbase;
	
	$row = new mezunUsers($dbase);
	
	spoofCheck(NULL,1);
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if ($row->byili >= $row->myili) {
		echo "<script> alert('Başlangıç yılı mezuniyet yılına eşit veya büyük olamaz!'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$salt = MakePassword(16);
	$crypt = md5($row->password.$salt);
		
	$row->password = $crypt.':'.$salt;
	
	$row->registerDate = date('Y-m-d H:i:s');
	
	if (USER_ACTIVATION) {
	
	$row->activated = 0;
	$row->activation = $row->createCode();
	
	$alink = '<a href="'.SITEURL.'/index.php?option=active&code='.$row->activation.'">Aktive Et</a>';
	
	$body = "Üyelik talebiniz başarıyla kaydedildi.\n";
	$body.= "----------------------------------------\n";
	$body.= "Aktivasyon Linki: ".$alink."\n";
	$body.= "Aktivasyon Kodu: ".$row->activation."\n";
	$body.= "----------------------------------------\n";
	$body.= "Site: ".SITEURL."\n";
	$body.= "Siteye giriş yapmak için yukarıdaki aktivasyon linkine tıklayınız.\n";
	   
	mosMail(MAILFROM, MAILFROMNAME, $row->email, 'Yeni Üyelik', $body, 1, '', '', '', MAILFROM, MAILFROMNAME);
	
	$msg = 'Kayıt talebiniz kaydedildi. E-posta adresinize aktivasyon linki gönderildi. Lütfen önce aktivasyonu gerçekleştiriniz!';
	
	} else {
		$row->activated = 1;
		$row->activation = '';
		$msg = 'Üyeliğiniz başarıyla gerçekleştirildi. Siteye giriş yapabilirsiniz!';
	}
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}		
	
	Redirect( 'index.php', $msg );

}

/**
* Kullanıcı giriş fonksiyonu
*/
function Login() {
	global $mainframe, $return;
	
	$mainframe->login();

if ( $return ) {
		if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
			Redirect( $return );
		} else {
			Redirect( 'index.php?option=cookiecheck&return=' . urlencode( $return ) );
		}
	} else {
		if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
			Redirect( 'index.php' );
		} else {
			Redirect( 'index.php?option=cookiecheck&return=' . urlencode( 'index.php' ) );
		}
	}
}
/**
* Kullanıcı çıkış fonksiyonu
*/
function Logout() {
	global $mainframe, $return;
	
	$mainframe->logout();

	if ( $return ) {
		Redirect( $return );
	} else {
		Redirect( 'index.php' );
	}
}

/**
* Cookie kontrol fonksiyonu
*/
function CookieCheck() {
	global $mainframe, $return;
	if (isset( $_COOKIE[$mainframe->sessionCookieName()] )) {
		Redirect( $return );
	} else {
		ErrorAlert( 'Çerezler açık değil!' );
	}
}


/**
* Sayfa açılışı...
*/
ob_start();

ob_end_clean();

header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

/**
* Gzip desteği
*/
initGzip();

/**
* Kullanıcı eğer üye değilse muhtemelen ya anasayfadadır ya da kayıt sayfasında
*/
if (!$my->id) {
	// Kullanıcı kayıt sayfasında ise...
	if ($option == 'register') {
		$reg = new mezunUsers($dbase);
		include_once(ABSPATH. '/site/templates/'.SITETEMPLATE.'/register.php');    
	//Kullanıcı anasayfada giriş ekranını görüyor...
	} else {
		include_once(ABSPATH. '/site/templates/'.SITETEMPLATE.'/login.php');    
	}
}
/**
* Kullanıcı eğer üye ise yetki düzeyine bakalım
*/
else {
	//Kullanıcı ($id=1 olan) eğer yönetici ise...
	if ($my->access_type == 'admin') {    
		require_once(ABSPATH.'/admin/includes/functions.php');    
			
		include_once(ABSPATH.'/admin/templates/'.ADMINTEMPLATE.'/index.php');
	//Kullanıcı normal bir üye ise...		
	} else {		
		include_once(ABSPATH.'/site/templates/'.SITETEMPLATE.'/index.php');
	}
}

/**
* Site hata gösterimi açık ise...
*/
if (DEBUGMODE) {
	
	echo '<div id="sql">';
	echo '<strong>'. $dbase->_ticker . ' sorgu çalıştırıldı</strong>';
	echo '<pre>';
	 foreach ($dbase->_log as $k=>$sql) {
		 echo $k+1 . "\n" . $sql . '<hr />';
	}
	echo '</pre></div>';
}

/**
* Gzip çalıştır...	
*/
doGzip();