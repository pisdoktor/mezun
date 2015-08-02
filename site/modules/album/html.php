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
		
		if (!$can['View']) {
			echo 'Albümü görmeye yetkiniz yok!';
			return;
		}
		
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			$("a.group").fancybox({
				helpers : {
					title: {
					 type: 'inside',
					 position: 'bottom'
					}
				}
			});
		});
		</script>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo $album->name;?> - <?php echo $album->aciklama;?></div>
		<div class="panel-body">
		
		<div class="row">
		
		<div class="col-sm-8">
		<div class="row">
		<?php
		  
		  for($i=0;$i<count($rows);$i++) {
			  $row = $rows[$i];
			  
			  $title = $row->title ? $row->title : $album->name;
			  
			  $thumb = SITEURL.'/images/album/thumb/'.$row->image;
			  
			  $thumbhref = '<img class="img-thumbnail album-thumb" src="'.SITEURL.'/images/album/thumb/'.$row->image.'" alt="'.$title.'" title="'.$title.'" />';
			  
			  $image = SITEURL.'/images/album/'.$row->image;
			  
			  $imagehref = '<img class="img-thumbnail" src="'.SITEURL.'/images/album/'.$row->image.'" alt="'.$title.'" title="'.$title.'" />';
			  ?>
			  
			  <div class="col-sm-3">
			  
			  <a class="group" rel="group-<?php echo $album->id;?>" href="<?php echo $image;?>" title="<?php echo $title;?>" alt="<?php echo $title;?>">
			  <?php echo $thumbhref;?>
			  </a>
			 
			  <?php
				  if ($can['Edit']) {
					  ?>
	<div style="position: absolute; z-index: 100; top:0px; left:20px;">
	<div class="dropdown">
	<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
	<span class="glyphicon glyphicon-cog"></span>
	<span class="caret"></span></button>
	<ul class="dropdown-menu">
	<li><a href="index.php?option=site&bolum=album&task=editimage&id=<?php echo $row->id;?>">Düzenle</a></li>
	<li><a href="index.php?option=site&bolum=album&task=deleteimage&id=<?php echo $row->id;?>">Sil</a></li>
	</ul>
	</div>
	</div>
			  <?php
				  }
			  ?>
			  </div>			 
			  <?php
		  }
			
		?>
		</div>
  
		</div>
		
		
		<div class="col-sm-4">
				
		<div class="panel panel-warning">
		<div class="panel-heading">Albüm Bilgileri</div>
		<div class="panel-body">
		<div class="row">
		<div class="col-sm-6">
		<strong>Albüm Adı:</strong>
		</div>
		<div class="col-sm-6">
		<?php echo $album->name;?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-6">
		<strong>Görünürlük:</strong>
		</div>
		<div class="col-sm-6">
		<?php mezunAlbumHelper::albumStatus($album->status);?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-6">
		<strong>Toplam Resim:</strong>
		</div>
		<div class="col-sm-6">
		<?php echo $album->total;?>
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
		
		<?php
		if ($can['Edit']) {
			AlbumHTML::uploadImagePanel($album->id);
		}
		?>
		
		</div> <!-- col-sm-4 -->
		
		</div> <!-- row -->
		
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
		<div id="progress">
			<div class="bar" style="width: 0%; background-color: #ff0000; height: 10px;"></div>
		</div>
			<div id="drop">
				Resmi Sürükleyip Buraya Bırakın<br/> veya <br />
				<a class="btn btn-primary btn-sm">Resim Seçin</a>
				<input type="file" name="image" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
		<input type="hidden" name="id" value="<?php echo $id;?>" />
		</form>
		
		</div>
		<div class="panel-footer"></div>
		</div>
		<?php
	}
	
	static function editAlbum($row, $new, $list) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading">Albüm : <?php echo $row->id ? 'Düzenle':'Oluştur';?></div>
		<div class="panel-body">
		
		<form action="index.php?option=site&bolum=album&task=save" role="form" method="post">
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="name">Albümün Adı:</label>
		<div class="col-sm-6">
		<input name="name" id="name" type="text" class="form-control" placeholder="Albümün adını yazın" value="<?php echo $row->name;?>" required />
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="aciklama">Albümün Açıklaması:</label>
		<div class="col-sm-6">
		<input name="aciklama" id="aciklama" type="text" class="form-control" placeholder="Albümün açıklamasını yazın" value="<?php echo $row->aciklama;?>" required />
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="status">Albümün Görünürlüğü:</label>
		<div class="col-sm-6">
		<?php echo $list['status'];?>
		</div>
		</div>
		</div>

		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<button type="submit" class="btn btn-primary">Albümü Kaydet</button>
		</div>
		</div>
		</div>
		
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="new" value="<?php echo $new;?>" />
		</form>
		</div>
		</div>
		<?php
	}
	
	static function editImage($row, $album) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading">Resmi Düzenle</div>
		<div class="panel-body">
		<form method="post" action="index.php?option=site&bolum=album&task=saveimage">
		<!--
		<div class='frame'>
		<img id='sample_picture' src='<?php echo SITEURL.'/images/album/'.$row->image;?>'>
		</div>

	<div class="btn-group">
	  <button id="rotate_left" type="button" title="Sola Döndür" class="btn btn-default btn-sm"> &lt; </button>
	  <button id="zoom_out" type="button" title="Uzaklaştır" class="btn btn-default btn-sm"> - </button>
	  <button id="fit" type="button" title="Resmi Sığdır" class="btn btn-default btn-sm"> [ ]  </button>
	  <button id="zoom_in" type="button" title="Yakınlaştır" class="btn btn-default btn-sm"> + </button>
	  <button id="rotate_right" type="button" title="Sağa Döndür" class="btn btn-default btn-sm"> &gt; </button>
	</div>
	

		<input type="hidden" id="x" name="x" value="" >
		<input type="hidden" id="y" name="y" value="" >
		<input type="hidden" id="w" name="w" value="" >
		<input type="hidden" id="h" name="h" value="" >
		<input type="hidden" id="scale" name="scale" value="" >
		<input type="hidden" id="angle" name="angle" value="" >
		<input type="hidden" name="image" value="<?php echo $row->image;?>" >
		-->
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="image">Resim:</label>
		<div class="col-sm-8">
		<img class="img-rounded" name="image" src="<?php echo SITEURL.'/images/album/'.$row->image;?>" width="400" height="300">
		</div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="title">Resim Açıklaması:</label>
		<div class="col-sm-8">
		<input name="title" id="title" type="text" class="form-control" placeholder="Resmin altında çıkacak açıklamayı yazın" />
		</div>
		</div>
		</div>
		
		<input type="hidden" name="id" value="<?php echo $row->id;?>">
		<button type="submit" class="btn btn-default btn-sm">Resmi Kaydet</button>
		</form>
		  </div>
		</div>
		<!--
		<script type='text/javascript'>
	jQuery(function() {
		
	  var picture = $('#sample_picture');
	  
	  // Make sure the image is completely loaded before calling the plugin
	  picture.one('load', function(){
		  
		// Initialize plugin (with custom event)
		picture.guillotine({eventOnChange: 'guillotinechange'});
		
		// Display inital data
		var data = picture.guillotine('getData');
		
		for(var key in data) { $('#'+key).val(data[key]); }
		// Bind button actions
		$('#rotate_left').click(function(){ picture.guillotine('rotateLeft'); });
		$('#rotate_right').click(function(){ picture.guillotine('rotateRight'); });
		$('#fit').click(function(){ picture.guillotine('fit'); });
		$('#zoom_in').click(function(){ picture.guillotine('zoomIn'); });
		$('#zoom_out').click(function(){ picture.guillotine('zoomOut'); });
		
		// Update data on change
		picture.on('guillotinechange', function(ev, data, action) {
		  data.scale = parseFloat(data.scale.toFixed(4));
		  for(var k in data) { $('#'+k).val(data[k]); }
		});
		
	  });
	  // Make sure the 'load' event is triggered at least once (for cached images)
	  if (picture.prop('complete')) picture.trigger('load')
	});
  </script>
  -->
		<?php
	}
}