<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

global $my;

mimport('helpers.modules.group.helper');

$rows = mezunGroupHelper::getMyGroups(true);

$rows = array_slice($rows, 0, 5);

$i=0;
foreach ($rows as $row) {
	$row->image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
	
	$row->name = mezunGlobalHelper::shortText($row->name, 20);
			
	$row->aciklama = mezunGlobalHelper::shortText($row->aciklama, 30);
	
	?>
	<div class="row">
	<div class="col-sm-3">
	<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
	<?php echo $row->image;?>
	</a>
	</div>
	<div class="col-sm-5">
	<div>
	<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
	<?php echo $row->name;?>
	</a>
	</div>
	<div>
	<small><?php echo mezunGroupHelper::getGroupUsers($row->id, true);?> Üye</small>
	</div>
	</div>
	<div class="col-sm-4">
	<?php
	if ($my->id != $row->creator) {
	?>
	<a href="index.php?option=site&bolum=group&task=leave&id=<?php echo $row->id;?>" class="btn btn-default btn-xs">Gruptan Çık</a>
	<?php } ?>
	</div>
			</div>
			<?php
			if ($i < count($rows)-1) {
				echo '<hr>';
			}
			$i++;
}
