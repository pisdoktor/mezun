<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class DuyuruHTML {
	static function editDuyuru($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Duyuru <?php echo $row->id ? 'Düzenle' : 'Ekle';?></h4>
	</div>
	<div class="panel-body">

		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.metin.value == ""){
				alert( "Duyuru metnini boş bırakmışsınız" );
			}  else {
		submitform( pressbutton );
			}
		}
		//-->
		</script> 
<form action="index.php" method="post" name="adminForm" role="form">

<div class="row">
<div class="col-sm-3">
<label for="metin">
Duyuru Metni:
</label>
</div>
<div class="col-sm-9">
<textarea id="metin" name="text" cols="60" rows="10" class="form-control" required><?php echo $row->text;?></textarea>
</div>
</div>

<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="duyuru" />
<input type="hidden" name="tarih" value="<?php echo $row->tarih ? $row->tarih : date('Y-m-d H:i:s');?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />
</form>
<br />

<div>
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="btn btn-warning" />
</div>

</div>
</div>
<?php
}
	
	static function getDuyuruList($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Duyurular</h4></div>
	<div class="panel-body">
	
<form action="index.php" method="post" name="adminForm" role="form">

<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Duyuru Ekle" onclick="javascript:submitbutton('add');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu duyuruları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
</div>
</div>

<div class="row">
<div class="col-sm-1">
<strong>SIRA</strong>
</div>
<div class="col-sm-1">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</div>
<div class="col-sm-3">
<strong>DUYURU TARİHİ</strong>
</div>
<div class="col-sm-7">
<strong>DUYURU İÇERİĞİ</strong> 
</div>

</div>

<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$checked = mosHTML::idBox( $i, $row->id );
?>

<div class="row" id="detail<?php echo $row->id;?>">

<div class="col-sm-1">
<?php echo $pageNav->rowNumber( $i ); ?>
</div>
<div class="col-sm-1">
<?php echo $checked;?>
</div>
<div class="col-sm-3">
<a href="index.php?option=admin&bolum=duyuru&task=editx&id=<?php echo $row->id;?>"><?php echo Forum::timeformat($row->tarih, true, true);?></a>
</div>
<div class="col-sm-7">
<?php echo $row->text;?>
</div>
</div>

<?php
$t = 1 - $t;
}
?>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="duyuru" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />
<div class="btn-group">
<input type="button" name="button" value="Yeni Duyuru Ekle" onclick="javascript:submitbutton('add');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu duyuruları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
</div>
</form>

<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=duyuru';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>


</div>
</div>

<?php
		
	}
}
