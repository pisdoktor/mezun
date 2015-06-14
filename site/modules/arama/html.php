<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Search {
	static function Form($list, $reg) {
		?>
		<form action="index.php" method="get" name="login" id="adminForm">
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
<input type="hidden" name="option" value="site">
<input type="hidden" name="bolum" value="arama">
<input type="hidden" name="task" value="search">
<input type="hidden" name="limit" value="10">
<input type="hidden" name="limitstart" value="0">

<input type="submit" name="submit" value="ARAMAYI BAŞLAT!" class="button">
</form>
<?php
	}
	
	static function Results($rows, $pageNav, $requri) {
		?>
		<form action="index.php" method="post" name="adminForm">
		
		</form>
		<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?'.$requri;
echo $pageNav->writeLimitPageLink($link);
?>
</div>
</div>
		<?php		
	}
}
