<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

mimport('helpers.modules.arkadas.helper');
mimport('helpers.modules.istek.helper');
	
	/**
	* Objectlist içerisinde kullanıcının; id, name, username, image, registerDate, lastvisit, sehir
	* bilgileri var.
	*/
	$rows = mezunArkadasHelper::canRecognize();
	
	//Her kullanıcı için daha önceden istek gönderilmiş olanları çıkaralım ve yeni bir dizi oluşturalım
	$users = array();
	
	foreach ($rows as $row) {
		//istek gönderilmemiş olanları alalım
		if (!mezunIstekHelper::checkIstek($row->id)) {
			
			if (!isset($users[$row->id])) {
				$users[$row->id] = array(
				'id' => $row->id,
				'name' => $row->name,
				'profil_href' => 'index.php?option=site&bolum=profil&task=show&id='.$row->id,
				'istek_href' => 'index2.php?option=site&bolum=istek&task=sendx&id='.$row->id,
				'image' => $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />'
				);
			}
		}
	}	
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('.isteksend').click(function (event){
				
				$('.isteksend').attr('disabled', true);
				
				$.ajax({
					type    : 'POST',
					url     : $(this).attr('url'),
					dataType: 'json',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					$('.isteksend').removeAttr('disabled');
					$('.userlink-'+data).empty();
					$('.user-'+data).html('<small>İstek gönderildi</small>');
				});
				
				event.preventDefault();
			});
		});
		</script>
		<?php
		$i = 0;		
		foreach ($users as $user) {
		?>
			<div class="row">
		
				<div class="col-sm-3">
					<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $user['id'];?>">
					<?php echo $user['image'];?>
					</a>
				</div>
		
				<div class="col-sm-4">
					<div class="row">
					<a href="<?php echo $user['profil_href'];?>">
					<?php echo $user['name'];?>
					</a>
					</div>
					
					<div class="row">
					<small><?php echo mezunArkadasHelper::ortakArkadasCount($user['id']);?> ortak arkadaş</small>
					</div>
					
				</div>
				
				<div class="col-sm-5 user-<?php echo $user['id'];?>">
					<div url="<?php echo $user['istek_href'];?>" href="#" class="btn btn-default btn-xs isteksend userlink-<?php echo $user['id'];?>">
					Arkadaşı ekle
					</div>
				</div>
				
			 </div> 
		<?php
		if ($i<count($users)-1) {
			echo '<hr>';
		}
		$i++;	
		}