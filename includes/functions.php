<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

function loadSiteModule() {
	global $option, $bolum, $task;
	global $id, $cid;
	global $limit, $limitstart;
	global $mainframe, $my, $mosmsg;
	
	switch($option) {
	default:
	MainPage();
	break;
	
	case 'site':
	if ($bolum) {
		if (file_exists(ABSPATH. '/site/modules/'.$bolum.'/index.php')) {
			include_once(ABSPATH. '/site/modules/'.$bolum.'/index.php');
		} else {
			Redirect('index.php', 'Module:'.$bolum.' bulunamadı!');
		}
	} else {
		Redirect('index.php');
	}
	break;
	
	case 'admin':
	convertAdmin();
	break;
	}
	
} 

function convertAdmin() {
	global $mainframe, $dbase, $my;
	
	if ($my->id == 1) {
	$session = new mezunSession($dbase);
	$session->load($mainframe->_session->session);

	$session->access_type = 'admin';
	$session->update();
	
	Redirect('index.php');
	} else {
		NotAuth();
	}    
}


function MainPage() {
	
	mimport('helpers.modules.istek.helper');
	mimport('helpers.modules.online.helper');
	mimport('helpers.modules.mesaj.helper');
	?>
	<div class="col-sm-5">
	<?php 
	mezunGlobalHelper::WelcomePanel();
	mezunIstekHelper::loadIstekPanel();
	mezunMesajHelper::loadMailPanel();
	mezunGlobalHelper::loadUserStats();
	?>
	</div>
	<div class="col-sm-7">
	<?php
	mezunGlobalHelper::loadDuyuru();
	mezunGlobalHelper::getSiteAkis();
	?>
	</div>
	<?php
}

function getFooter() {
	include(ABSPATH.'/site/templates/'.SITETEMPLATE.'/footer.php');
}