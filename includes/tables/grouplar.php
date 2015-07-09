<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class UserGroups extends DBTable {
	
	var $id = null;
	
	var $name = null;
	
	var $aciklama = null;
	
	var $image = null;
	
	var $status = null; //0: herkese açık, 1: davet ile giriş
	
	var $creator = null;
	
	var $creationdate = null;
	
	function UserGroups(&$db) {
		$this->DBTable( '#__groups', 'id', $db );
	}
	
	function creatorName() {
		$this->_db->setQuery("SELECT name FROM #__users WHERE id=".$this->_db->Quote($this->creator));
		$name = $this->_db->loadResult();
		
		return '<a href="index.php?option=site&bolum=profil&task=show&id='.$this->creator.'">'.$name.'</a>';
	}
	
	function totalMembers() {
		$query = "SELECT COUNT(m.userid) AS totaluser FROM #__groups_members AS m "
		. "\n LEFT JOIN #__groups AS g ON g.id=m.userid"
		. "\n WHERE m.groupid=".$this->_db->Quote($this->id);
		
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	
	function adminMembers() {
		$query = "SELECT m.userid, u.name as adminUser FROM #__groups_members AS m "
		. "\n LEFT JOIN #__users AS u ON u.id=m.userid "
		. "\n WHERE m.groupid=".$this->_db->Quote($this->id)." AND m.isadmin=1 ORDER BY m.joindate DESC";
		$this->_db->setQuery($query);
		
		return $this->_db->loadObjectList();
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

		if ($this->canDeleteGroup() || $this->isGroupAdmin() || $my->id == 1) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function canViewGroup() {
		global $my;
		
		if ($this->creator == $my->id || $this->status == 0 || $my->id == 1) {
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
		
		$this->_db->setQuery("SELECT 1 FROM #__group_members WHERE groupid=".$this->_db->Quote($this->id)." AND userid=".$this->_db->Quote($my->id));
		
		if ($this->_db->loadResult() > 0) {
			return true;
		} else {
			return false;
		}
	}
}

class GroupMessages extends DBTable {
	
	var $id = null;
	
	var $groupid = null;
	
	var $text = null;
	
	var $tarih = null;
	
	var $userid = null;
	
	function GroupMessages(&$db) {
		$this->DBTable('#__groups_messages', 'id', $db);
	}
}

class GroupMembers extends DBTable {
	
	var $userid = null;
	
	var $groupid = null;
	
	var $isadmin = 0;
	
	var $joindate = null;
	
	function GroupMembers(&$db) {
		$this->DBTable('#__group_members', 'userid', $db);
	}
}