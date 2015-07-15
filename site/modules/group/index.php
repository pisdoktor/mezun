<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.group.helper');
mimport('tables.gruplar');

switch($task) {
	default:
	case 'all':
	listGroups();
	break;
	//gruplarım	
	case 'my':
	getMyGroups();
	break;
	//seçilen grubu göster
	case 'view':
	viewGroup($id);
	break;
	//yeni grup oluşturma
	case 'new':
	editMyGroup(0);
	break;
	//grup düzenle
	case 'edit':
	editMyGroup($id);
	break;
	//grubu kaydet
	case 'save':
	saveGroup();
	break;
	//grubu silme 
	case 'delete':
	deleteGroup($id);
	break;
	//bir gruba katıl
	case 'join':
	joinGroup($id);
	break;
	//bir gruptan çık
	case 'leave':
	leaveGroup($id);
	break;
	//bir gruba mesaj gönder
	case 'send':
	sendGMessage();
	break;
	//bir gruptaki üyeleri listeler
	case 'showmembers':
	showGroupMembers($id);
	break;
	
	case 'addmember':
	addNewMember();
	break;
}

function addNewMember() {
	global $dbase;
	
	$joindate = date('Y-m-d H:i:s');
	$groupid = intval(getParam($_POST, 'groupid'));
	$userid = intval(getParam($_POST, 'uid'));
	$isadmin = intval(getParam($_POST, 'isadmin', 0));
	
	$dbase->setQuery("INSERT INTO #__groups_members (groupid, userid, joindate, isadmin) VALUES (".$dbase->Quote($groupid).", ".$dbase->Quote($userid).", ".$dbase->Quote($joindate).", ".$dbase->Quote($isadmin).")");
	$dbase->query();
	
	Redirect('index.php?option=site&bolum=group&task=showmembers&id='.$groupid);
}

function deleteGroup($id) {
	global $dbase;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$row = new mezunGruplar($dbase);
	$row->load($id);
	
	if (!$row->canDeleteGroup()) {
		NotAuth();
		return;
	}
	
	//grup resmini varsa silelim
	if ($row->image) {
			@unlink(ABSPATH.'/images/group/'.$row->image);
	}
	
	//mesajları silelim
	$dbase->setQuery("DELETE FROM #__groups_messages WHERE groupid=".$row->id);
	$dbase->query();
	
	//üyeleri silelim
	$dbase->setQuery("DELETE FROM #__groups_members WHERE groupid=".$row->id);
	$dbase->query();
	
	//grubu silelim
	$dbase->setQuery("DELETE FROM #__groups WHERE id=".$row->id);
	$dbase->query();
	
	Redirect('index.php?option=site&bolum=group', 'Grup ve tüm içeriği silindi');
}

function showGroupMembers($id) {
	global $dbase, $my;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$group = new mezunGruplar($dbase);
	$group->load($id);
	
	if (!$group->id) {
		NotAuth();
		return;
	}
	
	//toplam üye sayısını alalım
	$group->totalmember = $group->totalMembers();
	
	
	//grubun adminlerini alalım
	$adminU = $group->adminMembers();
	foreach ($adminU as $admin) {
		$list[] = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$admin->userid).'">'.$admin->adminUser.'</a>';
	}
	$group->admins = implode('<br />', $list);
	
	$limit = intval(getParam($_REQUEST, 'limit', 10));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$dbase->setQuery("SELECT COUNT(userid) FROM #__groups_members WHERE groupid=".$dbase->Quote($id));
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$query = "SELECT m.userid, m.isadmin, m.joindate, u.name, g.creator AS creatorid FROM #__groups_members AS m "
	. "\n LEFT JOIN #__users AS u ON u.id=m.userid "
	. "\n LEFT JOIN #__groups AS g ON g.id=m.groupid "
	. "\n WHERE m.groupid=".$dbase->Quote($id)
	. "\n ORDER BY m.joindate ASC, m.isadmin DESC, u.name DESC";
	
	$dbase->setQuery($query, $limitstart, $limit);
	
	$rows = $dbase->loadObjectList();
	
	//gruba eklemek için arkadaş listesini alalım
	mimport('tables.istekler');
	$friends = new mezunIstekler($dbase);
	$friendlist = $friends->getMyFriends();
	//gruba ekli üyeleri alalım
	$dbase->setQuery("SELECT userid FROM #__groups_members WHERE groupid=".$dbase->Quote($group->id)." AND userid NOT IN (".$my->id.")");
	$memberlist = $dbase->loadResultArray();
	
	//gruba ekli olmayan arkadaşaları ayıralım
	$others = array_diff($friendlist, $memberlist);
	
	//arkadaşların bilgilerini listeyelim
	$other = implode(', ', $others);
	
	if ($other) {
		$dbase->setQuery("SELECT id, name FROM #__users WHERE id IN (".$other.")");
		$musers = $dbase->loadObjectList();
		
		foreach ($musers as $muser) {
			$u[] = mezunHTML::makeOption($muser->id, $muser->name);
		}
		
		$list['invite'] = mezunHTML::selectList($u, 'uid', 'size="10"', 'value', 'text');
	} else {
		$list['invite'] = '';
	}
	
	GroupHTML::showGroupMembers($group, $rows, $pageNav, $list, $other);	
}

function saveGroup() {
	global $dbase, $my;
	
	$row = new mezunGruplar($dbase);
	$row->bind($_POST);
	
	$image = getParam($_FILES, 'image');
	
	if ($row->id) {
		$new = false;
	} else {
		$new = true;
	}
	
	if ($new) {
		$row->creator = $my->id;
		$row->creationdate = date('Y-m-d H:i:s');
	}
	
	$row->store();
	
	//oluşturanı grubun yöneticisi ve üyesi yapalım
	if ($new) {
	$joindate = date('Y-m-d H:i:s');
	$dbase->setQuery("INSERT INTO #__groups_members (userid, groupid, joindate, isadmin) VALUES (".$dbase->Quote($my->id).", ".$dbase->Quote($row->id).", ".$dbase->Quote($joindate).", 1)");
	$dbase->query();
	}
	
	//grup resmi işlemleri
	if ($image['name']) {
		mimport('helpers.image.helper');
		
		$maxWidth = 55;
		$maxHeight = 55;

		//eğer varsa önce eski resmi silelim
		if (!$new) {
			$oldimage = new mezunGruplar($dbase);
			$oldimage->load($row->id);
			if ($oldimage->image) {
				$src = ABSPATH.'/images/group/'.$oldimage->image;
				mezunImageHelper::deleteImage($src);
			}
		}
		
		mezunImageHelper::check($image['name']);
		
		//dosya adını değiştirelim
		$newname = mezunImageHelper::changeName($image['name']);
		//hedef dosya
		$dest = ABSPATH.'/images/group/';
		$targetfile= $dest.$newname;
		
		//upload image
		mimport('helpers.file.file');
		mezunFile::upload($image['tmp_name'], $targetfile);
	
		$query = "UPDATE #__groups SET image=".$dbase->Quote($newname)
		. "\n WHERE id=".$dbase->Quote($row->id);
		$dbase->setQuery($query);
		$dbase->query();	
		
		list($imgwidth, $imgheight) = getimagesize($targetfile);
	
	if ($imgwidth > $maxWidth) {
		$oran = floor($maxWidth / $imgwidth);
		
		$newwidth = $maxWidth;
		$newheight = $oran * $imgheight;
		
	} else if ($imgheight > $maxHeight) {
		$oran = floor($maxHeight / $imgheight);
		
		$newheight = $maxHeight;
		$newwidth = $oran * $imgwidth;
		
	} else {
		$newheight = $imgheight;
		$newwidth = $imgwidth;
	}
		
	mezunImageHelper::resize($targetfile, 0, 0, 0, 0, $newwidth, $newheight, $imgwidth, $imgheight);	
	
	}
	
	Redirect('index.php?option=site&bolum=group&task=view&id='.$row->id);
}

function leaveGroup($id) {
	global $dbase, $my;
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$group = new mezunGruplar($dbase);
	$group->load($id);
	
	if (!$group->id) {
		NotAuth();
		return;
	}
	
	if ($group->creator == $my->id) {
		Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Gruptan ayrılmazsınız! Grubun kurucusu sizsiniz ve grubu silmeden gruptan çıkamazsınız!');
	}
	
	if (!$group->isGroupMember()) {
		Redirect('index.php?option=site&bolum=group&task=view&id='.$id, 'Zaten bu gruba üye değilsiniz!');
	} else {
		$dbase->setQuery("DELETE FROM #__groups_members WHERE userid=".$my->id." AND groupid=".$group->id."");
		$dbase->query();
		
		Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Gruptan ayrıldınız!');
		
	}
}

function joinGroup($id) {
	global $dbase, $my;
	
	$id = intval(getParam($_REQUEST, 'id'));
	
	if (!$id) {
		NotAuth();
		return;
	}
	
	$group = new mezunGruplar($dbase);
	$group->load($id);
	
	if (!$group->id) {
		NotAuth();
		return;
	}
	
	//grup kapalı bir grupsa giriş yapma
	if ($group->status && !$group->canJoinGroup()) {
		NotAuth();
		return;
	} else {
		if ($group->isGroupMember()) {
			Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Zaten bu gruba üyesiniz!');
		} else {
			$joindate = date('Y-m-d H:i:s');
			$dbase->setQuery("INSERT INTO #__groups_members (userid, groupid, joindate) VALUES (".$dbase->Quote($my->id).", ".$dbase->Quote($id).", ".$dbase->Quote($joindate).")");
			$dbase->query();
			
			Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Gruba katıldınız!');
		}
	}
}

function editMyGroup($id) {
	global $dbase;
	
	
	$row = new mezunGruplar($dbase);
	$row->load($id);
	
	if ($row->id && !$row->canEditGroup()) {
		NotAuth();
		return;
	}
	
	$s = array();
	$s[] = mezunHTML::makeOption('1', 'Herkese Kapalı');
	$s[] = mezunHTML::makeOption('0', 'Herkese Açık');
	
	$list['status'] = mezunHTML::radioList($s, 'status', 'required', 'value', 'text', $row->status);
		
	GroupHTML::editMyGroup($row, $list); 
}

function sendGMessage() {
	global $dbase, $my;
	
	$errors         = array();      // array to hold validation errors
	$data           = array();      // array to pass back data
	
	if (empty($_POST['text']))
		$errors['text'] = 'Mesaj girmemişsiniz.';
	
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['message']  = $errors;
	} else {
		
		$row = new GroupMessages($dbase);
		$row->text = strval(getParam($_POST, 'text'));
		$row->tarih = date('Y-m-d H:i:s');
		$row->userid = $my->id;
		$row->groupid = intval(getParam($_POST, 'groupid'));
		
		$row->store();
		
		//gruba ait son 10 mesajı alalım
		$query = "SELECT m.*, u.name as gonderen FROM #__groups_messages AS m "
		. "\n LEFT JOIN #__users AS u ON u.id=m.userid"
		. "\n WHERE m.groupid=".$dbase->Quote($row->groupid)." ORDER BY m.tarih DESC LIMIT 10";
		$dbase->setQuery($query);
	
		$msgs = $dbase->loadObjectList();
		
		$veri = '';
		foreach ($msgs as $msg) {
				$veri .= '<div class="col-sm-12">';
				$veri .= '<div class="form-group">';
					
				$veri .= '<div class="row">';
				$veri .= '<div class="col-sm-12">';
				$veri .= '<small>Gönderen: '.$msg->gonderen.'</small>'; 
				$veri .= ' <small>Tarih: '.mezunGlobalHelper::timeformat($msg->tarih, true, true).'</small>';
				$veri .= '</div></div>';
					
				$veri .= '<div class="row">';
				$veri .= '<div class="col-sm-12">';
				$veri .= $msg->text;
				$veri .= '</div></div></div></div>';
		}    

		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['message'] = 'Success!';
	}

	// return all our data to an AJAX call
	echo json_encode($veri);
}

function viewGroup($id) {
	global $dbase;
	
	//grup bilgilerini alalım
	$row = new mezunGruplar($dbase);
	$row->load($id);
	
	if (!$row->id) {
		NotAuth();
		return;
	}
	
	//toplam üye sayısını alalım
	$row->totalmember = $row->totalMembers();
	
	
	//grubun adminlerini alalım
	$adminU = $row->adminMembers();
	foreach ($adminU as $admin) {
		$list[] = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$admin->userid).'">'.$admin->adminUser.'</a>';
	}
	$row->admins = implode('<br />', $list);

	
	//gruba ait son 10 mesajı alalım
	$query = "SELECT m.*, u.name as gonderen FROM #__groups_messages AS m "
	. "\n LEFT JOIN #__users AS u ON u.id=m.userid"
	. "\n WHERE m.groupid=".$dbase->Quote($row->id)." ORDER BY m.tarih DESC LIMIT 10";
	$dbase->setQuery($query);
	
	$msgs = $dbase->loadObjectList();	
	
	GroupHTML::viewGroup($row, $msgs);
}

function listGroups() {
	global $dbase, $my;
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	//tüm grupları alalım
	$query = "SELECT COUNT(*) FROM #__groups";
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$query = "SELECT g.*, COUNT(m.userid) as totaluser FROM #__groups AS g"
	. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id GROUP BY g.id";
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	//html ye gönderelim
	GroupHTML::listGroups($rows, $pageNav);	
}

function getMyGroups() {
	global $dbase, $my;
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	//üye olduğu grupları alalım
	$query = "SELECT COUNT(g.id) FROM #__groups AS g "
	. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id "
	. "\n WHERE m.userid=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	$total = $dbase->loadResult();
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$query = "SELECT g.* FROM #__groups AS g "
	. "\n LEFT JOIN #__groups_members AS m ON m.groupid=g.id "
	. "\n WHERE m.userid=".$dbase->Quote($my->id);
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
	foreach ($rows as $row) {
		$dbase->setQuery("SELECT COUNT(userid) FROM #__groups_members WHERE groupid=".$row->id);
		
		$row->totaluser = $dbase->loadResult();
	}
	
	GroupHTML::getMyGroups($rows, $pageNav);
}
