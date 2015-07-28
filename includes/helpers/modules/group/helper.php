<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunGroupHelper {
	/**
	* Kullanıcının gruplarını getirir
	* 
	*/
	static function getMyGroups($rand=false) {
		global $dbase, $my;
		
		$query = "SELECT g.* FROM #__groups AS g "
		. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id "
		. "\n WHERE m.userid=".$dbase->Quote($my->id)
		. ($rand ? "\n ORDER BY RAND()":"")
		;
		$dbase->setQuery($query);
		$rows = $dbase->loadObjectList();
		
		return $rows;		
	}
	/**
	* Belirtilen kullanıcıya ait grupları getirir
	* 
	* @param mixed $userid : grupları istenilen kullanıcı id
	* @return array
	*/
	static function getUserGroups($userid) {
		global $dbase;
		
		$query = "SELECT g.* FROM #__groups AS g "
		. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id "
		. "\n WHERE m.userid=".$dbase->Quote($userid);
		$dbase->setQuery($query);
		$rows = $dbase->loadObjectList();
		
		return $rows;		
	}
	/**
	* Tüm grupları getirir
	* 
	*/
	static function getAllGroups() {
		global $dbase;
		
		$query = "SELECT g.*, COUNT(m.userid) as totaluser FROM #__groups AS g"
		. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id GROUP BY g.id";
		$dbase->setQuery($query);
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
	/**
	* Bir gruba ait kullanıcıları getirir
	* 
	* @param mixed $groupid : Kullanıcıları istenilen grup id
	* @param mixed $count : false: kullanıcılar true: kullanıcı sayısı
	* @return array
	*/
	static function getGroupUsers($groupid, $count=false) {
		global $dbase;
		
		$dbase->setQuery("SELECT userid FROM #__groups_members WHERE groupid=".$dbase->Quote($groupid));
		$rows = $dbase->loadResultArray();
		
		if ($count) {
			return count($rows); 
		} else {
			return $rows;
		}
		
	}
	/**
	* İstenilen grubun son mesajlarını getirir
	* 
	* @param mixed $groupid : grubun id değeri
	* @param mixed $count : kaç mesajın gösterileceği
	* @return array
	*/
	static function getGroupMessages($groupid, $count) {
		global $dbase;
		
		$query = "SELECT m.*, u.name as gonderen FROM #__groups_messages AS m "
		. "\n LEFT JOIN #__users AS u ON u.id=m.userid"
		. "\n WHERE m.groupid=".$dbase->Quote($groupid)." ORDER BY m.tarih DESC LIMIT ".$count;
		$dbase->setQuery($query);
	
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
	
	static function getGroupAdmins($groupid) {
		global $dbase;
		
		$query = "SELECT m.userid, u.name as adminUser FROM #__groups_members AS m "
		. "\n LEFT JOIN #__users AS u ON u.id=m.userid "
		. "\n WHERE m.groupid=".$dbase->Quote($groupid)." AND m.isadmin=1 ORDER BY m.joindate DESC";
		$dbase->setQuery($query);
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
}