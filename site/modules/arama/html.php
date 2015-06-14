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
?>
<div class="search-user user-row<?php echo $t;?>">

<div>
<img src="<?php echo $image;?>" alt="<?php echo $row->name;?>" title="<?php echo $row->name;?>" width="200" height="200" />
</div>

<div><?php echo $row->unvan;?> <?php echo $row->name;?></div>
<div><?php echo $row->username;?></div>

<div><?php echo $row->work;?></div>
<div><?php echo $row->sehiradi;?></div>
<div><?php echo $row->dogumyeriadi;?></div>
<div><?php echo $row->bransadi;?></div>
<div><?php echo $row->byili;?></div>
<div><?php echo $row->myili;?></div>
<div><?php echo $row->lastvisit;?></div>
<div><?php echo $row->registerDate;?></div>
<div><?php echo $link;?></div>

</div>
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
