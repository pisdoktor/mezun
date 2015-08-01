<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class AlbumHTML {
	
	static function getMyAlbums($rows, $pageNav) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading">ALBÜMLERİM</div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<?php
		
		foreach ($rows as $row) {
							
			$row->name = mezunGlobalHelper::shortText($row->name, 20);
			
			$row->aciklama = mezunGlobalHelper::shortText($row->aciklama, 30);
			
			?>
			<div class="col-sm-3">
			<div align="center">
			<div class="row">
			<a href="<?php echo sefLink('index.php?option=site&bolum=album&task=view&id='.$row->id);?>">
			<?php echo $row->name;?>
			</a>
			</div>
			<div class="row">
			<?php echo $row->aciklama;?>
			</div>
			<div class="row">
			<small><?php echo $row->total;?> Resim</small>
			</div>
			</div>
			</div>
			<?php
		}
			
		?>
		</div>
		</div>
		
		</div>
		<div class="panel-footer">
		<div align="center">
		<div class="row">
		<?php echo $pageNav->writePagesCounter();?>
		</div>
		<div class="row">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=album');?>
		</div>
		</div>
		</div>
		</div>
		<?php
	}
	
	static function viewAlbum($album, $can, $rows, $pageNav) {
		$editlink = $can['Edit'] ? '<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=album&task=edit&id='.$album->id.'">Düzenle</a>':'';
		$deletelink = $can['Edit'] ? '<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=album&task=delete&id='.$album->id.'">Sil</a>':'';
		
		?>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo $album->name;?></div>
		<div class="panel-body">
		
		<div class="row">
		<div class="col-sm-8">
		<?php
		  
		  for($i=0;$i<count($rows);$i++) {
			  $row = $rows[$i];
			  $thumb = '<img class="img-thumbnail" src="'.SITEURL.'/images/album/thumb/'.$row->image.'" alt="" title="" />';
			  $image = '<img src="'.SITEURL.'/images/album/'.$row->image.'" alt="" title="" />';
			  
			  echo $thumb;
		  }
			
		?>
		</div>
		
		<div class="col-sm-4">
		<?php
		if ($can['Edit']) {
			AlbumHTML::uploadImagePanel($album->id);
		}
		?>
		
		<div class="panel panel-warning">
		<div class="panel-heading">Albüm Bilgileri</div>
		<div class="panel-body">
		<div class="row">
		
		<div class="col-sm-6">
		Albüm Adı:
		</div>
		<div class="col-sm-6">
		<?php echo $album->name;?>
		</div>
		
		</div>
		
		<div class="row">
		
		<div class="col-sm-6">
		Albüm Açıklaması:
		</div>
		<div class="col-sm-6">
		<?php echo $album->aciklama;?>
		</div>
		
		</div>
		<div class="row">
		
		<div class="col-sm-6">
		Toplam Resim:
		</div>
		<div class="col-sm-6">
		<?php echo $album->total;?>
		</div>
		
		</div>
		
		<div class="row">
		
		<div class="col-sm-6">
		Görünürlük:
		</div>
		<div class="col-sm-6">
		<?php mezunAlbumHelper::albumStatus($album->status);?>
		</div>
		
		</div>
		<?php if ($can['Edit']) {?>
		<br />
		<div class="row">
		
		<div class="col-sm-6">
		<div align="center">
		<div class="btn-group-vertical">
		<?php echo $editlink;?> <?php echo $deletelink;?> 
		</div>
		</div>
		</div>
		
		</div>
		<?php }?>
		
		</div>
		</div>
		
		</div>
		</div>
		
		</div>
		
		<div class="panel-footer">
		<div align="center">
		<div class="row">
		<?php echo $pageNav->writePagesCounter();?>
		</div>
		<div class="row">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=album&task=view&id='.$album->id);?>
		</div>
		</div>
		</div>
		</div>
		<?php
		
	}
	
	static function uploadImagePanel($id) {
		?>
		<div class="panel panel-warning">
		<div class="panel-heading">Albüme Resim Ekle</div>
		<div class="panel-body">
		
		<form id="upload" method="post" action="index2.php?option=site&bolum=album&task=upload" enctype="multipart/form-data">
			<div id="drop">
				Resmi Buraya Sürükleyip Bırakın<br/> veya <br />
				<a class="btn btn-primary btn-sm">Resim Seçin</a>
				<input type="file" name="image" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
		<input type="hidden" name="id" value="<?php echo $id;?>" />
		</form>
		
		</div>
		</div>
		<?php
	}
	
	static function editAlbum($row, $new) {
		
	}
	
	static function uploadImage() {
		
	}
	
	static function editImage() {
		
	}
}