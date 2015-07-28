<?php
/**
* Sistemin 2. ana dosyası.
* İçerisinden geçmeyen tüm dosyalar hata mesajı verecek ;)
* Bu dosya üzerinden sadece Ajax istekleri iletilecek...
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
* Genel döngü değerlerini alalım
*/
$option = strval(strtolower(getParam($_REQUEST, 'option')));
$bolum = strval(strtolower(getParam($_REQUEST, 'bolum')));
$task = strval(strtolower(getParam($_REQUEST, 'task')));

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

switch($option) {

	case 'login2':
	Login2();
	break;
}

function Login2() {
	global $mainframe;
	
	$mainframe->login2();
}

/**
* Sayfa açılışı...
*/
ob_start();

ob_end_clean();

/**
* Kullanıcı üye ise...
*/
if ($my->id) {
	//Kullanıcı yönetici ise... 
	if ($my->access_type == 'admin') {    
		require_once(ABSPATH.'/admin/includes/functions.php');    
			
		loadAdminModule();
	
	//Kullanıcı normal üye ise...		
	} else {        
		loadSiteModule();
	}
}

