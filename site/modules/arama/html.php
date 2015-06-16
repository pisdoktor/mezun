<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Search {
	static function Form($list, $reg) {
		?>
		<form action="index.php?option=site&bolum=arama&task=search" method="post" name="login" id="adminForm">
		<h3>ÜYE ARAMA FORMU</h3>
<div class="row">
<label for="name">Adı Soyadı:</label>
<input name="name" id="name" type="text" class="inputbox" alt="name" placeholder="Üyenin adını soyadını yazın" size="15" />
</div>

<div class="row">
<label for="username">Kullanıcı Adı:</label>
<input name="username" id="username" type="text" class="inputbox" alt="username" placeholder="Üyenin kullanıcı adını yazın" size="15" />
</div>

<div class="row">
<label for="cinsiyet">Cinsiyeti:</label>
<?php echo $list['cinsiyet'];?>
</div>

<div class="row">
<label for="sehir">Doğum Yeri:</label>
<?php echo $reg->selectSehir('dogumyeri');?>
</div>

<div class="row">
<label for="work">Şuanda Çalıştığı Kurum:</label>
<input name="work" id="work" type="text" class="inputbox" alt="work" placeholder="Üyenin çalıştığı kurumu yazın" size="15" />
</div>

<div class="row">
<label for="work">Branşı:</label>
<?php echo $reg->selectBrans();?>
</div>

<div class="row">
<label for="work">Ünvanı:</label>
<?php echo $reg->selectUnvan();?>
</div>

<div class="row">
<label for="sehir">Yaşadığı Şehir:</label>
<?php echo $reg->selectSehir('sehir');?>
</div>

<div class="row">
<label for="byili">Okula Başlangıç Yılı:</label>
<?php echo $reg->selectYil('byili');?>
</div>

<div class="row">
<label for="myili">Okulu Bitiriş Yılı:</label>
<?php echo $reg->selectYil('myili');?>
</div>

<div class="row">
<label for="image">Profil Resmi Olanlar:</label>
<input type="checkbox" name="image" value="1" class="checkbox">
</div>

<div class="row">
<label for="myili">Arama Seçeneği:</label>
<?php echo $list['type'];?>
</div>
<input type="submit" name="submit" value="ARAMAYI BAŞLAT!" class="button">
</form>
<?php
	}
	
	static function Results($rows, $pageNav) {
if (!$rows) {
	?>
	<div align="center">Arama seçeneklerinize göre bir üye bulunamadı!</div>
	<div align="center">İsterseniz <a href="index.php?option=site&bolum=arama">tekrar arama</a> yapabilirsiniz.</div>
	<?php
	
} else {
?>
	<div class="search-results">
	<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$image = $row->image ? SITEURL.'/images/'.$row->image : SITEURL.'/images/noimage.png';
$link = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->id.'">Profili Göster</a>';
$cinsiyet = $row->cinsiyet ? 'Erkek':'Bayan';
?>
<div id="profile" class="clearfix">
		
<div id="profile-photo">
<img src="<?php echo $image;?>" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
</div>
		
<div id="basic-info">
<table width="100%">
  <tr>
	<td colspan="2"><?php echo $link;?></td>
	<td colspan="2"></td>
  </tr>
  <tr>
	<td><strong>Adı, Soyadı:</strong></td>
	<td><?php echo $row->name;?></td>
	<td><strong>Siteye Kayıt Tarihi:</strong></td>
	<td><?php echo $row->registerDate;?></td>
  </tr>
  <tr>
	<td><strong>Kullanıcı Adı:</strong></td>
	<td><?php echo $row->username;?></td>
	<td><strong>Siteye Son Gelişi:</strong></td>
	<td><?php echo $row->lastvisit;?></td>
  </tr>
  <tr>
	<td><strong>Cinsiyet:</strong></td>
	<td><?php echo $cinsiyet;?></td>
	<td><strong>Fakülteye Giriş Yılı:</strong></td>
	<td><?php echo $row->byili;?></td>
  </tr>
  <tr>
	<td><strong>Doğum Tarihi:</strong></td>
	<td><?php echo $row->dogumtarihi;?></td>
	<td><strong>Mezuniyet Tarihi:</strong></td>
	<td><?php echo $row->myili;?></td>
  </tr>
  <tr>
	<td><strong>Doğum Yeri:</strong></td>
	<td><?php echo $row->dogumyeriadi;?></td>
	<td><strong>Yaşadığı Şehir:</strong></td>
	<td><?php echo $row->sehiradi;?></td>
  </tr>
  <tr>
	<td><strong>Şuanda Çalıştığı Kurum:</strong></td>
	<td><?php echo $row->work;?></td>
	<td></td>
	<td></td>
  </tr>
</table>
	</div>
		
		</div>
		<br />
<?php
$t = 1 - $t;
}
?>	
	</div>
		
<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=site&bolum=arama&task=search';
echo $pageNav->writeLimitPageLink($link);
?>
</div>
</div>
<?php
}		
	}
}
