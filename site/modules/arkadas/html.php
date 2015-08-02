<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class ArkadasHTML {
	
	static function viewUserFriends($user, $rows, $pageNav) {
		?>

		<div class="panel panel-default">
		<div class="panel-heading"><?php echo $user->name;?>: Arkadaş Listesi</div>
		<div class="panel-body">
		<?php
		if (!$rows) {
	?>
	<div align="center">
	<div class="row">
	Hiç arkadaşı yok!
	</div>
	</div>
	<?php
	
}

for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$image = $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png';

$cinsiyet = $row->cinsiyet == 1 ? 'Erkek':'Bayan';

?>
<div class="row">
<div class="col-sm-3">
<div align="center">
<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
<img src="<?php echo $image;?>" class="img-thumbnail profil-image" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="100" height="100" />
</a>
</div>
<div align="center">
<?php mezunOnlineHelper::isOnline($row->id);?>
</div>
</div>
<div class="col-sm-6">
<div>
<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
<?php echo $row->unvan;?>. <?php echo $row->name;?>
</a>
<br />
<small><?php mezunArkadasHelper::ortakArkadasMI($user->id, $row->id);?></small>
</div>
<div>

</div>

</div>

</div>
<?php
if ($i < count($rows)-1) {
	echo '<hr>';
}
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
$link = 'index.php?option=site&bolum=arkadas&task=view&id='.$user->id;
echo $pageNav->writePagesLinks($link);
?>
</div>
</div>

</div>

</div>

<?php        
}
	
	static function getList($rows, $pageNav) {
		?>

		<div class="panel panel-default">
		<div class="panel-heading">ARKADAŞLARIM</div>
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

$delfriend = '<a class="btn btn-default btn-xs" href="'.sefLink('index.php?option=site&bolum=arkadas&task=delete&id='.$row->id).'">Arkadaşlıktan Çıkar</a>';

$cinsiyet = $row->cinsiyet == 1 ? 'Erkek':'Bayan';

?>
<div class="row">
<div class="col-sm-3">
<div align="center">
<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
<img src="<?php echo $image;?>" class="img-thumbnail profil-image" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="100" height="100" />
</a>
</div>
<div align="center">
<?php mezunOnlineHelper::isOnline($row->id);?>
</div>
</div>
<div class="col-sm-6">
<div>
<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
<?php echo $row->unvan;?>. <?php echo $row->name;?>
</a>
</div>
<div>
<small><?php mezunArkadasHelper::ortakArkadasCount($row->id, true);?> ortak arkadaş</small>
</div>
<div>

</div>

</div>

<div class="col-sm-3">
<?php echo $delfriend;?>
</div>

</div>
<?php
if ($i < count($rows)-1) {
	echo '<hr>';
}
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
