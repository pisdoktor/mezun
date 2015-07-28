<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Istek {
	
	static function inBox($rows, $pageNav, $type) {
		$head = $type ? 'GİDEN ARKADAŞLIK İSTEKLERİ' : 'GELEN ARKADAŞLIK İSTEKLERİ';
		?>
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $head;?></div>
		<div class="panel-body">
<?php
	if (!$rows) {
		?>
		<div align="center">
		<div class="col-sm-12">
		Henüz bir arkadaşlık isteği yok!
		</div></div>
		<?php
	}
?>
<?php
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->giden = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->aid).'">'.$row->giden.'</a>';
$row->gonderen = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->gid).'">'.$row->gonderen.'</a>';

$user = $type ? $row->aid : $row->gid;

$row->gidenimage = $row->gidenimage ? '<img src="'.SITEURL.'/images/profil/'.$row->gidenimage.'" class="img-thumbnail profil-image" width="150" height="150" />':'<img src="'.SITEURL.'/images/profil/noimage.png" class="img-thumbnail profil-image" width="150" height="150" />';

$row->gonderenimage = $row->gonderenimage ? '<img src="'.SITEURL.'/images/profil/'.$row->gonderenimage.'" class="img-thumbnail profil-image" width="150" height="150" />':'<img src="'.SITEURL.'/images/profil/noimage.png" class="img-thumbnail profil-image" width="150" height="150" />';

$link = $type ? '<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=istek&task=delete&id='.$row->id.'">İsteği Sil</a>':'<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=istek&task=delete&id='.$row->id.'">İsteği Sil</a><a class="btn btn-primary btn-sm" href="index.php?option=site&bolum=istek&task=onayla&id='.$row->id.'">İsteği Onayla</a>';
?>
<div class="row">
<div class="col-sm-4">
<div align="center">
<?php echo $type ? $row->gidenimage : $row->gonderenimage;?>
</div>
<div  align="center"><?php mezunOnlineHelper::isOnline($row->id);?></div>
</div>

<div class="col-sm-5">
<div class="row">
<?php echo $type ? $row->giden : $row->gonderen;?>
</div>
<div class="row"><small><?php mezunArkadasHelper::ortakArkadasCount($user, true);?> ortak arkadaş</small></div>
<div class="row"><small>Gönderim Zamanı: <?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?></small></div>
</div>

<div class="col-sm-3">
<div class="btn-group-vertical">
<?php echo $link;?>
</div>
</div>

</div>

<?php
if ($i < count($rows)-1) {
	echo '<hr>';
}
}
?>
</div>
</div>
		<?php
		
	}
	
}
