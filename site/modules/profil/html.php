<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Profile {
	static function getProfile($row, $edit) {
		
		$image = $row->image ? SITEURL.'/images/'.$row->image : SITEURL.'/images/noimage.png';
		$cinsiyet = $row->cinsiyet ? 'Erkek' : 'Bayan';
		$editlink = $edit ? '<a href="index.php?option=site&bolum=profil&task=edit">Profili Düzenle</a>' : '';
		$passlink = $edit ? '<a href="#" id="changepass">Parola Değiştir</a>' : '';
		$editimage = $edit ? '<a href="#" id="changeimg">Resmi Değiştir</a>' : '';
		$msglink = $edit ? '' : '<a href="#" id="sendamsg">Mesaj Gönder</a>';
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
		<td colspan="2"><?php echo $editlink;?> | <?php echo $passlink;?></td>
		</tr>
		</table>
		</div>
		
		</div>
		
		<!-- Profil Resmi Değiştirme -->
		<div id="imagechange">
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
		<div id="passchange">
		<form action="index.php" method="post">
		<label for="password">Yeni Parola:</label>
		<input type="password" name="password" id="password" class="inputboc" />
		<br />
		<label for="password2">Yeni Parola Tekrar:</label>
		<input type="password" name="password2" id="password2" class="inputbox" />
		<input type="submit" value="Parolayı Değiştir" class="button">
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="profil" />
		<input type="hidden" name="task" value="changepass" />
		</form>
		</div>
		<!-- Parola Değiştirme -->
		<?php
	}
}
