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
$link = '<a class="btn btn-default" href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->id).'">Profili Göster</a>';
$delfriend = '<a class="btn btn-warning" href="'.sefLink('index.php?option=site&bolum=arkadas&task=delete&id='.$row->id).'">Arkadaşlıktan Çıkar</a>';
$cinsiyet = $row->cinsiyet == 1 ? 'Erkek':'Bayan';
?>
<div class="row">
<div class="col-sm-3">
<div align="center">
<div class="row">
<div class="figure"> 
<img src="<?php echo $image;?>" class="img-thumbnail profil-image" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
<div class="figcaption"><?php echo $link;?><br /><br /> <?php echo $delfriend;?></div>
</div>

</div>
<div class="row">
<?php echo $row->unvan;?>. <?php echo $row->name;?>
<div align="center"><?php mezunOnlineHelper::isOnline($row->id);?></div>
<div align="center"><small><?php mezunArkadasHelper::ortakArkadasCount($row->id, true);?> ortak arkadaş</small></div>
</div>
</div>
</div>
</div>
<?php
}
?>    
</div>

<div class="panel-footer" align="center">

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

</div>

</div>
<?php        
}
}
