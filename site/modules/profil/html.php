<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Profile {
	static function editProfile($row) {
		?>
<form action="index.php" method="post" id="adminForm" role="form">
<div class="panel panel-warning">
		<div class="panel-heading"><h4>PROFİL DÜZENLE</h4></div>
		<div class="panel-body">
		
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
<label class="control-label col-sm-4" for="work">Şuanda Çalıştığınız Kurum:</label>
<div class="col-sm-6">
<input name="work" id="work" type="text" class="form-control" value="<?php echo $row->work;?>" required />
</div>
</div>
</div>

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
<input name="phone" id="phone" type="text" class="form-control bfh-phone" value="<?php echo $row->phone;?>" data-format="d (ddd) ddd dd dd" required />
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
<div class="col-sm-12">
<button type="submit" class="btn btn-primary" />PROFİLİMİ GÜNCELLE!</button>
</div>
</div>
</div>
</div>
</div>
<input type="hidden" name="option" value="site" />
<input type="hidden" name="bolum" value="profil" />
<input type="hidden" name="task" value="save" />
</form>
		<?php
	}
	static function getProfile($row, $edit, $msg, $istem, $show) {
		
		$image = $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png';
		$cinsiyet = $row->cinsiyet ? 'Erkek' : 'Bayan';
		$editlink = $edit ? '<a class="btn btn-default" href="index.php?option=site&bolum=profil&task=edit">Profili Düzenle</a>' : '';
		$passlink = $edit ? '<a class="btn btn-default" href="#" id="changepass">Parola Değiştir</a>' : '';
		$editimage = $edit ? '<a class="btn btn-default" href="#" id="changeimg">Resmi Değiştir</a>' : '';
		$deleteimage = ($edit && $row->image) ? '<a class="btn btn-default" href="index.php?option=site&bolum=profil&task=deleteimage">Resmi Sil</a>' : ''; 
		$msglink = $msg ? '<a class="btn btn-default" href="#" id="sendamsg">Mesaj Gönder</a>' : '';
		$istemlink = !$istem ? '' : '<a class="btn btn-default" href="index.php?option=site&bolum=istek&task=send&id='.$row->id.'">Arkadaşlık İsteği Gönder</a>';		
		
		
		$head = $edit ? 'PROFİLİM' : 'PROFİL: '.$row->name;
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><h4><?php echo $head;?></h4>
		</div>
		<div class="panel-body"> 
		
		<div class="row">
		<div class="col-sm-3">
		

		
		<div class="figure">
		
		<img src="<?php echo $image;?>" class="img-thumbnail" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
		
		<div class="figcaption">
		<div align="center"><?php echo $editimage;?></div>
		<br />
		<div align="center"><?php echo $deleteimage;?></div>
		<br />
		<div align="center"><?php echo $msglink;?> <?php echo $istemlink;?> <?php echo $editlink;?> <?php echo $passlink;?></div>
		</div>
		
		</div>
		
		<?php if (!$edit) {?>
		<div align="center"><?php isOnline($row->id);?></div>
		<?php }?>	
		</div>
		
		<div class="col-sm-9">
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Adı, Soyadı:</strong></div>
		<div class="col-sm-8"><?php echo $row->name;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Kullanıcı Adı:</strong></div>
		<div class="col-sm-8"><?php echo $row->username;?></div>
		</div>
		</div>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Cinsiyet:</strong></div>
		<div class="col-sm-8"><?php echo $cinsiyet;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Doğum Tarihi:</strong></div>
		<div class="col-sm-8"><?php echo $row->dogumtarihi;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Doğum Yeri:</strong></div>
		<div class="col-sm-8"><?php echo $row->dogumyeri;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Yaşadığı Şehir:</strong></div>
		<div class="col-sm-8"><?php echo $row->sehiradi;?></div>
		</div>
		</div>
		<?php } ?>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Telefon Numarası:</strong></div>
		<div class="col-sm-8"><?php echo $row->phone;?></div>
		</div>
		</div>
		<?php } ?>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Siteye Kayıt Tarihi:</strong></div>
		<div class="col-sm-8"><?php echo Forum::timeformat($row->registerDate, true, true);?></div>
		</div>
		</div>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Siteye Son Geliş Tarihi:</strong></div>
		<div class="col-sm-8"><?php echo Forum::timeformat($row->lastvisit, true, true);?></div>
		</div>
		</div>
		<?php } ?>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Fakülteye Giriş Yılı:</strong></div>
		<div class="col-sm-8"><?php echo $row->byili;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Mezuniyet Yılı:</strong></div>
		<div class="col-sm-8"><?php echo $row->myili;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Okul Numarası:</strong></div>
		<div class="col-sm-8"><?php echo $row->okulno;?></div>
		</div>
		</div>
		
		<?php if ($show) {?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Şuanda Çalıştığı Kurum:</strong></div>
		<div class="col-sm-8"><?php echo $row->work;?></div>
		</div>
		</div>
		<?php } ?>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Branşı:</strong></div>
		<div class="col-sm-8"><?php echo $row->brans;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-4"><strong>Ünvanı:</strong></div>
		<div class="col-sm-8"><?php echo $row->unvan;?></div>
		</div>
		</div>
		
		</div>
		
		</div>
		</div>
		</div>
		
		
		<!-- Profil Resmi Değiştirme -->
		<div id="imagechange" style="display: none;" title="Profil Resmi Değiştir">
		<div class="text-info">* Resminizin uzantısı jpg, jpeg, gif, png olmak zorundadır.</div>
		<div class="text-warning">* Resminizin boyutu 2 Mb geçemez!</div>
		<form action="index.php" method="post" enctype="multipart/form-data" role="form">
		<input type="file" name="image" id="image" class="btn btn-default" />
		<br />
		<button type="submit" class="btn btn-primary">Profil Resmi Yap</button>
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="profil" />
		<input type="hidden" name="task" value="saveimage" />
		</form>
		</div>
		<!-- Profil Resmi Değiştirme -->
		
		<!-- Parola Değiştirme -->
		<div id="passchange" style="display: none;" title="Parola Değiştir">
		<form action="index.php" method="post" role="form">
		<label for="password">Yeni Parola:</label>
		<input type="password" name="password" id="password" class="form-control" required />
		<br />
		<label for="password2">Yeni Parola Tekrar:</label>
		<input type="password" name="password2" id="password2" class="form-control" required />
		<br />
		<button type="submit" class="btn btn-primary">Parolayı Değiştir</button>
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="profil" />
		<input type="hidden" name="task" value="changepass" />
		</form>
		</div>
		<!-- Parola Değiştirme -->
		
		<!-- Mesaj Gönderme-->
		<div id="sendmessage" style="display: none;" title="Mesaj Gönder">
		<form action="index.php" method="post" role="form">
		<input type="text" name="baslik" class="form-control" placeholder="Mesajınızın başlığı" required>
		<textarea rows="5" name="text" class="form-control" placeholder="Mesajınızın içeriği" required></textarea>
		<button type="submit" class="btn btn-primary">Mesajı Gönder</button>
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="mesaj" />
		<input type="hidden" name="task" value="send" />
		<input type="hidden" name="aid" value="<?php echo $row->id;?>" />
		</form>
		</div>
		<!-- Mesaj Gönderme -->
		<?php
	}
}
