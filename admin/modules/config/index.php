<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	getConfigList();
	break;
	   
	case 'save':
	saveConfig();
	break;
	
	case 'cancel':
	cancelConfig();
	break;
}

function saveConfig() {
	 global $dbase;
	 
	 //Tüm ayaları silelim
	 $dbase->setQuery("DELETE FROM #__config");
	 $dbase->query();
	 
	 $config['_ISO'] = getParam($_POST, '_ISO');
	 $config['SITEHEAD'] = getParam($_POST, 'SITEHEAD');
	 $config['META_DESC'] = getParam($_POST, 'META_DESC');
	 $config['META_KEYS'] = getParam($_POST, 'META_KEYS');
	 $config['ADMINTEMPLATE'] = getParam($_POST, 'ADMINTEMPLATE');
	 $config['SITETEMPLATE'] = getParam($_POST, 'SITETEMPLATE');
	 $config['OFFSET'] = getParam($_POST, 'OFFSET');
	 $config['DEBUGMODE'] = getParam($_POST, 'DEBUGMODE');
	 $config['SECRETWORD'] = getParam($_POST, 'SECRETWORD');
	 $config['ERROR_REPORT'] = getParam($_POST, 'ERROR_REPORT');
	 $config['SESSION_TYPE'] = getParam($_POST, 'SESSION_TYPE');
	 $config['USER_ACTIVATION'] = getParam($_POST, 'USER_ACTIVATION');
	 $config['MAILER'] = getParam($_POST, 'MAILER');
	 $config['MAILFROMNAME'] = getParam($_POST, 'MAILFROMNAME');
	 $config['MAILFROM'] = getParam($_POST, 'MAILFROM');
	 $config['SENDMAIL'] = getParam($_POST, 'SENDMAIL');
	 $config['smtpauth'] = getParam($_POST, 'smtpauth');
	 $config['smtpuser'] = getParam($_POST, 'smtpuser');
	 $config['smtppass'] = getParam($_POST, 'smtppass');
	 $config['smtphost'] = getParam($_POST, 'smtphost');
	 $config['smtpport'] = getParam($_POST, 'smtpport');
	 $config['smtpsecure'] = getParam($_POST, 'smtpsecure');
	 $config['GZIPCOMP'] = getParam($_POST, 'GZIPCOMP');
	 $config['STATS'] = getParam($_POST, 'STATS');
	 $config['COUNTSTATS'] = getParam($_POST, 'COUNTSTATS');
	 $config['countChildPosts'] = getParam($_POST, 'countChildPosts');
	 $config['hotTopicPosts'] = getParam($_POST, 'hotTopicPosts');
	 $config['hotTopicVeryPosts'] = getParam($_POST, 'hotTopicVeryPosts');
	 $config['todayMod'] = getParam($_POST, 'todayMod');
	 $config['TIMEFORMAT'] = getParam($_POST, 'TIMEFORMAT');
	 $config['compactTopicPagesEnable'] = getParam($_POST, 'compactTopicPagesEnable');
	 $config['compactTopicPagesContiguous'] = getParam($_POST, 'compactTopicPagesContiguous');
	 $config['latestPostCount'] = getParam($_POST, 'latestPostCount');
	 $config['SEF'] = getParam($_POST, 'SEF');
	 
	 
	 foreach ($config as $k=>$v) {
		 $dbase->setQuery("INSERT INTO #__config "
		 . "\n (name, var) "
		 . "\n VALUES " 
		 . "\n (".$dbase->Quote($k).", ".$dbase->Quote($v).")");
		 $dbase->query();
	 }
	
	Redirect('index.php?option=admin', 'Site ayarları kaydedildi');
	
}

function cancelConfig() {
	Redirect( 'index.php?option=admin');
}

function getConfigList() {
	 global $dbase;
	 
	 $dbase->setQuery("SELECT * FROM #__config");
	 $rows = $dbase->loadObjectList();
	 
	 $config = array();
	 foreach ($rows as $row) {
		 $config[$row->name] = $row->var;
	 }
	 
	 $lists['offset'] = mezunHTML::integerSelectList(-12, 14, 1, 'OFFSET', 'id="OFFSET" size="1"', $config['OFFSET'], true, '');
	 
	 $lists['debugmode'] = mezunHTML::yesnoRadioList('DEBUGMODE', 'id="DEBUGMODE"', $config['DEBUGMODE']);
	 $lists['errorreport'] = mezunHTML::yesnoRadioList('ERROR_REPORT', 'id="ERROR_REPORT"', $config['ERROR_REPORT']);
	 
	 $stype = array();
	 $stype[] = mezunHTML::makeOption('0', 'Yüksek Seviye');
	 $stype[] = mezunHTML::makeOption('1', 'Orta Seviye');
	 $stype[] = mezunHTML::makeOption('2', 'Düşük Seviye');

	 $lists['sessiontype'] = mezunHTML::selectList($stype, 'SESSION_TYPE', 'id="SESSION_TYPE" size="1"', 'value', 'text', $config['SESSION_TYPE']);	 
	 
	 $lists['useractivation'] = mezunHTML::yesnoRadioList('USER_ACTIVATION', 'id="USER_ACTIVATION"', $config['USER_ACTIVATION']);
	 
	 $smailer = array();
	 $smailer[] = mezunHTML::makeOption('sendmail', 'SendMail');
	 $smailer[] = mezunHTML::makeOption('smtp', 'SMTP');
	 $smailer[] = mezunHTML::makeOption('mail', 'PHP Mail Fonksiyonu');
	 
	 $lists['mailer'] = mezunHTML::selectList($smailer, 'MAILER', 'id="MAILER" size="1"', 'value', 'text', $config['MAILER']);
	 
	 $lists['gzipcomp'] = mezunHTML::yesnoRadioList('GZIPCOMP', 'id="GZIPCOMP"', $config['GZIPCOMP']);
	 
	 $lists['stats'] = mezunHTML::yesnoRadioList('STATS', 'id="STATS"', $config['STATS']);
	 
	 $lists['countstats'] = mezunHTML::yesnoRadioList('COUNTSTATS', 'id="COUNTSTATS"', $config['COUNTSTATS']);
	 
	 
	 $stoday = array();
	 $stoday[] = mezunHTML::makeOption('0', 'Kapalı');
	 $stoday[] = mezunHTML::makeOption('1', 'Sadece Bugün');
	 $stoday[] = mezunHTML::makeOption('2', 'Dün ve Bugün');
	 
	 $lists['todaymod'] = mezunHTML::selectList($stoday, 'todayMod', 'id="todayMod" size="1"', 'value', 'text', $config['todayMod']);
	 
	 
	 $lists['sef'] = mezunHTML::yesnoRadioList('SEF', 'id="SEF"', $config['SEF']);
	 
	 //Forum seçenekleri
	 $lists['countchild'] = mezunHTML::yesnoRadioList('countChildPosts', 'id="countChildPosts"', $config['countChildPosts']);
	 
	 $lists['compacttopic'] = mezunHTML::yesnoRadioList('compactTopicPagesEnable', 'id="compactTopicPagesEnable"', $config['compactTopicPagesEnable']);
	 
	
	ConfigHTML::getConfigList($config, $lists);
}
