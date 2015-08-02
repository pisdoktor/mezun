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

<?php if (!$row->block) { ?>
<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="content">Blok İçeriği:</label>
<div class="col-sm-6">
<textarea cols="5" class="form-control" name="content" id="content" required>
<?php echo $row->content;?>
</textarea>
</div>
</div>
</div>
<?php }?>

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

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="ordering">Gösterim Yeri:</label>
<div class="col-sm-3">
<?php echo mezunAdminMenuHTML::BlockMenu($row);?>
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
	<div class="panel-heading"><h4>Yönetim Paneli - Blok Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=blocks&task=new" class="btn btn-default btn-sm">Yeni Blok Ekle</a>
</div>
</div>
	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>BAŞLIK</th>
	<th>DOSYA</th>
	<th>BAŞLIK GÖSTERİMİ</th>
	<th>POZİSYON</th>
	<th>YAYIN</th>
	</tr>
	</thead>
	<tbody>
<?php
for($i=0;$i<count($rows);$i++) {
$row = $rows[$i];

$published = itemState($row->published);

$showtitle = $row->showtitle ? 'Evet':'Hayır';

$editlink = '<a href="index.php?option=admin&bolum=blocks&task=edit&id='.$row->id.'">Düzenle</a>';
$deletelink = $row->iscore ? '':'<a href="index.php?option=admin&bolum=blocks&task=delete&id='.$row->id.'">Sil</a>';

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
<?php echo $row->title;?>
</td>
<td>
<?php echo $row->block;?>
</td>
<td>
<?php echo $showtitle;?> 
</td>
<td>
<?php echo $row->position;?>
</td>
<td>
<?php echo $published;?>
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
$link = 'index.php?option=admin&bolum=blocks';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
	</div>
	</div>
	<?php
	}
}