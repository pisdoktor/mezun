<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class adminTemplatesHTML {
	
	static function newTemplate($lists) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Tema Yönetimi - Yeni Tema</h4></div>
	<div class="panel-body">
		<form action="index.php?option=admin&bolum=templates&task=save" method="post" enctype="multipart/form-data">
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="temp_type">
			  Tema Çeşidi:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <?php echo $lists['temp_type'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="tempfile">
			  Tema Paketi:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="file" name="tempfile" id="tempfile" data-filename-placement="inside">
			  </div>
		</div>
		</div>
		
		
		<div class="form-group">
		<button type="submit" class="btn btn-primary btn-sm">YÜKLE!</button>
		</div>
		</form>
		</div>
		</div>
		<?php
	}
	
	static function Templates($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Tema Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
	<div class="col-sm-12">
	<a class="btn btn-default btn-sm" href="index.php?option=admin&bolum=templates&task=new"><span>Yeni Tema Yükle</span></a>
	</div>
	</div>
		<table class="table table-striped">
		<thead>
		<tr>
		<th>SIRA</th>
		<th>İŞLEM</th>
		<th>TEMA ADI</th>
		<th>ÇEŞİDİ</th>
		<th>AKTİF</th>
		</tr>
		</thead>
		<tbody>
		<?php
for($i=0;$i<count($rows);$i++) {
$row = $rows[$i];

$editcsslink = '<a href="index.php?option=admin&bolum=templates&task=editcss&id='.$row->id.'">CSS Düzenle</a>';

$deletelink = '<a href="index.php?option=admin&bolum=templates&task=delete&id='.$row->id.'">Sil</a>';

$aktif = ($row->name == SITETEMPLATE) || ($row->name == ADMINTEMPLATE) ? '<img src="'.SITEURL.'/admin/images/star.png" />': '';

$error = '';
if (!file_exists(ABSPATH.'/'.$row->temp_type.'/templates/'.$row->name.'/index.php')) {
	$error = 'Temada eksik dosya var!';
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
	<li><?php echo $editcsslink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $row->name;?>
<div style="color:red;">
<small><?php echo $error;?></small>
</div>
</td>
<td>
<?php echo $row->temp_type;?>
</td>
<td>
<?php echo $aktif;?>
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
$link = 'index.php?option=admin&bolum=templates';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
		</div>
		</div>
		<?php
	}
	
}
