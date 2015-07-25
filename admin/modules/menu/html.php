<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class menusHTML {
	
	static function editMenu($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Menü <?php echo $row->id ? 'Düzenle':'Yeni';?></h4></div>
	<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">

	<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="name">Menü Adı:</label>
<div class="col-sm-6">
<input name="name" id="name" type="text" class="form-control" placeholder="Menü Adı" value="<?php echo $row->name;?>"   required />
</div>
</div>
</div>

	<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="link">Menü Linki:</label>
<div class="col-sm-6">
<input name="link" id="link" type="text" class="form-control" placeholder="Menü Linki" value="<?php echo $row->link;?>"   required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="menu_Type">Menü Çeşidi:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::MenuType($row);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="parent">Ana Link:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::Parent($row);?>
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
<label class="control-label col-sm-4" for="access">Erişim:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::Access($row);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="ordering">Sıralama:</label>
<div class="col-sm-6">
<?php echo mezunAdminMenuHTML::Ordering($row, $row->id);?>
</div>
</div>
</div>



<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="menu" />
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
	
	static function getMenus($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Menüler</h4></div>
	<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">
	<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Menü Ekle" onclick="javascript:submitbutton('new');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu menüleri silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
</div>
</div>


<div class="row">
<div class="col-sm-1">
<strong>SIRA</strong>
</div>
<div class="col-sm-1">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</div>
<div class="col-sm-2">
<strong>MENÜ ADI</strong>
</div>
<div class="col-sm-4">
<strong>MENÜ LİNKİ</strong> 
</div>
<div class="col-sm-1">
<strong>YAYIN</strong> 
</div>
<div class="col-sm-1">
<strong>ERİŞİM</strong> 
</div>
<div class="col-sm-1">
<strong>ÇEŞİDİ</strong> 
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
$access = itemAccess($row->access);

?>
<div class="row">
<div class="col-sm-1">
<?php echo $pageNav->rowNumber( $i ); ?>
</div>
<div class="col-sm-1">
<?php echo $checked;?>
</div>
<div class="col-sm-2">
<a href="index.php?option=admin&bolum=menu&task=editx&id=<?php echo $row->id;?>">
<?php echo $row->treename;?>
</a>
</div>
<div class="col-sm-4">
<?php echo $row->link;?>
</div>
<div class="col-sm-1">
<?php echo $published;?> 
</div>
<div class="col-sm-1">
<?php echo $access;?> 
</div>
<div class="col-sm-1">
<?php echo $row->menu_type;?> 
</div>
<div class="col-sm-1">
<input type="text" name="ordering" value="<?php echo $row->ordering;?>" size="1" /> 
</div>

</div>
<?php   
}
?>


	
<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Menü Ekle" onclick="javascript:submitbutton('new');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu menüleri silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" /> 
</div>
</div>	

<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="menu" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
	</form>
	</div>
	</div>
		<?php
	}
}
