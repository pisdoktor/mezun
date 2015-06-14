<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Mesajlar extends DBTable {
	
	var $id     = null;
	
	var $gid  = null;
	
	var $aid = null;
	
	var $baslik = null;
	
	var $text   = null;
	
	var $tarih = null;
	
	var $okunma = null;
	
	var $gsilinme = null;
	
	var $asilinme = null;
	
	function Mesajlar( &$db ) {
		$this->DBTable( '#__mesajlar', 'id', $db );
	}
	
	function createID() {
		return mosMakePassword(255);
	}
	
	function newMsg() {
		global $my;
		
		$query = "SELECT COUNT(*) FROM #__mesajlar WHERE aid=".$this->_db->Quote($my->id)." AND okunma=0";
		
		$this->_db->setQuery($query);
		
		$new = $this->_db->loadResult();
		
		if ($new) {
			return 'Toplam [ <span id="newmsg"><a href="index.php?option=site&bolum=mesaj&task=inbox">'.$new.'</a></span> ] yeni mesajınız var!';
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
}