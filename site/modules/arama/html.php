<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Search {
	
	static function Form($list, $reg) {
		?>
		<form action="index.php?option=site&bolum=arama&task=search" method="post" id="adminForm" role="form">
		<div class="panel panel-default">
		<div class="panel-heading">ÜYE ARAMA FORMU</div>
		<div class="panel-body">
		
		<fieldset>
		<legend>Mesleki Bilgiler:</legend>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="brans">Branşı:</label>
<div class="col-sm-4">
<?php echo $reg->selectBrans(0);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="unvan">Ünvanı:</label>
<div class="col-sm-4">
<?php echo $reg->selectUnvan(0);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="byili">Okula Başlangıç Yılı:</label>
<div class="col-sm-3">
<?php echo $reg->selectYil('byili', 0);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="myili">Okulu Bitiriş Yılı:</label>
<div class="col-sm-3">
<?php echo $reg->selectYil('myili', 0);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Şuanda Çalıştığı Kurum:</label>
<div class="col-sm-6">
<input name="work" id="work" type="text" class="form-control" placeholder="Çalıştığı kurumu yazın" />
</div>
</div>
</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Kişisel Bilgiler:</legend>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="name">Adı, Soyadı:</label>
<div class="col-sm-6">
<input name="name" id="name" type="text" class="form-control" placeholder="Üyenin adını ve soyadını yazın" />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="cinsiyet">Cinsiyeti:</label>
<div class="col-sm-3">
<?php echo $list['cinsiyet'];?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Doğum Yeri:</label>
<div class="col-sm-3">
<?php echo $reg->selectSehir('dogumyeri', 0);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Yaşadığı Şehir:</label>
<div class="col-sm-3">
<?php echo $reg->selectSehir('sehir', 0);?>
</div>
</div>
</div>

		</fieldset>
		
		<fieldset>
		<legend>Kullanıcı Bilgileri:</legend>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="username">Kullanıcı Adı:</label>
<div class="col-sm-6">
<input name="username" id="username" type="text" class="form-control" placeholder="Üyenin kullanıcı adını yazın" />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="image">Profil Resmi Olanlar:</label>
<div class="col-sm-3">
<input type="checkbox" id="image" name="image" value="1" class="checkbox">
</div>
</div>
</div>

		</fieldset>

		<fieldset>
		<legend>Eşleşme:</legend>
		
		<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="search_type">Arama Seçeneği:</label>
<div class="col-sm-3">
<?php echo $list['type'];?>
</div>
</div>
</div>
		
		</fieldset>



<button type="submit" class="btn btn-primary">ARAMAYI BAŞLAT</button>
</div>
		<div class="panel-footer"></div>
		</div>
		</form>

<?php
	}
	
	static function Results($rows, $pageNav) {
		?>
		<div class="panel panel-primary">
		<div class="panel-heading">ARAMA SONUÇLARI</div>
		<div class="panel-body">
		<?php
if (!$rows) {
	?>
	<div align="center">Arama seçeneklerinize göre bir üye bulunamadı!</div>
	<div align="center">Ama isterseniz <a href="index.php?option=site&bolum=arama">tekrar arama</a> yapabilirsiniz.</div>
	<?php
	
} else {
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$image = $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png';

$profillink = '<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=profil&task=show&id='.$row->id.'">Profili Göster</a>';

$isteklink = (!mezunArkadasHelper::checkArkadaslik($row->id) && !mezunIstekHelper::checkIstek($row->id)) ? '<a class="btn btn-default btn-sm" href="index.php?option=site&bolum=istek&task=send&id='.$row->id.'">Arkadaşı Ekle</a>' : '';

$cinsiyet = $row->cinsiyet == 1 ? 'Erkek':'Bayan';
?>
<div class="row">

<div class="col-sm-3">
<img src="<?php echo $image;?>" class="img-thumbnail" title="<?php echo $row->name;?>" alt="<?php echo $row->name;?>" width="200" height="200" />
</div>

<div class="col-sm-7">

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Adı, Soyadı:</strong></div>
<div class="col-sm-8"><?php echo $row->name;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Siteye Son Geliş Tarihi:</strong></div>
<div class="col-sm-8"><?php echo mezunGlobalHelper::timeformat($row->lastvisit, true, true);?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Ünvanı:</strong></div>
<div class="col-sm-8"><?php echo $row->unvan;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Branşı:</strong></div>
<div class="col-sm-8"><?php echo $row->bransadi;?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Durum:</strong></div>
<div class="col-sm-8"><?php mezunOnlineHelper::isOnline($row->id, false);?></div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-4"><strong>Ortak Arkadaş:</strong></div>
<div class="col-sm-8"><?php mezunArkadasHelper::ortakArkadasCount($row->id,true);?> kişi</div>
</div>
</div>

</div>

<div class="col-sm-2">
<div class="form-group">
<div class="btn-group-vertical">
<?php echo $profillink;?> <?php echo $isteklink;?>
</div>
</div>
</div>

</div>
<?php if ($i < count($rows)-1) {
	echo '<hr>';
}
?>       
<?php
}
?>	
<?php
}	

?>
	</div>
		
		<div class="panel-footer">
		<div align="center">

<div class="row">
<div class="col-sm-12">
<?php echo $pageNav->writePagesCounter();?>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php 
$link = 'index.php?option=site&bolum=arama&task=search';
echo $pageNav->writePagesLinks($link);
?>
</div>
</div>

</div>
		</div>
		</div>  
<?php	
	}
	
}
