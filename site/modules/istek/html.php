<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Istek {
	
	static function inBox($rows, $pageNav, $type) {
		$head = $type ? 'ARKADAŞLIK İSTEKLERİ: GİDEN' : 'ARKADAŞLIK İSTEKLERİ: GELEN';
		?>
	<div class="panel panel-default">
		<div class="panel-heading"><h4><?php echo $head;?></h4></div>
		<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">
	
	<div class="form-group">
	<div class="btn-group">
	<?php echo $type == 0 ? formButton("Kabul Et", 'onayla', 1) : '';?>
	<?php echo $type == 0 ? formButton("Red Et", 'delete', 2) : '';?>
	<?php echo $type == 1 ? formButton("İptal Et", 'delete', 2) : '';?>
	</div>  
	</div>
	
	<div class="row">
	<div class="col-sm-1">
	<strong>SIRA</strong>
	</div>
	<div class="col-sm-1">
	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>)"/>
	</div>
	<div class="col-sm-5">
	<strong><?php echo $type ? 'Gönderilen' : 'Gönderen';?></strong>
	</div>
	<div class="col-sm-5">
	<strong>Gönderim Zamanı</strong>
	</div>
	</div>

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
$row->giden = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->aid.'">'.$row->giden.'</a>';
$row->gonderen = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->gid.'">'.$row->gonderen.'</a>';
$checked = mosHTML::idBox( $i, $row->id );
?>
<div class="row" id="<?php echo $row->id;?>">
	<div class="col-sm-1">
	<?php echo $pageNav->rowNumber( $i ); ?>
	</div>
	<div class="col-sm-1">
	<?php echo $checked;?>
	</div>
	<div class="col-sm-5">
	<?php echo $type ? $row->giden : $row->gonderen;?>
	</div>
	<div class="col-sm-5">
	<?php echo Forum::timeformat($row->tarih, true, true);?>
	</div>
</div>
<?php
}
?>
<input type="hidden" name="option" value="site" />
<input type="hidden" name="bolum" value="istek" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
</div>
		<?php
		
	}
	
}
