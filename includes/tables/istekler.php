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
			echo $this->_db->loadResult();
		} 
	}
	
	function checkDurum($gid, $aid, $durum) {
		$where[] = "(gid=".$gid." AND aid=".$aid.")";
		$where[] = "(gid=".$aid." AND aid=".$gid.")";
		
		$query = "SELECT id FROM #__istekler"
		. "\n WHERE (" . implode( ' OR ', $where ).")"
		. "\n AND durum=".$durum;
		;
		$this->_db->setQuery($query);
		
		if ($this->_db->loadResult() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function getMyFriends() {
		global $my;
		
		$query = "SELECT aid FROM #__istekler WHERE durum=1 AND gid=".$this->_db->Quote($my->id);
		$this->_db->setQuery($query);
		$rows1 = $this->_db->loadResultArray();
		
		$query = "SELECT gid FROM #__istekler WHERE durum=1 AND aid=".$this->_db->Quote($my->id);
		$this->_db->setQuery($query);
		$rows2 = $this->_db->loadResultArray();
		
		$rows = array_merge($rows1, $rows2);
		
		return $rows;
	}
}