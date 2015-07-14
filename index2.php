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
$mainframe = new mezunMainFrame( $dbase, $option );
$mainframe->initSession();
/**
* mezunUsers tablosundan kullanıcının bilgilerini çekelim
* 
* @var mezunUsers
*/
$my = $mainframe->getUser();

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

