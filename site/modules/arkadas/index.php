<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$id = intval(getParam($_REQUEST, 'id')); 
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	getArkadasList();
	break;
}

/**
* Arkadaş listesi gösterimi
* sql sorgularında iyileştirme yapılmalı!
*/
function getArkadasList() {
	global $dbase, $my, $limit, $limitstart;
	
	//benim gönderip karşı tarafın kabul ettiği arkadaşlıklar
	$query = "SELECT i.aid FROM #__istekler AS i "
	. "\n WHERE i.gid=".$dbase->Quote($my->id)." AND i.durum=1"
	;
	$dbase->setQuery($query);
	$rows1 = $dbase->loadResultArray();
	
	//karşı tarafın gönderdiği ve benim kabul ettiğim arkadaşlıklar
	$query = "SELECT i.gid FROM #__istekler AS i"
	. "\n WHERE i.aid=".$dbase->Quote($my->id)." AND i.durum=1"
	;
	$dbase->setQuery($query);
	$rows2 = $dbase->loadResultArray();
	
	$rows = array_merge($rows1, $rows2);
	
	$total = count($rows);
	
	$query = "SELECT u.*, s.name as sehiradi, ss.name as dogumyeriadi, b.name as bransadi FROM #__users AS u"
	. "\n LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.dogumyeri"
	. "\n LEFT JOIN #__branslar AS b ON b.id=u.brans"
	. "\n WHERE u.id IN (".implode(', ', $rows).")";
	$dbase->setQuery($query, $limitstart, $limit);
	$list = $dbase->loadObjectList();
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	Arkadas::getList($list, $pageNav);	
}