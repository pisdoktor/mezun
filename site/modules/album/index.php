<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.album.helper');
mimport('tables.albums');

$id = intval(getParam($_REQUEST, 'id'));


switch($task) {
	default:
	getMyAlbums();
	break;
	
	case 'user':
	getUserAlbums($id);
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
	deleteImage($id);
	break;
	
	case 'editimage':
	editImage($id);
	break;
	
	case 'saveimage':
	saveImage();
	break;
}

function getUserAlbums($id) {
	global $dbase;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$rows = mezunAlbumHelper::getUserAlbums($id);
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	AlbumHTML::getMyAlbums($list, $pageNav);
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
	
	mimport('helpers.modules.arkadas.helper');
	
	if (!$id) {
		NotAuth();
		return;
	}
	$can = array();
	
	$album = new mezunAlbums($dbase);
	$album->load($id);
	
	if (!$album->id) {
		NotAuth();
		return;
	}
	
	$can['View'] = false;
	
	if ($album->userid == $my->id) {
		$can['Edit'] = true;
		$can['View'] = true;
	} else {
		$can['Edit'] = false;
		
		if ($album->status == 0) {
			$can['View'] = true;
		}
	
		if (($album->status == 1) && mezunArkadasHelper::checkArkadaslik($album->userid)) {
			$can['View'] = true;
		} 
	
		if ($album->status == 2) {
			$can['View'] = false;
		}
	}
	
	$dbase->setQuery("SELECT * FROM #__album_images WHERE albumid=".$dbase->Quote($album->id)." ORDER BY addeddate DESC, id DESC");
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
		
		if ($row->userid != $my->id) {
			NotAuth();
			return;
		}
		
		$new = false;
	} else {
		$row = new mezunAlbums($dbase);
		$new = true;
	}
	
	$gr = array();
	$gr[] = mezunHTML::makeOption('0', 'Herkese Açık');
	$gr[] = mezunHTML::makeOption('1', 'Arkadaşlarım');
	$gr[] = mezunHTML::makeOption('2', 'Gizli');
	
	$list['status'] = mezunHTML::radioList($gr, 'status', '', 'value', 'text', $row->status);
	
	AlbumHTML::editAlbum($row, $new, $list);
}

function saveAlbum() {
	global $dbase, $my;
	
	$new = getParam($_REQUEST, 'new');
	$id = intval(getParam($_REQUEST, 'id'));
	
	$row = new mezunAlbums($dbase);
	$row->bind($_POST);
	
	if ($new) {
	$row->creationdate = date('Y-m-d H:i:s');
	$row->userid = $my->id;    
	}
	
	$row->store();
	
	Redirect('index.php?option=site&bolum=album&task=view&id='.$row->id);
}

function deleteAlbum($id) {
	global $dbase, $my;
	
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
		@unlink(ABSPATH.'/album/'.$image->image);
		@unlink(ABSPATH.'/album/thumb/'.$image->image);
	}
	
	//albümü silelim
	$dbase->setQuery("DELETE FROM #__albums WHERE id=".$row->id);
	$dbase->query();
	
	Redirect('index.php?option=site&bolum=album');
	
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
	
		if ($album->status != 2) {
		$akistext = '<a href="index.php?option=site&bolum=album&task=view&id='.$album->id.'">'.$album->name.'</a> albümüne yeni resim yükledi';
		mezunGlobalHelper::AkisTracker($akistext);
		}
		echo '{"status":"success"}';
		return;
	}
}

echo '{"status":"error"}';
return;	
}

function deleteImage($id) {
	global $dbase, $my;
	
	mimport('helpers.file.file');
		
	if (!$id) {
		NotAuth();
		return;
	}
		
	$row = new mezunAlbumImages($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	if ($row->userid != $my->id) {
		NotAuth();
		return;
	}
	
	$albumid = $row->albumid;
			
	$image = ABSPATH.'/images/album/'.$row->image;
	$thumb = ABSPATH.'/images/album/thumb/'.$row->image;
			
	if (@unlink($image) && @unlink($thumb)) {
		$dbase->setQuery("DELETE FROM #__album_images WHERE id=".$dbase->Quote($row->id));
		$dbase->query();
	}

Redirect('index.php?option=site&bolum=album&task=view&id='.$albumid);
}

function editImage($id) {
	global $dbase, $my;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunAlbumImages($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	if ($row->userid != $my->id) {
		NotAuth();
		return;
	}
	
	//resim 800x600 den büyükse önce resize yapalım
	mimport('helpers.image.helper');
	
	$image = ABSPATH.'/images/album/'.$row->image;
	list($w, $h) = getimagesize($image);
	
	if ($w > 800 || $h > 600) {
		
		if ($w > 800) {
		$oran = round(800 / $w, 2);
		
		$newwidth = 800;
		$newheight = round($oran * $h);
		
	} else if ($h > 600) {
		$oran = round(600 / $h, 2);
		
		$newheight = $h;
		$newwidth = round($oran * $w);
		
	} else {
		$newheight = $h;
		$newwidth = $w;
	}
	
	mezunImageHelper::resize($image, $image, $newwidth, $newheight, $w, $h);
		
	}
	
	$album = new mezunAlbums($dbase);
	$album->load($row->albumid);
	
	AlbumHTML::editImage($row, $album);
	
}

function saveImage() {
	global $dbase;
	/**
	$x = getParam($_REQUEST, 'x');
	$y = getParam($_REQUEST, 'y');
	$w = getParam($_REQUEST, 'w');
	$h = getParam($_REQUEST, 'h');
	$scale = getParam($_REQUEST, 'scale');
	$angle = getParam($_REQUEST, 'angle');
	$image = getParam($_REQUEST, 'image');
	
	mimport('helpers.image.helper');
	
	$src = ABSPATH.'/images/album/'.$image;
	
	list($iw, $ih) = getimagesize($src);
	
	if ($angle) {
	mezunImageHelper::rotate($src, $src, $angle);
	}
	
	$neww = floor($iw * $scale);
	$newh = floor($ih * $scale);
	
	mezunImageHelper::resize($src, $src, $neww, $newh, $iw, $ih);
	
	mezunImageHelper::crop($src, 0, 0, $x, $y, $w, $h, $iw, $ih);
	*/
	$id = intval(getParam($_REQUEST, 'id'));
	$title = getParam($_REQUEST, 'title');
	
	
	$row = new mezunAlbumImages($dbase);
	$row->load($id);
	
	$row->title = $title;
	
	$row->store();
	
	Redirect('index.php?option=site&bolum=album&task=view&id='.$row->albumid);
	
}