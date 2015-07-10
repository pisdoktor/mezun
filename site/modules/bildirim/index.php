<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

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
	$bolum[] = mosHTML::makeOption('', 'Hata ile karşılaştığınız bölümü seçin');
	$bolum[] = mosHTML::makeOption('profil', 'Profil');
	$bolum[] = mosHTML::makeOption('arkadas', 'Arkadaşlarım');
	$bolum[] = mosHTML::makeOption('online', 'Online Üyeler');
	$bolum[] = mosHTML::makeOption('mesaj', 'Mesajlar');
	$bolum[] = mosHTML::makeOption('istek', 'İstekler');
	$bolum[] = mosHTML::makeOption('group', 'Gruplar');
	$bolum[] = mosHTML::makeOption('arama', 'Üye Arama');
	$bolum[] = mosHTML::makeOption('forum', 'Forum');
	$bolum[] = mosHTML::makeOption('diger', 'Diğer');
	
	$list = mosHTML::selectList($bolum, 'hatabolumu', '', 'value', 'text');
	
	systemMessage::Form($my, $list);
}

function sendSysMsg() {
	global $dbase, $my;
	
	$row = new Mesajlar($dbase);
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
