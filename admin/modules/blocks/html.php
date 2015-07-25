<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class BlocksHTML {
	
	static function editBlock($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Block <?php echo $row->id ? 'Düzenle':'Yeni';?></h4></div>
	<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">

	<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="title">Blok Başlığı:</label>
<div class="col-sm-6">
<input name="title" id="title" type="text" class="form-control" placeholder="Blok başlığı" value="<?php echo $row->title;?>"   required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="showtitle">Başlık Gösterimi:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::ShowTitle($row);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="published">Yayınlanma:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::Published($row);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="ordering">Sıralama:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::BlockOrdering($row, $row->id);?>
</div>
</div>
</div>



<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="blocks" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />

<div>
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="btn btn-warning" />
</div>
	
	</form>
	</div>
	</div>
		<?php
	}
	
	static function getBlocks($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Bloklar</h4></div>
	<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">
	<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Blok Ekle" onclick="javascript:submitbutton('new');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu blockları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
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
<strong>BLOK BAŞLIĞI</strong>
</div>
<div class="col-sm-2">
<strong>BLOK DOSYASI</strong> 
</div>
<div class="col-sm-2">
<strong>BAŞLIK GÖSTERİMİ</strong> 
</div>
<div class="col-sm-1">
<strong>POZİSYON</strong> 
</div>
<div class="col-sm-1">
<strong>YAYIN</strong> 
</div>
<div class="col-sm-1">
<strong>SIRALA</strong> 
</div>

</div>
<?php
for($i=0;$i<count($rows);$i++) {
$row = $rows[$i];
$checked = mezunHTML::idBox( $i, $row->id );
$published = itemState($row->published);
$showtitle = $row->showtitle ? 'Evet':'Hayır';

?>
<div class="row">
<div class="col-sm-1">
<?php echo $pageNav->rowNumber( $i ); ?>
</div>
<div class="col-sm-1">
<?php echo $checked;?>
</div>
<div class="col-sm-3">
<a href="index.php?option=admin&bolum=blocks&task=editx&id=<?php echo $row->id;?>">
<?php echo $row->title;?>
</a>
</div>
<div class="col-sm-2">
<?php echo $row->block;?>
</div>
<div class="col-sm-2">
<?php echo $showtitle;?> 
</div>
<div class="col-sm-1">
<?php echo $row->position;?>
</div>
<div class="col-sm-1">
<?php echo $published;?>
</div>
<div class="col-sm-1">
<?php echo $row->ordering;?> 
</div>

</div>
<?php   
}
?>
<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Blok Ekle" onclick="javascript:submitbutton('new');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu blockları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
</div>
</div>
	<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="blocks" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
	</form>
	</div>
	</div>
	<?php
	}
}