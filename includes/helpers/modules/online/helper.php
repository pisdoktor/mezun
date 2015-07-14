<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunOnlineHelper {
	
	function __construct() {
		
	}
	
	
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
	
	//Online süresi hesaplama
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
}