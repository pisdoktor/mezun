<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 20));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

mimport('tables.albums');

switch($task) {
	default:
	case 'album':
	getAlbumList();
	break;
	
	case 'editalbum':
	editAlbum($id);
	break;
	
	case 'savealbum':
	saveAlbum();
	break;
	
	case 'deletealbum':
	deleteAlbum($id);
	break;
	
	case 'images':
	getImagesList();
	break;
	
	case 'editimage':
	editImage($id);
	break;
	
	case 'saveimage':
	saveImage();
	break;
	
	case 'deleteimage':
	deleteImage($id);
	break;
	
	case 'recount':
	reCountImages();
	break;
}

function saveAlbum() {
	global $dbase;
	
	$row = new mezunAlbums($dbase);
	$row->bind($_POST);
	$row->store();
	
	Redirect('index.php?option=admin&bolum=album&task=album');
}

function editAlbum($id) {
	global $dbase;
	
	$row = new mezunAlbums($dbase);
	$row->load($id);
	
	$gr = array();
	$gr[] = mezunHTML::makeOption('0', 'Herkese Açık');
	$gr[] = mezunHTML::makeOption('1', 'Arkadaşlarım');
	$gr[] = mezunHTML::makeOption('2', 'Gizli');
	
	$list['status'] = mezunHTML::radioList($gr, 'status', '', 'value', 'text', $row->status);
	
	adminAlbumHTML::editAlbum($row, $list);
}

function reCountImages() {
	global $dbase;
}

function getAlbumList() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT a.*, u.name as creator, COUNT(ai.id) AS totalimages FROM #__albums AS a "
	. "\n LEFT JOIN #__users AS u ON u.id=a.userid"
	. "\n LEFT JOIN #__album_images AS ai ON ai.albumid=a.id"
	. "\n GROUP BY a.id ORDER BY a.creationdate DESC");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	
	adminAlbumHTML::getAlbumList($list, $pageNav);
}

function getImagesList() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT ai.*, u.name as creator, a.name as albumname FROM #__album_images AS ai"
	. "\n LEFT JOIN #__users AS u ON u.id=ai.userid"
	. "\n LEFT JOIN #__albums AS a ON a.id=ai.albumid");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	
	adminAlbumHTML::getImagesList($list, $pageNav);
}
