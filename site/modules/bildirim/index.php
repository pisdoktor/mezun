<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.bildirim.helper');

switch($task) {
	default:
	sysMessage();
	break;
	
	case 'send':
	sendSysMsg();
	break;
}

function sysMessage() {
	global $my;
	
	$bolum = array();
	$bolum[] = mezunHTML::makeOption('', 'Hata ile karşılaştığınız bölümü seçin');
	$bolum[] = mezunHTML::makeOption('profil', 'Profil');
	$bolum[] = mezunHTML::makeOption('arkadas', 'Arkadaşlarım');
	$bolum[] = mezunHTML::makeOption('online', 'Online Üyeler');
	$bolum[] = mezunHTML::makeOption('mesaj', 'Mesajlar');
	$bolum[] = mezunHTML::makeOption('istek', 'İstekler');
	$bolum[] = mezunHTML::makeOption('group', 'Gruplar');
	$bolum[] = mezunHTML::makeOption('arama', 'Üye Arama');
	$bolum[] = mezunHTML::makeOption('forum', 'Forum');
	$bolum[] = mezunHTML::makeOption('diger', 'Diğer');
	
	$list = mezunHTML::selectList($bolum, 'hatabolumu', '', 'value', 'text');
	
	systemMessage::Form($my, $list);
}

function sendSysMsg() {
	global $dbase, $my;
	
	$row = new mezunMesajlar($dbase);
	$row->bind($_POST);
	
	$hatabolumu = getParam($_POST, 'hatabolumu');
	
	$row->id = $row->createID();
	$row->gid = $my->id;
	$row->aid = '-1';
	$row->baslik = $row->cryptionText($row->baslik);
	
	$text = $hatabolumu.' bölümüyle ilgili bir mesaj gönderilmiş.';
	$text.= "\n Gönderilen mesaj aşağıdadır;";
	$text.= "\n-----------------------------------------------------\n";
	$text.= $row->text;
	$text.= "\n-----------------------------------------------------\n";
	
	$row->text = $row->cryptionText($text);
	$row->tarih = date('Y-m-d H:i:s');
	$row->okunma = 0;
	
	$row->store();
	
	Redirect('index.php', 'Mesajınız sistem yöneticisine gönderildi');
}
