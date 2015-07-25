<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

mimport('helpers.modules.online.helper');

switch($task) {
	default:
	getOnline();
	break;
}

/**
* Online üyeleri veritabanından alalım
* Sayfaya bakan kullanıcının kendisi görmesi engellenmeli!
*/
function getOnline() {
	global $dbase, $my;
	
	$query = "SELECT s.userid, s.time, u.name, u.nowvisit, ss.name AS sehir FROM #__sessions AS s"
	. "\n LEFT JOIN #__users AS u ON u.id=s.userid"
	. "\n LEFT JOIN #__sehirler AS ss ON ss.id=u.sehir"
	. "\n WHERE s.userid > 0 "
	. "\n AND s.userid NOT IN (".$dbase->Quote($my->id).")"
	. "\n ORDER BY s.time DESC"
	;
	
	$dbase->setQuery($query);
	$rows = $dbase->loadObjectList();
	
	showOnlineUsers($rows);
}

/**
* Online üyeleri gösteren fonksiyon
* @param mixed $rows online üyelerin bilgilerini içerir
* Bazı ufak düzenlemeler yapılacak!
*/
function showOnlineUsers($rows) {
	?>
	<div class="panel panel-info">
	<div class="panel-heading"><h4>ONLİNE ÜYELER</h4></div>
	<div class="panel-body">
	
	<div class="row">
	<div class="col-sm-3">
	<strong>Üye Adı</strong>
	</div>
	<div class="col-sm-3">
	<strong>Bulunduğu Şehir</strong>
	</div>
	<div class="col-sm-2">
	<strong>Siteye Giriş Zamanı</strong>
	</div>
	<div class="col-sm-2">
	<strong>Son İşlem Zamanı</strong>
	</div>
	<div class="col-sm-2">
	<strong>Online Süresi</strong>
	</div>
	</div>
	<?php
	foreach($rows as $row) {
	$link = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->userid).'">'.$row->name.'</a>';
	$onlinetime = mezunOnlineHelper::calcOnlineTime(($row->time), strtotime($row->nowvisit));
	?>
	<div class="form-group">
	<div class="row">
	<div class="col-sm-3">
	<?php echo $link;?>
	</div>
	<div class="col-sm-3">
	<?php echo $row->sehir;?>
	</div>
	<div class="col-sm-2">
	<?php echo FormatDate($row->nowvisit, '%H:%M:%S');?>
	</div>
	<div class="col-sm-2">
	<?php echo date('H:i:s', $row->time+(OFFSET*3600));?>
	</div>
	<div class="col-sm-2">
	<?php echo $onlinetime;?>
	</div>
	</div>
	</div>
		<?php
		}
	?>
	</div>
	<div class="panel-footer"></div>
	</div>
	<?php
}