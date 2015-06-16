<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

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
	
	$query = "SELECT s.*, u.name, u.username, u.work FROM #__sessions AS s"
	. "\n LEFT JOIN #__users AS u ON u.id=s.userid"
	//. "\n WHERE s.userid NOT IN (".$dbase->Quote($my->id).")"
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
	<h3>ONLINE OLAN ÜYELER</h3>
	<table width="100%">
	<tr>
	<th>Üye Adı</th>
	<th>Üye Kullanıcı Adı</th>
	<th>Üye Kurumu</th>
	<th>Son İşlem Zamanı</th>
	<th></th>
	</tr>
	<?php
	foreach($rows as $row) {
	$link = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->userid.'">'.$row->name.'</a>';
	?>
	<tr>
	<td><center><?php echo $link;?></center></td>
	<td><center><?php echo $row->username;?></center></td>
	<td><center><?php echo $row->work;?></center></td>
	<td><center><?php echo date('H:i:s', $row->time);?></center></td>
	</tr>
	<?php
		}
	?>
	</table>
	<?php
}