<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Arkadas {
	static function getList($rows, $pageNav) {
		?>
		<h3>ARKADAŞLARIM</h3>
		<?php
		if (!$rows) {
	?>
	<div align="center">Hiç arkadaşınız Yok!</div>
	<div align="center">İsterseniz <a href="index.php?option=site&bolum=arama">üye arama</a> bölümünden arkadaşlarına ulaşabilirsiniz.</div>
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
$link = 'index.php?option=site&bolum=arkadas';
echo $pageNav->writeLimitPageLink($link);
?>
</div>
</div>
<?php
}        
	}
	
}
