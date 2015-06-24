<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Arkadas {
	static function getList($rows, $pageNav) {
		?>
		<div class="panel panel-default">
		<div class="panel-heading"><h4>ARKADAŞLARIM</h4></div>
		<div class="panel-body">
		<?php
		if (!$rows) {
	?>
	<div align="center">
	<div class="row">
	Hiç arkadaşınız yok!
	</div>
	<div class="row">
	İsterseniz <a href="index.php?option=site&bolum=arama">üye arama</a> bölümünden arkadaşlarına ulaşabilirsiniz.
	</div>
	</div>
	<?php
	
}
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$image = $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png';
$link = '<a class="btn btn-default" href="index.php?option=site&bolum=profil&task=show&id='.$row->id.'">Profili Göster</a>';
$cinsiyet = $row->cinsiyet ? 'Erkek':'Bayan';
?>
<div class="row">

<div class="col-sm-3">
<img src="<?php echo $image;?>" class="img-circle" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="150" height="150" />
</div>

<div class="col-sm-7">

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Adı, Soyadı:</strong></div>
<div class="col-sm-8"><?php echo $row->name;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Siteye Son Geliş Tarihi:</strong></div>
<div class="col-sm-8"><?php echo mosFormatDate($row->lastvisit);?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Şuanda Çalıştığı Kurum:</strong></div>
<div class="col-sm-8"><?php echo $row->work;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Ünvanı:</strong></div>
<div class="col-sm-8"><?php echo $row->unvan;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Branşı:</strong></div>
<div class="col-sm-8"><?php echo $row->bransadi;?></div>
</div>
</div>

</div>

<div class="col-sm-2">
<?php echo $link;?>
</div>

</div>
<br />		
<?php
}
?>    
	</div>
	</div>
		
<div align="center">

<div class="row">
<div class="col-sm-12">
<?php echo $pageNav->writePagesCounter();?>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php 
$link = 'index.php?option=site&bolum=arkadas';
echo $pageNav->writePagesLinks($link);
?>
</div>
</div>

<div class="row">
<div class="col-sm-1">
<?php 
$link = 'index.php?option=site&bolum=arkadas';
echo $pageNav->writeLimitBox($link);
?>
</div>
</div>

</div>
<?php        
}
}
