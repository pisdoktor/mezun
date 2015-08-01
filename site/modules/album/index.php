<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.album.helper');
mimport('tables.albums');

switch($task) {
	default:
	getMyAlbums();
	break;
	
	case 'view':
	viewAlbum($id);
	break;
	
	case 'new':
	editAlbum(0);
	break;
	
	case 'edit':
	editAlbum($id);
	break;
	
	case 'save':
	saveAlbum();
	break;
	
	case 'delete':
	deleteAlbum($id);
	break;
	
	case 'upload':
	uploadImage($id);
	break;
	
	case 'deleteimage':
	deleteImage();
	break;
	
	case 'editimage':
	editImage();
	break;
}

function getMyAlbums() {
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$rows = mezunAlbumHelper::getMyAlbums();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	AlbumHTML::getMyAlbums($list, $pageNav);
}

function viewAlbum($id) {
	global $dbase, $my;
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	if (!$id) {
		NotAuth();
		return;
	}
	$can = array();
	
	$album = new mezunAlbums($dbase);
	$album->load($id);
	
	if ($album->userid == $my->id) {
		$can['Edit'] = true;
	} else {
		$can['Edit'] = false;
	}
	
	$dbase->setQuery("SELECT * FROM #__album_images WHERE albumid=".$dbase->Quote($album->id));
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$album->total = $total;
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	AlbumHTML::viewAlbum($album, $can, $list, $pageNav);
	
}

function editAlbum($id) {
	global $dbase, $my;
	
	$new = false;
	if ($id) {
		$row = new mezunAlbums($dbase);
		$row->load($id);
		
		if (!$row->id) {
			NotAuth();
			return;
		}
		
		$new = false;
	} else {
		$row = new mezunAlbums($dbase);
		$new = true;
	}
	
	AlbumHTML::editAlbum($row, $new);
}

function saveAlbum() {
	global $dbase, $my;
	
	
	$row = new mezunAlbums($dbase);
	$row->bind($_POST);
	$row->store();
	
	Redirect('index.php?option=site&bolum=album&task=view&id='.$row->id);
}

function deleteAlbum($id) {
	global $dbase, $my;
	
	mimport('helpers.file.file');
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunAlbums($dbase);
	$row->load($id);
	
	if ($row->userid != $my->id) {
		NotAuth();
		return;
	}
	//albümdeki resimleri silelim
	$dbase->setQuery("SELECT image FROM #__album_images WHERE albumid=".$row->id);
	$images = $dbase->loadObjectList();
	
	foreach ($images as $image) {
		mezunFile::delete(ABSPATH.'/album/'.$image);
		mezunFile::delete(ABSPATH.'/album/thumb/'.$image);
	}
	
	//albümü silelim
	$dbase->setQuery("DELETE FROM #__albums WHERE id=".$row->id);
	$dbase->query();
	
}

function uploadImage($id) {
	global $dbase, $my;
	
	mimport('helpers.image.helper');
	mimport('helpers.file.file');
	
	$row = new mezunAlbumImages($dbase);
	
	$dest = ABSPATH.'/images/album/';
	$thumb = ABSPATH.'/images/album/thumb/';

	if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

	$extension = mezunImageHelper::getExt($_FILES['image']['name']);

	if(!mezunImageHelper::check($_FILES['image']['name'])){
		echo '{"status":"error"}';
		return;
	}
	
	$newname = mezunImageHelper::changeName($_FILES['image']['name'], 50);

	if(mezunFile::upload($_FILES['image']['tmp_name'], $dest.$newname)){
		$row->image = $newname;
		$row->userid = $my->id;
		$row->addeddate = date('Y-m-d H:i:s');
		$row->albumid = $id;
		
		$row->store();
		list($w, $h) = getimagesize($dest.$newname);
		
		mezunImageHelper::resize($dest.$newname, $thumb.$newname, 100, 100, $w, $h);
		
		$album = new mezunAlbums($dbase);
		$album->load($id);
	
		$akistext = '<a href="index.php?option=site&bolum=album&task=view&id='.$album->id.'">'.$album->name.'</a> albümüne yeni resim yükledi';
		mezunGlobalHelper::AkisTracker($akistext);
		
		echo '{"status":"success"}';
		return;
	}
}

echo '{"status":"error"}';
return;	
}

function deleteImage() {
	
	
}

function editImage() {
	
}
