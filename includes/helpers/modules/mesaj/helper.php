<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunMesajHelper {
	/**
	* Toplam okunmayan mesaj sayısını gösteren fonksiyon
	* 
	*/
	static function totalUnread() {
		global $dbase, $my;
		$query = "SELECT COUNT(*) FROM #__mesajlar WHERE aid=".$dbase->Quote($my->id)." AND okunma=0 AND asilinme=0";
		$dbase->setQuery($query);
		
		if ($dbase->loadResult()) {
			echo $dbase->loadResult();
		} 
	}
	/**
	* Gelen giden mesaj içeriği şifreleme fonksiyonu
	* 
	* @param mixed $text : şifrelenecek metin
	* @param mixed $cryption : encode veya decode...
	*/
	static function cryptionText($text, $cryption='encode') {
		
		$hash = md5(SECRETWORD);
		
		if ($cryption=='encode') {
			$text = base64_encode($text);
			$text = base64_encode($text.':'.$hash);
		} else {
			$text = base64_decode($text);
			list($text, $hash) = explode(':', $text);
			$text = base64_decode($text);
		}
		
		return $text;
	}
	
}