<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	searchForm();
	break;
	
	case 'search':
	searchResults();
	break;
}
/**
* Arama seçeneklerini gösteren fonksiyon
*/
function searchForm() {
	global $dbase, $my;
	
	$user = new Users($dbase);
	
	$type = array();
	$type[] = mosHTML::makeOption('0', 'Herhangi bir eşleşme');
	$type[] = mosHTML::makeOption('1', 'Tam eşleşme');
	
	$list['type'] = mosHTML::selectList($type, 'search_type', '', 'value', 'text');
	
	$cins = array();
	$cins[] = mosHTML::makeOption('', 'Cinsiyet Seçin');
	$cins[] = mosHTML::makeOption('1', 'Erkek');
	$cins[] = mosHTML::makeOption('2', 'Bayan');
	
	$list['cinsiyet'] = mosHTML::selectList($cins, 'cinsiyet', '', 'value', 'text');
	
	Search::Form($list, $user);
}

/**
* Arama sonuçlarının gösterimi
*/
function searchResults() {
	global $dbase, $my;
	
$name = getParam($_REQUEST, 'name');
$username = getParam($_REQUEST, 'username');
$work = getParam($_REQUEST, 'work');
$brans = intval(getParam($_REQUEST, 'brans'));
$unvan = strval(getParam($_REQUEST, 'unvan'));
$byili = intval(getParam($_REQUEST, 'byili'));
$myili = intval(getParam($_REQUEST, 'myili'));
$sehir = intval(getParam($_REQUEST, 'sehir'));
$dogumyeri = intval(getParam($_REQUEST, 'dogumyeri'));
$cinsiyet = intval(getParam($_REQUEST, 'cinsiyet'));
$image = intval(getParam($_REQUEST, 'image'));
$search_type = intval(getParam($_REQUEST, 'search_type'));
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));


	
	$where = array();
	
	if ($name) {
		$where[] = "u.name LIKE '%" . $dbase->getEscaped( trim( $name  ) ) . "%'";
	}
	
	if ($username) {
		$where[] = "u.username LIKE '%" . $dbase->getEscaped( trim( $username ) ) . "%'";
	}
	
	if ($work) {
		$where[] = "u.work LIKE '%" . $dbase->getEscaped( trim( $work ) ) . "%'";
	}
	
	if ($brans) {
		$where[] = "u.brans=".$dbase->Quote($brans);
	}
	
	if ($unvan) {
		$where[] = "u.unvan=".$dbase->Quote($unvan);
	}
	
	if ($byili) {
		$where[] = "u.byili=".$dbase->Quote($byili);
	}
	
	if ($myili) {
		$where[] = "u.myili=".$dbase->Quote($myili);
	}
	
	if ($sehir) {
		$where[] = "u.sehir=".$dbase->Quote($sehir);
	}
	
	if ($dogumyeri) {
		$where[] = "u.dogumyeri=".$dbase->Quote($dogumyeri);
	}
	
	if ($cinsiyet) {
		$where[] = "u.cinsiyet=".$dbase->Quote($cinsiyet);
	}
	
	if ($image) {
	   $where[] = "u.image!=''";
	}
	
	$where2 = $where ? " AND (u.activated=1 AND u.id NOT IN (".$my->id."))" : " WHERE (u.activated=1 AND u.id NOT IN (".$my->id."))";
	
	$stype = $search_type ? 'AND':'OR'; 
	
	$query = "SELECT COUNT(*) FROM #__users AS u"
	. ( count( $where ) ? "\n WHERE (" . implode( ' '.$stype.' ', $where ).")" : "" )
	.$where2 
	;
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new pageNav($total, $limitstart, $limit);
	
	$query = "SELECT u.*, s.name AS sehiradi, ss.name as dogumyeriadi, b.name as bransadi FROM #__users AS u"
	. "\n LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.dogumyeri"
	. "\n LEFT JOIN #__branslar AS b ON b.id=u.brans"
	. ( count( $where ) ? "\n WHERE (" . implode( ' '.$stype.' ', $where ).")" : "" ) 
	.$where2
	;
	
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	Search::Results($rows, $pageNav);
}
