<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

$id = intval(getParam($_REQUEST, 'id')); 
$groupid = intval(getParam($_REQUEST, 'groupid')); 
$userid = intval(getParam($_REQUEST, 'userid')); 


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
	
	case 'setmod':
	changeModX($groupid, $userid, 1);
	break;
	
	case 'getmod':
	changeModX($groupid, $userid, 0);
	break;
}
/**
* AJAX ile grup moderatörü ekleme / çıkarma
* 
* @param mixed $groupid : Düzenlenecek grup id
* @param mixed $userid : İşlem yapılacak kullanıcı id
* @param mixed $status : ne yapılacak?
*/
function changeModX($groupid, $userid, $status) {
	global $dbase;
	
	$errors         = '';      // array to hold validation errors
	$data           = array();      // array to pass back data
	
	if (!$groupid) {
		$errors = 'Group ID değeri yok';
	}
	
	if (!$userid) {
		$errors = 'User ID değeri yok';
	}
	
	$row = new mezunGruplar($dbase);
	$row->load($groupid);
	
	if ($row->creator == $userid) {
		$errors = 'Kendini çıkaramazsın';
	}
	
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['message']  = $errors;
	} else {
	
	
	$dbase->setQuery("UPDATE #__groups_members SET isadmin=".$dbase->Quote($status)." WHERE groupid=".$dbase->Quote($groupid)." AND userid=".$dbase->Quote($userid));
	$dbase->query();
	
	$data['success'] = true;
	$data['url'] = $status ? 'index2.php?option=site&bolum=group&task=getmod&groupid='.$groupid.'&userid='.$userid : 'index2.php?option=site&bolum=group&task=setmod&groupid='.$groupid.'&userid='.$userid;
	
	$data['message'] = $status ? 'Görevi Al':'Moderatör Yap';
	
	$data['userid'] = $userid;
	
	$data['style'] = $status ? 'Grup Moderatörü':'';
	
	}
	
			// return all our data to an AJAX call
	echo json_encode($data);
}
/**
* Gruba yeni bir üye ekleme fonksiyonu
* 
*/
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
/**
* Belirtilen grubu silme fonksiyonu
* 
* @param mixed $id : silinecek grubun id değeri
*/
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

/**
* Belirtilen grubun üyelerini gösteren fonksiyon
* 
* @param mixed $id
*/
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
	
	//grubun üyelerini alalım
	$memberlist = mezunGroupHelper::getGroupUsers($group->id);
	
	//grubun adminlerini alalım
	$adminU = mezunGroupHelper::getGroupAdmins($group->id);
	foreach ($adminU as $admin) {
		$list[] = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$admin->userid).'">'.$admin->adminUser.'</a>';
	}
	$group->admins = implode('<br />', $list);
	
	//toplam üye sayısı
	$group->totalmember = count($memberlist);
	
	//sayfalandırma yapalım
	$limit = intval(getParam($_REQUEST, 'limit', 10));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	$pageNav = new mezunPagenation($group->totalmember, $limitstart, $limit);
	
	//grubun üyelerini alalım
	$query = "SELECT m.userid, m.isadmin, m.joindate, u.name, g.creator AS creatorid FROM #__groups_members AS m "
	. "\n LEFT JOIN #__users AS u ON u.id=m.userid "
	. "\n LEFT JOIN #__groups AS g ON g.id=m.groupid "
	. "\n WHERE m.groupid=".$dbase->Quote($id)
	. "\n ORDER BY m.joindate ASC, m.isadmin DESC, u.name DESC";
	
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
	
	//gruba eklemek için arkadaş listesini alalım
	mimport('helpers.modules.arkadas.helper');
	$friendlist = mezunArkadasHelper::getMyFriends();
	
	//gruba ekli olmayan arkadaşaları ayıralım
	$others = array_diff($friendlist, $memberlist);
	
	//arkadaşların bilgilerini listeyelim
	$other = implode(',', $others);
	
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

/**
* Yeni grubun veya varolan grubun bilgilerini kaydeden fonksiyon
*/
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
	
	if ($new) {
		$akistext = '<a href="index.php?option=site&bolum=group&task=view&id='.$row->id.'">'.$row->name.'</a> adlı bir grup oluşturdu';
			mezunGlobalHelper::AkisTracker($akistext);
	} else {
		$akistext = '<a href="index.php?option=site&bolum=group&task=view&id='.$row->id.'">'.$row->name.'</a> grubunu düzenledi';
			mezunGlobalHelper::AkisTracker($akistext);
	}
	
	Redirect('index.php?option=site&bolum=group&task=view&id='.$row->id);
}
/**
* Belirtilen gruptan kullanıcının çıkmasını sağlayan fonksiyon
* 
* @param mixed $id
*/
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
		
		$akistext = '<a href="index.php?option=site&bolum=group&task=view&id='.$group->id.'">'.$group->name.'</a> grubundan ayrıldı';
			mezunGlobalHelper::AkisTracker($akistext);
		
		Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Gruptan ayrıldınız!');
		
	}
}
/**
* Belirtilen gruba üyenin katılmasını sağlayan fonksiyon
* 
* @param mixed $id
*/
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
			
			$akistext = '<a href="index.php?option=site&bolum=group&task=view&id='.$group->id.'">'.$group->name.'</a> grubuna katıldı';
			mezunGlobalHelper::AkisTracker($akistext);
			
			Redirect('index.php?option=site&bolum=group&task=view&id='.$group->id, 'Gruba katıldınız!');
		}
	}
}
/**
* Grup ekleme ve düzenleme fonksiyonu
* 
* @param mixed $id : Düzenlenecek grubun id değeri
*/
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
/**
* Gruba mesaj gönderme fonksiyonu
* 
*/
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
/**
* İstenilen grubu gösteren fonksiyon
* 
* @param mixed $id
*/
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
	$row->totalmember = mezunGroupHelper::getGroupUsers($row->id, true);
	
	//grubun adminlerini alalım
	$adminU = mezunGroupHelper::getGroupAdmins($row->id);
	
	foreach ($adminU as $admin) {
		$list[] = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$admin->userid).'">'.$admin->adminUser.'</a>';
	}
	$row->admins = implode('<br />', $list);

	//gruba ait son 10 mesajı alalım
	$msgs = mezunGroupHelper::getGroupMessages($row->id, 10);
	
	
	GroupHTML::viewGroup($row, $msgs);
}
/**
* Tüm grupları getiren fonksiyon
*/
function listGroups() {
	global $dbase, $my;
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$rows = mezunGroupHelper::getAllGroups();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	//html ye gönderelim
	GroupHTML::listGroups($list, $pageNav);	
}

/**
* Kullanıcının gruplarını getiren fonksiyon
*/
function getMyGroups() {
	global $dbase, $my;
	
	$limit = intval(getParam($_REQUEST, 'limit', 20));
	$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));
	
	$rows = mezunGroupHelper::getMyGroups();
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	GroupHTML::getMyGroups($list, $pageNav);
}
