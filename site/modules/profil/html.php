<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Profile {
	static function getProfile($row, $edit) {
		
		$image = $row->image ? SITEURL.'/images/'.$row->image : SITEURL.'/images/noimage.png';
		$cinsiyet = $row->cinsiyet ? 'Erkek' : 'Bayan';
		$editlink = $edit ? '<a href="index.php?option=site&bolum=profil&task=edit">Düzenle</a>' : '';
		$passlink = $edit ? '<a href="#" id="changepass">Parola Değiştir</a>' : '';
		$editimage = $edit ? '<a href="#" id="changeimg">Resmi Değiştir</a>' : '';
		?>
		<div id="profile">
		
		<div id="profile-photo">
		<img src="<?php echo $image;?>" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" />
		</div>
		
		<div id="basic-info">
		<div><?php echo $row->username;?></div>
		<div><?php echo $row->name;?></div>
		<div><?php echo $cinsiyet;?></div>
		<div><?php echo $row->dogumtarihi;?></div>
		<div><?php echo $row->dogumyeri;?></div>
		<div><?php echo $row->sehiradi;?></div>
		<div><?php echo $row->registerDate;?></div>
		<div><?php echo $row->lastvisit;?></div>
		<div><?php echo $row->work;?></div>
		<div><?php echo $row->myili;?></div>
		<div><?php echo $row->byili;?></div>
		
		<?php echo $editlink;?>
		<?php echo $passlink;?>
		<?php echo $editimage;?>
		</div>
		
		</div>
		
		<!-- Profil Resmi Değiştirme -->
		<div id="imagechange">
		
		</div>
		<!-- Profil Resmi Değiştirme -->
		
		<!-- Parola Değiştirme -->
		<div id="passchange">
		
		</div>
		<!-- Parola Değiştirme -->
		<?php
	}
}
