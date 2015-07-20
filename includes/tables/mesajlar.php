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