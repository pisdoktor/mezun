<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class adminAlbumHTML {
	
	static function getAlbumList($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Albüm Yönetimi</h4></div>
	<div class="panel-body">

	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>ALBUM ADI</th>
	<th>RESİM SAYISI</th>
	<th>OLUŞTURAN</th>
	<th>OLUŞTURULMA ZAMANI</th>
	<th>DURUM</th>
	</tr>
	</thead>
<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$editlink = '<a href="index.php?option=admin&bolum=album&task=editalbum&id='.$row->id.'">Düzenle</a>';
$deletelink = '<a href="index.php?option=admin&bolum=album&task=deletealbum&id='.$row->id.'">Sil</a>';

if ($row->status == 0) {
	$status = 'Herkese Açık';
} else if ($row->status == 1) {
	$status = 'Arkadaşlar';
} else {
	$status = 'Gizli';
}
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
<td>
<?php echo $row->totalimages;?>
</td>
<td>
<?php echo $row->creator;?>
</td>
<td>
<?php echo mezunGlobalHelper::timeformat($row->creationdate, true, true);?>
</td>
<td>
<?php echo $status;?>
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
$link = 'index.php?option=admin&bolum=album&task=album';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
</div>
</div>

<?php
		
	}
	
	static function getImagesList($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Albüm Resimleri Yönetimi</h4></div>
	<div class="panel-body">

	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>RESİM BAŞLIĞI</th>
	<th>ALBÜM ADI</th>
	<th>OLUŞTURAN</th>
	<th>OLUŞTURULMA ZAMANI</th>
	</tr>
	</thead>
<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$editlink = '<a href="index.php?option=admin&bolum=album&task=editimage&id='.$row->id.'">Düzenle</a>';
$deletelink = '<a href="index.php?option=admin&bolum=album&task=deleteimage&id='.$row->id.'">Sil</a>';
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
<?php echo $row->albumname;?>
</td>
<td>
<?php echo $row->creator;?>
</td>
<td>
<?php echo mezunGlobalHelper::timeformat($row->addeddate, true, true);?>
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
$link = 'index.php?option=admin&bolum=album&task=images';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
</div>
</div>

<?php
		
	}

}