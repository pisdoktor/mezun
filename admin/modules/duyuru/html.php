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
<form action="index.php" method="post" name="adminForm" role="form">

<div class="row">
<div class="col-sm-3">
<label for="metin">
Duyuru Metni:
</label>
</div>
<div class="col-sm-9">
<textarea id="metin" name="text" cols="6" class="form-control" required><?php echo $row->text;?></textarea>
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
	<div class="panel-heading"><h4>Yönetim Paneli - Duyuru Yönetimi</h4></div>
	<div class="panel-body">
	
	<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=duyuru&task=new" class="btn btn-default btn-sm">Yeni Duyuru Ekle</a>
</div>
</div>
	
	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>DUYURU TARİHİ</th>
	<th>DUYURU İÇERİĞİ</th>
	</tr>
	</thead>
<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$editlink = '<a href="index.php?option=admin&bolum=duyuru&task=edit&id='.$row->id.'">Düzenle</a>';
$deletelink = '<a href="index.php?option=admin&bolum=duyuru&task=delete&id='.$row->id.'">Sil</a>';
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
<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
</td>
<td>
<?php echo mezunGlobalHelper::shortText($row->text, 50);?>
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
$link = 'index.php?option=admin&bolum=duyuru';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
</div>
</div>

<?php
		
	}
}
