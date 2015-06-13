<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$cid = mosGetParam($_REQUEST, 'cid');
$id = intval(mosGetParam($_REQUEST, 'id'));
$limit = intval(mosGetParam($_REQUEST, 'limit', 10));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));
$search = mosGetParam($_REQUEST, 'search');

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	getKullaniciList($search);
	break;
	
	case 'add':
	editKullanici(0);
	break;
	
	case 'edit':
	editKullanici(intval(($cid[0])));
	break;
	
	case 'editx':
	editKullanici($id);
	break;
	
	case 'save':
	saveKullanici();
	break;
	
	case 'cancel':
	cancelKullanici();
	break;
	
	case 'delete':
	delKullanici($cid);
	break;
}

function delKullanici(&$cid) {
	global $dbase;

	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('Silmek için listeden bir duyuru seçin'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );
	$query = "DELETE FROM #__users"
	. "\n WHERE ( $cids )"
	;
	$dbase->setQuery( $query );
	if ( !$dbase->query() ) {
		echo "<script> alert('".$dbase->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	mosRedirect( 'index.php?option=yonetim&bolum=kullanici', 'Seçili kullanıcı(lar) silindi' );
}

function saveKullanici() {
	 global $dbase;
	
	$row = new Users( $dbase );
	
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$isNew     = !$row->id;
	$pwd     = '';

	// MD5 hash convert passwords
	if ($isNew) {
		// new user stuff
		if ($row->password == '') {
			$pwd             = mosMakePassword();

			$salt = mosMakePassword(16);
			$crypt = md5($pwd.$salt);
			$row->password = $crypt.':'.$salt;
		} else {
			$pwd = trim( $row->password );

			$salt = mosMakePassword(16);
			$crypt = md5($pwd.$salt);
			$row->password = $crypt.':'.$salt;
		}
		$row->registerDate     = date( 'Y-m-d H:i:s' );
	} else {
		$original = new admUsers( $dbase );
		$original->load( (int)$row->id );

		// existing user stuff
		if ($row->password == '') {
			// password set to null if empty
			$row->password = null;
		} else {
			$row->password = trim($row->password);
			$salt = mosMakePassword(16);
			$crypt = md5($row->password.$salt);
			$row->password = $crypt.':'.$salt;
		}
	}
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	mosRedirect('index.php?option=admin&bolum=user', 'Kullanici kaydedildi');
	
}

function cancelKullanici() {
	global $dbase;
	
	$row = new Users( $dbase );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( 'index.php?option=admin&bolum=user');
}

function getKullaniciList($search) {
	 global $dbase, $limit, $limitstart, $my;
	 
	 $where = array();
	 if ($search) {
		 $search = mosStripslashes($search);
		 $where[] = "k.username LIKE '%" . $dbase->getEscaped( trim( strtolower( $search ) ) ) . "%'";
	 }
	 
	 
	 $query = "SELECT COUNT(k.id) FROM #__users AS k"
	 . ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	 ;
	 
	 
	 $dbase->setQuery($query);
	 $total = $dbase->loadResult();
	 
	 $pageNav = new pageNav( $total, $limitstart, $limit);
	 
	 $query = "SELECT k.*, s.name as sehir, b.name as brans FROM #__users AS k"
	 . "\n LEFT JOIN #__sehirler AS s ON s.id=k.sehir"
	 . "\n LEFT JOIN #__branslar AS b ON b.id=k.brans"
	 . ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	 . "\n ORDER BY k.id"
	 ;
	
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	KullaniciHTML::getKullaniciList($rows, $pageNav, $search);
}

function editKullanici($cid) {
	global $dbase, $my;
	
	$row = new Users($dbase);
	$row->load($cid);
	
	KullaniciHTML::editKullanici($row);
}