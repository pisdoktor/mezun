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
$row->gidenimage = $row->gidenimage ? '<img src="'.SITEURL.'/images/profil/'.$row->gidenimage.'" class="img-thumbnail profil-image" width="200" height="200" />':'<img src="'.SITEURL.'/images/profil/noimage.png" class="img-thumbnail profil-image" width="200" height="200" />';
$row->gonderenimage = $row->gonderenimage ? '<img src="'.SITEURL.'/images/profil/'.$row->gonderenimage.'" class="img-thumbnail profil-image" width="200" height="200" />':'<img src="'.SITEURL.'/images/profil/noimage.png" class="img-thumbnail profil-image" width="200" height="200" />';

$row->gonderen = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->gid).'">'.$row->gonderen.'</a>';

$link = $type ? '<a class="btn btn-default" href="index.php?option=site&bolum=istek&task=delete&id='.$row->id.'">İsteği Sil</a>':'<a class="btn btn-default" href="index.php?option=site&bolum=istek&task=delete&id='.$row->id.'">İsteği Sil</a><br /><br /><a class="btn btn-primary" href="index.php?option=site&bolum=istek&task=onayla&id='.$row->id.'">İsteği Onayla</a>';
?>

<div class="col-sm-3">
<div align="center">
<div class="row">
<div class="figure"> 
<?php echo $type ? $row->gidenimage : $row->gonderenimage;?>
<div class="figcaption"><?php echo $link;?></div>
</div>

</div>
<div class="row">
<?php echo $type ? $row->giden : $row->gonderen;?>
<div align="center"><?php mezunOnlineHelper::isOnline($row->id);?></div>
<div align="center"><small><?php mezunArkadasHelper::ortakArkadasCount($row->id, true);?> ortak arkadaş</small></div>
</div>
</div>
</div>

<?php
}
?>
</div>
</div>
		<?php
		
	}
	
}
