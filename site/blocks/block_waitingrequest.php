<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

mimport('helpers.modules.istek.helper');

global $dbase;

$requests = mezunIstekHelper::incomingRequest();

$rows = '';

if ($requests) {
//son 5 isteği alalım
$requests = array_slice($requests, 0, 5);

//sorguya hazırlayalım
$users = implode(',', $requests);

$query = "SELECT id, name, image FROM #__users WHERE id IN (".$users.")";
$dbase->setQuery($query);
$rows = $dbase->loadObjectList();

}

if ($rows) {

$i = 0;        
foreach ($rows as $row) {
	$image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />';
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.userlink-<?php echo $row->id;?>').click(function (event){
				
				$('.userlink-<?php echo $row->id;?>').attr('disabled', true);
				
				$.ajax({
					type    : 'POST',
					url     : $(this).attr('url'),
					dataType: 'html',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					$('.onaysend').hide();
					$('.user-<?php echo $row->id;?>').html('<small>'+data+'</small>');
				});
				
				event.preventDefault();
			});
		});
		</script>
	<div class="row">
		
				<div class="col-sm-3">
					<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
					<?php echo $image;?>
					</a>
				</div>
		
				<div class="col-sm-5">
					<div class="row">
					<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->id;?>">
					<?php echo $row->name;?>
					</a>
					</div>
					
					<div class="row">
					<small><?php echo mezunArkadasHelper::ortakArkadasCount($row->id);?> ortak arkadaş</small>
					</div>
					
				</div>
				
				<div class="col-sm-4 user-<?php echo $row->id;?>">
				<div class="btn-group-vertical">
					<div url="index2.php?option=site&bolum=istek&task=onaylax&id=<?php echo $row->id;?>" href="#" class="btn btn-default btn-xs onaysend userlink-<?php echo $row->id;?>">
					Onayla
					</div>
					
					<div url="index2.php?option=site&bolum=istek&task=deletex&id=<?php echo $row->id;?>" href="#" class="btn btn-default btn-xs onaysend userlink-<?php echo $row->id;?>">
					Sil
					</div>
				</div>
				</div>
				
			 </div> 
		<?php
		if ($i<count($rows)-1) {
			echo '<hr>';
		}
		$i++;    
		}
} else {
	echo 'Bekleyen istek yok';
}