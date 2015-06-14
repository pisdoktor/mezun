<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Istekler extends DBTable {
	
	var $id = null;
	
	var $gid     = null;
	
	var $aid   = null;
	
	var $tarih = null;
	
	var $durum  = null;
	
	function Istekler( &$db ) {
		$this->DBTable( '#__istekler', 'id', $db );
	}
	
	function totalWaiting() {
		global $my;
		$query = "SELECT COUNT(*) FROM #__istekler WHERE aid=".$this->_db->Quote($my->id)." AND durum=0";
		$this->_db->setQuery($query);
		
		if ($this->_db->loadResult()) {
			echo "(".$this->_db->loadResult().")";
		} 
	}
	
	function checkDurum($gid, $aid, $durum) {
		$where[] = "(gid=".$gid." AND aid=".$aid.")";
		$where[] = "(gid=".$aid." AND aid=".$gid.")";
		
		$query = "SELECT COUNT(*) FROM #__istekler"
		. "\n WHERE (" . implode( ' OR ', $where ).")"
		. "\n AND durum=".$durum;
		;
		$this->_db->setQuery($query);
		
		if ($this->_db->loadResult()) {
			return true;
		} else {
			return false;
		}
	}
	
	function changeDurum($oid, $type) {
		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}
		
		$this->load($this->$k);
			
		switch($type) {
			case 'onay':
			$this->set('durum', 1);
			break;
			
			case 'red':
			$this->set('durum', -1);
			break;
		}		
		$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		
		if( !$ret ) {
			$this->_error = strtolower(get_class( $this ))."::kayıt başarısız <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
		
	}
}