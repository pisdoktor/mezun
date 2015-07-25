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
				'istek_href' => 'index.php?option=site&bolum=istek&task=send&id='.$row->id,
				'image' => $row->image ? '<img src="'.SITEURL.'/images/profil/'.$row->image.'" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />':'<img src="'.SITEURL.'/images/profil/noimage.png" width="50" height="50" alt="'.$row->name.'" title="'.$row->name.'" />'
				);
			}
		}
	}	
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
				
				<div class="col-sm-5">
					<a href="<?php echo $user['istek_href'];?>" class="btn btn-default btn-xs">
					Arkadaşı ekle
					</a>
				
				</div>
				
			 </div>
			 
		<?php
		if ($i<count($users)-1) {
			echo '<hr>';
		}
		$i++;	
		}