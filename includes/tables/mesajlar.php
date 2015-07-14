<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunMesajlar extends mezunTable {
	
	var $id     = null;
	
	var $gid  = null;
	
	var $aid = null;
	
	var $baslik = null;
	
	var $text   = null;
	
	var $tarih = null;
	
	var $okunma = null;
	
	var $gsilinme = null;
	
	var $asilinme = null;
	
	function mezunMesajlar( &$db ) {
		$this->mezunTable( '#__mesajlar', 'id', $db );
	}
	
	function createID() {
		return MakePassword(255);
	}
	
	function totalUnread() {
		global $my;
		$query = "SELECT COUNT(*) FROM #__mesajlar WHERE aid=".$this->_db->Quote($my->id)." AND okunma=0 AND asilinme=0";
		$this->_db->setQuery($query);
		
		if ($this->_db->loadResult()) {
			echo $this->_db->loadResult();
		} 
	}
	
	function newMsg() {
		global $my;
		
		$query = "SELECT COUNT(*) FROM #__mesajlar WHERE aid=".$this->_db->Quote($my->id)." AND okunma=0";
		
		$this->_db->setQuery($query);
		
		$new = $this->_db->loadResult();
		
		if ($new) {
			return 'Toplam <span class="badge"><a href="index.php?option=site&bolum=mesaj&task=inbox">'.$new.'</a></span> yeni mesajınız var!';
		} else {
			return '[ Yeni mesajınız yok ]';
		}
	}
	
	function changeMsg($oid=null, $read=1) {
		
		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}
		
		$this->load($this->$k);
		
		$this->set('okunma', $read);

		$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		
		if( !$ret ) {
			$this->_error = strtolower(get_class( $this ))."::kayıt başarısız <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
	
	function cryptionText($text, $cryption='encode') {
		
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