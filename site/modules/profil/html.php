<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Profile {
	static function editProfile($row) {
		?>
<form action="index.php" method="post" name="login" id="adminForm">
<h3>PROFİL DÜZENLEME</h3>
<div class="row">
<label for="name">Adınız ve Soyadınız:</label>
<input name="name" id="name" type="text" class="inputbox" alt="name" value="<?php echo $row->name;?>" size="15" required />
</div>

<div class="row">
<label for="dogumtarihi">Doğum Tarihiniz:</label>
<input name="dogumtarihi" id="dogumtarihi" type="text" class="inputbox form-control bfh-phone" alt="dogumtarihi" value="<?php echo $row->dogumtarihi;?>" size="15" data-format="dd-dd-dddd" />
</div>

<div class="row">
<label for="sehir">Doğum Yeriniz:</label>
<?php echo $row->selectSehir('dogumyeri');?>
</div>

<div class="row">
<label for="email">E-posta Adresiniz:</label>
<input name="email" id="email" type="text" class="inputbox" alt="email" value="<?php echo $row->email;?>" size="15" required />
</div>

<div class="row">
<label for="phone">Telefon Numaranız:</label>
<input name="phone" id="phone" type="text" class="inputbox form-control bfh-phone" alt="phone" value="<?php echo $row->phone;?>" size="15" data-format="0 (ddd) ddd dd dd" />
</div>

<div class="row">
<label for="okulno">Okul Numaranız:</label>
<input name="okulno" id="okulno" type="text" class="inputbox" alt="okulno" value="<?php echo $row->okulno;?>" size="15" />
</div>

<div class="row">
<label for="work">Şuanda Çalıştığınız Kurum:</label>
<input name="work" id="work" type="text" class="inputbox" alt="work" value="<?php echo $row->work;?>" size="15" required />
</div>

<div class="row">
<label for="work">Branşınız:</label>
<?php echo $row->selectBrans();?>
</div>

<div class="row">
<label for="work">Ünvanınız:</label>
<?php echo $row->selectUnvan();?>
</div>

<div class="row">
<label for="sehir">Yaşadığınız Şehir:</label>
<?php echo $row->selectSehir('sehir');?>
</div>

<div class="row">
<label for="byili">Okula Başlangıç Yılınız:</label>
<?php echo $row->selectYil('byili');?>
</div>

<div class="row">
<label for="myili">Okulu Bitiriş Yılınız:</label>
<?php echo $row->selectYil('myili');?>
</div>
<br />
<div align="center">
<input type="submit" name="button" value="KAYDET!" class="button" />
</div>
<input type="hidden" name="option" value="site" />
<input type="hidden" name="bolum" value="profil" />
<input type="hidden" name="task" value="save" />
</form>
		<?php
	}
	static function getProfile($row, $edit, $msg, $istem) {
		
		$image = $row->image ? SITEURL.'/images/'.$row->image : SITEURL.'/images/noimage.png';
		$cinsiyet = $row->cinsiyet ? 'Erkek' : 'Bayan';
		$editlink = $edit ? '<a href="index.php?option=site&bolum=profil&task=edit">Profili Düzenle</a>' : '';
		$passlink = $edit ? '<a href="#" id="changepass">Parola Değiştir</a>' : '';
		$editimage = $edit ? '<a href="#" id="changeimg">Resmi Değiştir</a>' : '';
		$msglink = $msg ? '<a href="#" id="sendamsg">Mesaj Gönder</a>' : $istem ? 'Arkadaşlık isteği beklemede' : '<a href="index.php?option=site&bolum=istek&task=send&id='.$row->id.'">Arkadaşlık İsteği Gönder</a>';
		
		$msglink = $edit ? '' : $msglink;
		?>
		<div id="profile" class="clearfix">
		
		<div id="profile-photo">
		<img src="<?php echo $image;?>" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
		<div align="center"><?php echo $editimage;?></div>
		</div>
		
		<div id="basic-info">
		<table width="100%">
		<tr>
		<td>
		Adı:
		</td>
		<td>
		<?php echo $row->name;?>
		</td>
		</tr>
		<tr>
		<td>
		Kullanıcı Adı:
		</td>
		<td>
		<?php echo $row->username;?>
		</td>
		</tr>
		<tr>
		<td>
		Cinsiyet:
		</td>
		<td>
		<?php echo $cinsiyet;?>
		</td>
		</tr>
		<tr>
		<td>
		Doğum Tarihi:
		</td>
		<td>
		<?php echo $row->dogumtarihi;?>
		</td>
		</tr>
		<tr>
		<td>
		Doğum Yeri:
		</td>
		<td>
		<?php echo $row->dogumyeri;?>
		</td>
		</tr>
		<tr>
		<td>
		Yaşadığı Şehir:
		</td>
		<td>
		<?php echo $row->sehiradi;?>
		</td>
		</tr>
		<tr>
		<td>
		Siteye Kayıt Tarihi:
		</td>
		<td>
		<?php echo $row->registerDate;?>
		</td>
		</tr>
		<tr>
		<td>
		Siteye Son Giriş Tarihi:
		</td>
		<td>
		<?php echo $row->lastvisit;?>
		</td>
		</tr>
		<tr>
		<td>
		Şuanda Çalıştığı Kurum:
		</td>
		<td>
		<?php echo $row->work;?>
		</td>
		</tr>
		<tr>
		<td>Fakülteye Giriş Yılı:
		</td>
		<td>
		<?php echo $row->byili;?>
		</td>
		</tr>
		<tr>
		<td>
		Mezuniyet Yılı:
		</td>
		<td>
		<?php echo $row->myili;?>
		</td>
		</tr>
		<tr>
		<td colspan="2"><?php echo $editlink;?>  <?php echo $passlink;?> <?php echo $msglink;?></td>
		</tr>
		</table>
		</div>
		
		</div>
		
		<!-- Profil Resmi Değiştirme -->
		<div id="imagechange" style="display: none;">
		* Resminizin uzantısı jpg, jpeg, gif, png olmak zorundadır.<br />
		* Resminizin boyutu 2 Mb geçemez!
		<form action="index.php" method="post" enctype="multipart/form-data">
		<input type="file" name="image" id="image" />
		<input type="submit" value="Profil Resmi Yap" class="button">
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="profil" />
		<input type="hidden" name="task" value="saveimage" />
		</form>
		</div>
		<!-- Profil Resmi Değiştirme -->
		
		<!-- Parola Değiştirme -->
		<div id="passchange" style="display: none;">
		<form action="index.php" method="post">
		<label for="password">Yeni Parola:</label>
		<input type="password" name="password" id="password" class="inputbox" />
		<br />
		<label for="password2">Yeni Parola Tekrar:</label>
		<input type="password" name="password2" id="password2" class="inputbox" />
		<br />
		<input type="submit" value="Parolayı Değiştir" class="button">
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="profil" />
		<input type="hidden" name="task" value="changepass" />
		</form>
		</div>
		<!-- Parola Değiştirme -->
		
		<!-- Mesaj Gönderme-->
		<div id="sendmessage" style="display: none;">
		<form action="index.php" method="post">
		<input type="text" name="baslik" class="inputbox" placeholder="Mesajınızın başlığı">
		<textarea cols="50" rows="5" name="text" class="textbox" placeholder="Mesajınızın içeriği"></textarea>
		<input type="submit" value="Mesajı Gönder" class="button">
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
