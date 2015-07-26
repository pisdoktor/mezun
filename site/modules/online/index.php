<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php'); 

mimport('helpers.modules.online.helper');

switch($task) {
	default:
	getOnline();
	break;
}

/**
* Online üyeleri veritabanından alalım
*/
function getOnline() {
	global $dbase, $my, $limitstart, $limit;
	
	$query = "SELECT s.userid, s.time, u.name, u.nowvisit, ss.name AS sehir FROM #__sessions AS s"
	. "\n LEFT JOIN #__users AS u ON u.id=s.userid"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.sehir"
	. "\n WHERE s.userid > 0 "
	. "\n AND s.userid !=".$dbase->Quote($my->id)
	. "\n ORDER BY s.time DESC"
	;
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	mezunOnlineHTML::showOnlineUsers($list, $pageNav);
}
