<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class KullaniciHTML {
	
	static function editKullanici($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Üye <?php echo $row->id ? 'Düzenle' : 'Ekle';?></h4>
	</div>
	<div class="panel-body">
<form action="index.php" method="post" name="adminForm" role="form">

<fieldset>
<legend>Üyelik Bilgileri</legend>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="username">Kullanıcı Adı:</label>
<div class="col-sm-6">
<input name="username" id="username" type="text" class="form-control" value="<?php echo $row->username;?>" placeholder="Kullanıcı adını yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="password">Parola:</label>
<div class="col-sm-4">
<input name="password" id="password" type="password" class="form-control" placeholder="Parola yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="password2">Parola Tekrar:</label>
<div class="col-sm-4">
<input name="password2" id="password2" type="password" class="form-control" placeholder="Parolayı tekrar yazın" required />
<div class="col-sm-6">
</div>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="email">E-posta Adresi:</label>
<div class="col-sm-4">
<input name="email" id="email" type="text" class="form-control" value="<?php echo $row->email;?>" placeholder="E-posta yazın" required />
</div>
</div>
</div>

</fieldset>

<fieldset>
<legend>Kişisel Bilgiler</legend>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="name">Ad ve Soyad:</label>
<div class="col-sm-6">
<input name="name" id="name" type="text" class="form-control" value="<?php echo $row->name;?>" placeholder="Adını ve soyadını yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Doğum Yeri:</label>
<div class="col-sm-3">
<?php echo $row->selectSehir('dogumyeri');?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="dogumtarihi">Doğum Tarihi:</label>
<div class="col-sm-3">
<input name="dogumtarihi" id="dogumtarihi" type="text" class="form-control bfh-phone" value="<?php echo $row->dogumtarihi;?>" data-format="dd-dd-dddd" placeholder="GG-AA-YYYY şeklinde" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="cinsiyet">Cinsiyet:</label>
<div class="col-sm-6">
<?php echo $row->userCinsiyet(1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Yaşadığı Şehir:</label>
<div class="col-sm-3">
<?php echo $row->selectSehir('sehir', 1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="phone">Telefon Numarası:</label>
<div class="col-sm-3">
<input name="phone" id="phone" type="text" class="form-control bfh-phone" value="<?php echo $row->phone;?>" data-format="d (ddd) ddd dd dd" placeholder="0 (000) 000 00 00 şeklinde" required />
</div>
</div>
</div>

</fieldset>

<fieldset>
<legend>Mesleki Bilgiler</legend>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="brans">Branş:</label>
<div class="col-sm-4">
<?php echo $row->selectBrans(1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="unvan">Ünvan:</label>
<div class="col-sm-4">
<?php echo $row->selectUnvan(1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="byili">Okula Başlangıç Yılı:</label>
<div class="col-sm-3">
<?php echo $row->selectYil('byili', 1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="myili">Okulu Bitiriş Yılı:</label>
<div class="col-sm-3">
<?php echo $row->selectYil('myili', 1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="okulno">Okul Numarası:</label>
<div class="col-sm-4">
<input name="okulno" id="okulno" type="text" class="form-control" value="<?php echo $row->okulno;?>" placeholder="Okul numarasını yazın" />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Şuanda Çalıştığı Kurum:</label>
<div class="col-sm-6">
<input name="work" id="work" type="text" class="form-control" value="<?php echo $row->work;?>" placeholder="Çalıştığı kurumu yazın" required />
</div>
</div>
</div>
</fieldset>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="user" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />

</form>
<br />
<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="btn btn-warning" />
</div>
</div>

</div>
</div>
<?php
}
	
	static function getKullaniciList($rows, $pageNav, $search) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Üye Yönetimi</h4></div>
	<div class="panel-body">

<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=user&task=new" class="btn btn-default btn-sm">Yeni Kullanıcı Ekle</a>
</div>
<div class="col-sm-4">
<form action="index.php?option=admin&bolum=user" method="post" name="adminForm" role="form">
<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="form-control" onChange="document.adminForm.submit();" placeholder="Kullanıcı adı yazın" />
</form>
</div>

</div>


<table class="table table-striped">
<thead>
<tr>
<th>SIRA</th>
<th>İŞLEM</th>
<th>İSİM</th>
<th>KULLANICI ADI</th>
<th>E-POSTA</th>
<th>DURUM</th>
</tr>
</thead>
<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$blok = itemState($row->activated);

$bloklink = $row->activated ? '<a href="index.php?option=admin&bolum=user&task=block&id='.$row->id.'">Blokla</a>':'<a href="index.php?option=admin&bolum=user&task=unblock&id='.$row->id.'">Aktifleştir</a>';

$editlink = '<a href="index.php?option=admin&bolum=user&task=edit&id='.$row->id.'">Düzenle</a>';

$deletelink = '<a href="index.php?option=admin&bolum=user&task=delete&id='.$row->id.'">Sil</a>';
?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
 <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  <span class="glyphicon glyphicon-cog"></span> 
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><?php echo $editlink;?></li>
	<li><?php echo $bloklink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $row->name;?>
</td>
<td>
<?php echo $row->username;?>
</td>
<td>
<?php echo $row->email;?>
</td>
<td>
<?php echo $blok;?>
</td>
</tr>
<?php
$t = 1 - $t;
}
?>
</tbody>
</table>
</div>

<div class="panel-footer">
<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=user';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>
</div>
</div>
<?php
		
	}
}
