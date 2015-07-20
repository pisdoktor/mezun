<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunOnlineHelper {
	/**
	* Kullanıcı online mı değil mi?
	* 
	* @param mixed $userid : kontrol edilecek kullanıcı id
	* @param mixed $showimage : resim olarak mı yoksa sadece text mi
	*/
	static function isOnline($userid, $showimage=true) {
		global $dbase;
		
		$query = "SELECT userid FROM #__sessions WHERE userid=".$dbase->Quote($userid);
		$dbase->setQuery($query);
		$row = $dbase->loadResult();
		
		if ($showimage) {
		if ($row) {
			echo '<span class="onlineStatus">
			<img src="'.SITEURL.'/images/online/online.png" alt="Online" title="Online" /> Online
			</span>';
		} else {
			echo '<span class="onlineStatus">
			<img src="'.SITEURL.'/images/online/offline.png" alt="Offline" title="Offline" /> Offline
			</span>';
		}
		} else {
		if ($row) {
			echo 'Online';
		} else {
			echo 'Offline';
		}    
		}
	}
	/**
	* Kullanıcının online süresini hesaplayan fonksiyon
	* 
	* @param mixed $end : bitiş zamanı
	* @param mixed $start : başlangıç zamanı
	*/
	static function calcOnlineTime($end, $start) {
		
		$difference = $end-$start;

		$second = 1;
		$minute = 60*$second;
		$hour   = 60*$minute;
		$day    = 24*$hour;

		$ans["day"]    = floor($difference/$day);
		$ans["hour"]   = floor(($difference%$day)/$hour);
		$ans["minute"] = floor((($difference%$day)%$hour)/$minute);
		$ans["second"] = floor(((($difference%$day)%$hour)%$minute)/$second);

		$html = '';

		if ($ans["day"]) {
			$html.= $ans["day"]. " gün ";
		}

		if ($ans["hour"]) {
			$html.= $ans["hour"]. " saat ";
		}

		if ($ans["minute"]) {
			$html.= $ans["minute"]. " dakika ";
		}

		if ($ans["second"]) {
			$html.= $ans["second"]. " saniye";
		}

		return $html;
	}
	/**
	* Toplam online olan kullanıcı sayısını gösteren fonksiyon
	* 
	*/
	static function totalOnline() {
		global $dbase, $my;
		
		$query = "SELECT COUNT(*) FROM #__sessions"
		. "\n WHERE userid > 0"
		. "\n AND userid NOT IN (".$dbase->Quote($my->id).")"
		;
		$dbase->setQuery($query);
		
		if ($dbase->loadResult()) {
			echo $dbase->loadResult();
		}
	}
}