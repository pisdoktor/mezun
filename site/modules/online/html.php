<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunOnlineHTML {
	static function showOnlineUsers($rows, $pageNav) {
	?>

	<div class="panel panel-info">
	<div class="panel-heading">ŞU ANDA SİTEDE OLAN ÜYELER</div>
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
	<div class="panel-footer" align="center">

<div class="row">
<div class="col-sm-12">
<?php echo $pageNav->writePagesCounter();?>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php 
$link = 'index.php?option=site&bolum=online';
echo $pageNav->writePagesLinks($link);
?>
</div>
</div>

</div>
	</div>

	<?php
}
}
