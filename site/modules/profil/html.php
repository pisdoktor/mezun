<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunProfilHTML {
	static function editImage($photo, $width, $height, $type, $minWidth, $minHeight) {
		
		if ($width < $minWidth) {
			$minWidth = $width;
		}
		if ($height < $minHeight) {
			$minHeight = $height;
		}
		
		?>
<script type="text/javascript">
  $(function(){
	  
	$('#target').Jcrop({
		boxWidth: 960,
		boxHeight: 450,
		setSelect: [ <?php echo $minWidth;?>, <?php echo $minHeight;?>, 0, 0 ],
		trueSize: [<?php echo $width;?>, <?php echo $height;?>],
	  onSelect: updateCoords,
	  onChange: updateCoords
	});

  });
  
  function checkCoords(){
	if (parseInt(jQuery('#w').val())>0) return true;
	alert('Lütfen bir alan seçin.');
	return false;
  };
  
  function updateCoords(c) {
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
  };
</script>
<div class="text-info"><h4>Resmin içerisindeki kutucuğu uygun şekilde sürükleyip "Resmi Kes ve Kaydet" butonuna basınız! Kutucuk içerisinde kalan alan profil resminiz olarak kullanılacaktır!</h4></div>
<div id="real-image" align="center">
<img id="target" src="<?php echo $photo['withaddr'];?>" alt="Düzenlenecek Profil Resmi">
</div>


<form action="<?php echo sefLink('index.php?option=site&bolum=profil&task=cropsave');?>" method="post" onsubmit="return checkCoords();">
	<input type="hidden" id="x" name="x" />
	<input type="hidden" id="y" name="y" />
	<input type="hidden" id="w" name="w" />
	<input type="hidden" id="h" name="h" />
	<input type="hidden" name="type" value="<?php echo $type;?>" />
<br />
	<input type="submit" value="Resmi Kes ve Kaydet" class="btn btn-primary" />
</form>
		<?php
	}
	
	static function editProfile($row) {
		?>
		<script type="text/javascript">
$(function(){
	$('select[name=byili]').on('change', function(){
		
		$('select[name=myili]').children().each(function() {
			
			if ($(this).val() <= $('select[name=byili]').val()) {
				$(this).prop('hidden',true);
			} else {
				$(this).prop('hidden',false);
			}
		});
	});
});
</script>
<form action="<?php echo sefLink('index.php?option=site&bolum=profil&task=save');?>" method="post" id="adminForm" role="form">
<div class="panel panel-warning">
		<div class="panel-heading">PROFİL DÜZENLE</div>
		<div class="panel-body">
		
		<fieldset>
		<legend>Mesleki Bilgileriniz:</legend>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Branşınız:</label>
<div class="col-sm-4">
<?php echo $row->selectBrans();?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Ünvanınız:</label>
<div class="col-sm-4">
<?php echo $row->selectUnvan();?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="byili">Okula Başlangıç Yılınız:</label>
<div class="col-sm-3">
<?php echo $row->selectYil('byili');?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="myili">Okulu Bitiriş Yılınız:</label>
<div class="col-sm-3">
<?php echo $row->selectYil('myili');?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Şuanda Çalıştığınız Kurum:</label>
<div class="col-sm-6">
<input name="work" id="work" type="text" class="form-control" value="<?php echo $row->work;?>" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="okulno">Okul Numaranız:</label>
<div class="col-sm-4">
<input name="okulno" id="okulno" type="text" class="form-control" value="<?php echo $row->okulno;?>" />
</div>
</div>
</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Kişisel Bilgileriniz:</legend>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="name">Adınız ve Soyadınız:</label>
		<div class="col-sm-6">
		<input name="name" id="name" type="text" class="form-control" value="<?php echo $row->name;?>" required />
		</div>
		</div>
		</div>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Yaşadığınız Şehir:</label>
<div class="col-sm-3">
<?php echo $row->selectSehir('sehir');?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="phone">Telefon Numaranız:</label>
<div class="col-sm-3">
<input name="phone" id="phone" type="text" class="form-control bfh-phone" value="<?php echo $row->phone;?>" data-format="d (ddd) ddd dd dd" placeholder="0 (000) 000 00 00 şeklinde" required />
</div>
</div>
</div>
		
		</fieldset>
		
<div class="form-group">
<div class="row">
<div class="col-sm-12">
<button type="submit" class="btn btn-primary" />PROFİLİMİ GÜNCELLE!</button>
</div>
</div>
</div>
</div>
</div>
</form>
		<?php
	}
	
	static function getProfile($row, $can) {
		
		$image = $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png';
		
		$cinsiyet = $row->cinsiyet == 1 ? 'Erkek' : 'Bayan';
		
		$editlink = $can['Edit'] ? '<a class="btn btn-default btn-sm" href="'.sefLink('index.php?option=site&bolum=profil&task=edit').'">Profili Düzenle</a>' : '';
		
		$passlink = $can['Edit'] ? '<a class="btn btn-default btn-sm" href="#" id="changepass">Parola Değiştir</a>' : '';
		
		$editimage = $can['Edit'] ? '<a class="btn btn-default btn-sm" href="#" id="changeimg">Resim Ekle</a>' : '';
		
		$deleteimage = ($can['Edit'] && $row->image) ? '<a class="btn btn-default btn-sm" href="'.sefLink('index.php?option=site&bolum=profil&task=deleteimage').'">Resmi Sil</a>' : ''; 
		
		$cropimage = ($can['Edit'] && $can['Crop']) ? '<a class="btn btn-default btn-sm" href="'.sefLink('index.php?option=site&bolum=profil&task=editimage').'">Resmi Düzenle</a>' : '';
		 
		$msglink = $can['SendMsg'] ? '<a class="btn btn-default btn-sm" href="#" id="sendamsg">Mesaj Gönder</a>' : '';
		
		$deletefriend = $can['SendMsg'] ? '<a class="btn btn-default btn-sm" href="'.sefLink('index.php?option=site&bolum=arkadas&task=delete&id='.$row->id).'">Arkadaşlıktan Çıkar</a>':'';
		
		$istemlink = $can['SendIstem'] ? '<a class="btn btn-default btn-sm" href="'.sefLink('index.php?option=site&bolum=istek&task=send&id='.$row->id).'">Arkadaşlık İsteği Gönder</a>' : '';		
		
		
		$head = $can['Edit'] ? 'PROFİLİM' : 'PROFİL: '.$row->name;
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><?php echo $head;?></div>
		<div class="panel-body"> 
		
		<div class="row">
		<div class="col-sm-5">
		
		<div align="center">
		<div class="row">
		<img src="<?php echo $image;?>" class="img-thumbnail" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
		</div>
		<?php if (!$can['Edit']) {?>
		<div align="center">
		<div>
		<?php mezunOnlineHelper::isOnline($row->id);?>
		</div>
		<div>
		<small><?php echo mezunArkadasHelper::ortakArkadasCount($row->id, true);?> ortak arkadaş</small>
		</div>
		</div>
		<?php }?>
		<br /> 
		<div class="row">
		<?php echo mezunGlobalLikes::likeButton($row->id, 'profil');?>
		</div>   
		<hr>
		
		<div align="center">
		<div class="btn btn-group-vertical">
		<?php echo $editimage;?> <?php echo $cropimage;?> <?php echo $deleteimage;?> <?php echo $msglink;?> <?php echo $istemlink;?> <?php echo $editlink;?> <?php echo $passlink;?> <?php echo $deletefriend;?>
		</div>
		</div>
		
		
		</div>
		
		</div>
		
		<div class="col-sm-7">
		
		<fieldset>
		<legend>Mesleki Bilgiler:</legend>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Branşı:</strong></div>
		<div class="col-sm-7"><?php echo $row->brans;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Ünvanı:</strong></div>
		<div class="col-sm-7"><?php echo $row->unvan;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Fakülteye Girişi:</strong></div>
		<div class="col-sm-7"><?php echo $row->byili;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Mezuniyet Yılı:</strong></div>
		<div class="col-sm-7"><?php echo $row->myili;?></div>
		</div>
		</div>
		
		<?php if ($row->okulno) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Okul Numarası:</strong></div>
		<div class="col-sm-7"><?php echo $row->okulno;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($can['Show'] && $row->work) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Çalıştığı Kurum:</strong></div>
		<div class="col-sm-7"><?php echo $row->work;?></div>
		</div>
		</div>
		<?php } ?>
		
		</fieldset>
		
		<fieldset>
		<legend>Kişisel Bilgiler:</legend>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Adı, Soyadı:</strong></div>
		<div class="col-sm-7"><?php echo $row->name;?></div>
		</div>
		</div>
		
		<?php if ($can['Show']) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Cinsiyet:</strong></div>
		<div class="col-sm-7"><?php echo $cinsiyet;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($can['Show']) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Doğum Tarihi:</strong></div>
		<div class="col-sm-7"><?php echo $row->dogumtarihi;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($can['Show']) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Doğum Yeri:</strong></div>
		<div class="col-sm-7"><?php echo $row->dogumyeri;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($can['Show']) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Yaşadığı Şehir:</strong></div>
		<div class="col-sm-7"><?php echo $row->sehiradi;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($can['Show'] && $row->phone) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Telefon Numarası:</strong></div>
		<div class="col-sm-7"><?php echo $row->phone;?></div>
		</div>
		</div>
		<?php } ?>
		
		
		</fieldset>
		
		<fieldset>
		<legend>Kullanıcı Bilgileri:</legend>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Kullanıcı Adı:</strong></div>
		<div class="col-sm-7"><?php echo $row->username;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Siteye Kayıt Tarihi:</strong></div>
		<div class="col-sm-7"><?php echo mezunGlobalHelper::timeformat($row->registerDate, true, true);?></div>
		</div>
		</div>
		
		<?php if ($can['Show']) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5"><strong>Siteye Son Gelişi:</strong></div>
		<div class="col-sm-7"><?php echo mezunGlobalHelper::timeformat($row->lastvisit, true, true);?></div>
		</div>
		</div>
		<?php } ?>
		
		</fieldset>
		
		</div>
		
		</div>
		</div>
		</div>
			
		<!-- Profil Resmi Değiştirme -->
		<div id="imagechange" style="display: none;" title="Profil Resmi Değiştir"> 
		<div class="text-info">* Resminizin uzantısı jpg, jpeg, gif, png olmak zorundadır.</div>
	   <div class="text-warning">* Resminizin boyutu 2 Mb geçemez!</div>
		<form action="<?php echo sefLink('index.php?option=site&bolum=profil&task=saveimage');?>" method="post" enctype="multipart/form-data" role="form">
		<input type="file" name="image" id="image" class="btn btn-default" />
		<br />       
		<button type="submit" class="btn btn-primary">Profil Resmi Yap</button>
		</form>
		</div>
		<!-- Profil Resmi Değiştirme -->
		
		<!-- Parola Değiştirme -->
		<div id="passchange" style="display: none;" title="Parola Değiştir">
		<form action="<?php echo sefLink('index.php?option=site&bolum=profil&task=changepass');?>" method="post" role="form">
		<label for="password">Yeni Parola:</label>
		<input type="password" name="password" id="password" class="form-control" required />
		<br />
		<label for="password2">Yeni Parola Tekrar:</label>
		<input type="password" name="password2" id="password2" class="form-control" required />
		<br />
		<button type="submit" class="btn btn-primary">Parolayı Değiştir</button>
		</form>
		</div>
		<!-- Parola Değiştirme -->
		
		<!-- Mesaj Gönderme-->
		<div id="sendmessage" style="display: none;" title="Mesaj Gönder">
		<form action="<?php echo sefLink('index.php?option=site&bolum=mesaj&task=send');?>" method="post" role="form">
		<input type="text" name="baslik" class="form-control" placeholder="Mesajınızın başlığı" required>
		<textarea rows="5" name="text" class="form-control" placeholder="Mesajınızın içeriği" required></textarea>
		<button type="submit" class="btn btn-primary">Mesajı Gönder</button>
		<input type="hidden" name="aid" value="<?php echo $row->id;?>" />
		</form>
		</div>
		<!-- Mesaj Gönderme -->
		<?php
	}
}
