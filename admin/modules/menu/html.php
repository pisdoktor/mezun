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
	<div class="panel-heading"><h4>Yönetim Paneli - Menü Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=menu&task=new" class="btn btn-default btn-sm">Yeni Menü Ekle</a>
</div>
</div>
	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>MENÜ ADI</th>
	<th>MENÜ LİNKİ</th>
	<th>YAYINLANMA</th>
	<th>ERİŞİM</th>
	<th>ÇEŞİT</th>
	</tr>
	</thead>
	<tbody>
<?php
for($i=0;$i<count($rows);$i++) {
$row = $rows[$i];

$published = itemState($row->published);

$access = itemAccess($row->access);

$editlink = '<a href="index.php?option=admin&bolum=menu&task=edit&id='.$row->id.'">Düzenle</a>';

$deletelink = '<a href="index.php?option=admin&bolum=menu&task=delete&id='.$row->id.'">Sil</a>';

?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  <span class="glyphicon glyphicon-cog"></span> 
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><?php echo $editlink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $row->treename;?>
</td>
<td>
<?php echo $row->link;?>
</td>
<td>
<?php echo $published;?>
</td>
<td>
<?php echo $access;?>
</td>
<td>
<?php echo $row->menu_type;?> 
</td>
</tr>
<?php   
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
$link = 'index.php?option=admin&bolum=menu';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
	</div>
	</div>
		<?php
	}
}
