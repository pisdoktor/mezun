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
		<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
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
	<table class="table table-striped">
		 <thead>
	  <tr>
		<th>SIRA</th>
		<th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
		<th>BAŞLIK</th>
		<th>GÖNDEREN</th>
		<th>TARİH</th>
	  </tr>
	</thead>
	<tbody>
	  
	

<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$checked = mezunHTML::idBox( $i, $row->id );

$row->baslik = mezunMesajHelper::cryptionText($row->baslik, 'decode');
?>
<tr class="<?php echo $t;?>">
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<?php echo $checked;?>
</td>
<td>
<a href="index.php?option=admin&bolum=bildirim&task=show&id=<?php echo $row->id;?>"><?php echo $row->baslik;?></a>
</td>
<td>
<?php echo $row->gonderen;?>
</td>
<td>
<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
</td>

</tr>

<?php
$t = 1 - $t;
}
?>	
</tbody>
</table>

	</div>
	<div class="panel-footer">
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
