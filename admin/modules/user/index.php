<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
$search = getParam($_REQUEST, 'search');

include(dirname(__FILE__). '/html.php');

mimport('tables.users');

switch($task) {
	default:
	getKullaniciList($search);
	break;
	
	case 'new':
	editKullanici(0);
	break;
	
	case 'edit':
	editKullanici($id);
	break;
	
	case 'save':
	saveKullanici();
	break;
	
	case 'cancel':
	cancelKullanici();
	break;
	
	case 'delete':
	delKullanici($id);
	break;
	
	case 'block':
	blockUser($id, 0);
	break;
	
	case 'unblock':
	blockUser($id, 1);
	break;
}

function blockUser($id, $what) {
	global $dbase;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunUsers($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		break;
	}
	
	$row->activated = $what;
	
	$row->store();
	
	Redirect( 'index.php?option=admin&bolum=user' );
}

function delKullanici($id) {
	global $dbase;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunUsers($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	$dbase->setQuery("DELETE FROM #__users WHERE id=".$dbase->Quote($row->id));
	$dbase->query();
	
	Redirect( 'index.php?option=admin&bolum=user' );
}

function saveKullanici() {
	 global $dbase;
	
	$row = new mezunUsers( $dbase );
	
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
			$pwd             = MakePassword();

			$salt = MakePassword(16);
			$crypt = md5($pwd.$salt);
			$row->password = $crypt.':'.$salt;
		} else {
			$pwd = trim( $row->password );

			$salt = MakePassword(16);
			$crypt = md5($pwd.$salt);
			$row->password = $crypt.':'.$salt;
		}
		$row->registerDate     = date( 'Y-m-d H:i:s' );
	} else {
		$original = new mezunUsers( $dbase );
		$original->load( (int)$row->id );

		// existing user stuff
		if ($row->password == '') {
			// password set to null if empty
			$row->password = null;
		} else {
			$row->password = trim($row->password);
			$salt = MakePassword(16);
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
	
	Redirect('index.php?option=admin&bolum=user', 'Kullanici kaydedildi');
	
}

function cancelKullanici() {
	global $dbase;
	
	$row = new mezunUsers( $dbase );
	$row->bind( $_POST );
	Redirect( 'index.php?option=admin&bolum=user');
}

function getKullaniciList($search) {
	 global $dbase, $limit, $limitstart, $my;
	 
	 $where = array();
	 if ($search) {
		 $search = mezunStripslashes($search);
		 $where[] = "k.username LIKE '%" . $dbase->getEscaped( trim( strtolower( $search ) ) ) . "%'";
	 } 
	 
	 $query = "SELECT k.*, s.name as sehir, b.name as brans FROM #__users AS k"
	 . "\n LEFT JOIN #__sehirler AS s ON s.id=k.sehir"
	 . "\n LEFT JOIN #__branslar AS b ON b.id=k.brans"
	 . ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	 . "\n ORDER BY k.id"
	 ;
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	$pageNav = new mezunPagenation( $total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	KullaniciHTML::getKullaniciList($list, $pageNav, $search);
}

function editKullanici($id) {
	global $dbase;
	
	$row = new mezunUsers($dbase);
	$row->load($id);

	KullaniciHTML::editKullanici($row);
}