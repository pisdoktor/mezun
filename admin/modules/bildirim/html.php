<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class Bildirim {
	
	static function showBildirim($row) {
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><h4>Geri Bildirim: <?php echo $row->baslik;?></h4></div>
		<div class="panel-body">
		
		<div class="row">
		<div class="col-sm-3">
		<strong>Gönderen:</strong>
		</div>
		<div class="col-sm-9">
		<?php echo $row->gonderen;?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-3">
		<strong>Gönderim Tarihi:</strong>
		</div>
		<div class="col-sm-9">
		<?php echo Forum::timeformat($row->tarih, true, true);?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-12">
		<?php echo $row->text;?>
		</div>
		</div>
		
		</div>
		</div>
		<?php
	}
	
	static function getBildirim($rows, $pageNav, $crypt) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Geri Bildirimler</h4></div>
	<div class="panel-body">
	
	<div class="row">
<div class="col-sm-1">
<strong>SIRA</strong>
</div>
<div class="col-sm-4">
<strong>BAŞLIK</strong>
</div>
<div class="col-sm-4">
<strong>GÖNDEREN</strong> 
</div>
<div class="col-sm-3">
<strong>GÖNDERİM TARİHİ</strong> 
</div>
</div>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->baslik = $crypt->cryptionText($row->baslik, 'decode');
?>

<div class="row" id="detail<?php echo $row->id;?>">

<div class="col-sm-1">
<?php echo $pageNav->rowNumber( $i ); ?>
</div>
<div class="col-sm-4">
<a href="index.php?option=admin&bolum=bildirim&task=show&id=<?php echo $row->id;?>"><?php echo $row->baslik;?></a>
</div>
<div class="col-sm-4">
<?php echo $row->gonderen;?>
</div>
<div class="col-sm-3">
<?php echo Forum::timeformat($row->tarih, true, true);?>
</div>
</div>

<?php
$t = 1 - $t;
}
?>	
	
	
	<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=bildirim';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
	</div>
	</div>
	<?php
	}
}
