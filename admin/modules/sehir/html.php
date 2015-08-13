<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class IlHTML {
	static function editIl($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Şehir <?php echo $row->id ? 'Düzenle' : 'Ekle';?></h4>
	</div>
	<div class="panel-body">
<form action="index.php" method="post" name="adminForm" role="form">

<div class="row">
<div class="col-sm-3">
<label class="control-label" for="name">Şehir Adı:</label>
</div>

<div class="col-sm-4">
<input type="text" id="name" name="name" class="form-control" value="<?php echo $row->name;?>" required>
</div>
</div>

<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="sehir" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />
<br />
</form>
<div>
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="btn btn-warning" />
</div>

</div>
</div>
<?php
}
	
	static function getIlList($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Şehir Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
<div class="col-sm-12">
<a href="index.php?option=admin&bolum=sehir&task=new" class="btn btn-default btn-sm">Yeni Şehir Ekle</a>
</div>
</div>
	
	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>ŞEHİR ADI</th>
	</tr>
	</thead>
	<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$editlink = '<a href="index.php?option=admin&bolum=sehir&task=edit&id='.$row->id.'">Düzenle</a>';
$deletelink = '<a href="index.php?option=admin&bolum=sehir&task=delete&id='.$row->id.'">Sil</a>';

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
<?php echo $row->name;?>
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
$link = 'index.php?option=admin&bolum=sehir';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
</div>
</div>

<?php
		
	}
}
