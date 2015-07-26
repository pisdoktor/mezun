<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunGruplar extends mezunTable {
	
	var $id = null;
	
	var $name = null;
	
	var $aciklama = null;
	
	var $image = null;
	
	var $status = null; //0: herkese açık, 1: davet ile giriş
	
	var $creator = null;
	
	var $creationdate = null;
	
	function mezunGruplar(&$db) {
		$this->mezunTable( '#__groups', 'id', $db );
	}
	
	function creatorName() {
		$this->_db->setQuery("SELECT name FROM #__users WHERE id=".$this->_db->Quote($this->creator));
		$name = $this->_db->loadResult();
		
	return '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$this->creator).'">'.$name.'</a>';
	}
	
	public function canDeleteGroup() {
		global $my;

		if ($this->creator == $my->id || $my->id == 1) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function canEditGroup() {
		global $my;

		if ($this->canDeleteGroup()) {
			return true;
		} else if ($this->isGroupAdmin()) {
			return true;
		} else if ($my->id == 1) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function canViewGroup() {
		global $my;
		
		if ($this->creator == $my->id) {
			return true;
		} else if ($this->isGroupMember()) {
			return true;
		} else if ($this->isGroupAdmin()) {
			return true;
		} else if ($my->id == 1) {
			return true;
		} else {
			return false;
		}

	}
	
	public function canJoinGroup() {
		global $my;
		
		if ($this->status == 0 || $my->id == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isGroupMember() {
		global $my;
		
		$this->_db->setQuery("SELECT userid FROM #__groups_members WHERE groupid=".$this->_db->Quote($this->id)." AND userid=".$this->_db->Quote($my->id));
		
		if ($this->_db->loadResult() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isGroupAdmin() {
		global $my;
		
		$this->_db->setQuery("SELECT 1 FROM #__groups_members WHERE groupid=".$this->_db->Quote($this->id)." AND userid=".$this->_db->Quote($my->id)." AND isadmin=1");
		
		if ($this->_db->loadResult() > 0) {
			return true;
		} else {
			return false;
		}
	}
}

class GroupMessages extends mezunTable {
	
	var $id = null;
	
	var $groupid = null;
	
	var $text = null;
	
	var $tarih = null;
	
	var $userid = null;
	
	function GroupMessages(&$db) {
		$this->mezunTable('#__groups_messages', 'id', $db);
	}
}

class GroupMembers extends mezunTable {
	
	var $userid = null;
	
	var $groupid = null;
	
	var $isadmin = 0;
	
	var $joindate = null;
	
	function GroupMembers(&$db) {
		$this->mezunTable('#__group_members', 'userid', $db);
	}
}