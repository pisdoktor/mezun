<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class KullaniciHTML {
	static function editKullanici($row) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Kullanıcı <?php echo $row->id ? 'Düzenle' : 'Ekle';?></h4>
	</div>
	<div class="panel-body">
		<script type="text/javascript">
		$(function(){
			$('input[name=confirm_password]').on('keyup', function(){
		var pwd = $('input[name=password]').val();
		var confirm_pwd = $(this).val();
		$('span.success').hide();
		$('span.error').hide();
		if( pwd != confirm_pwd ){
			$('span.error').show();
		}
		
	});
	
});

$.extend({
  password: function (length, special) {
	var iteration = 0;
	var password = "";
	var randomNumber;
	var keylist = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	if(special == undefined){
		var special = false;
	}
	while(iteration < length){
		iteration++;
		password += keylist.charAt(Math.floor(Math.random()*keylist.length))
	}
	return password;
  }
});

$(document).ready(function() {
 
	$('.link-password').click(function(e){
 
		// First check which link was clicked
		linkId = $(this).attr('id');
				
		if (linkId == 'olustur'){
			$('#random').empty();
			// If the generate link then create the password variable from the generator function
			password = $.password(10,true);
 
			// Empty the random tag then append the password and fade In
			$('#random').hide().append(password).fadeIn('slow');
			$('#showpass').hide();
 
			// Also fade in the confirm link
			$('#confirm').fadeIn('slow');
		} else {
			// If the confirm link is clicked then input the password into our form field
			$('#password').val(password);
			$('#confirm_password').val(password);
			$('#showpass').empty().append(password).fadeIn('slow');
			// remove password from the random tag
			$('#random').empty();
			// Hide the confirm link again
			$(this).hide();
		}
		e.preventDefault();
	});
});

</script>
 <script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
			// do field validation
			if (form.username.value == "") {
				alert( "You must provide a user login name." );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "You login name contains invalid characters or is too short." );
			} else if (trim(form.email.value) == "") {
				alert( "You must provide an e-mail address." );
			} else if (form.groupid.value == "") {
				alert( "You must assign user to a group." );
			} else if (trim(form.password.value) != "" && form.password.value != form.confirm_password.value){
				alert( "Password do not match." );
			} else if (form.kurumid.value == "") {
				alert( "Lütfen bir kurum seçin" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
<form action="index.php" method="post" name="adminForm" role="form">

<div class="form-group">
<div class="row">
<div class="col-sm-3">
<label for="name">Adı,Soyadı:</label>
</div>
<div class="col-sm-4">
<input type="text" id="name" name="name" class="form-control" value="<?php echo $row->name;?>" required>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-3">
<label for="username">Kullanıcı Adı:</label>
</div>
<div class="col-sm-4">
<input type="text" id="username" name="username" class="form-control" value="<?php echo $row->username;?>" required>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-3">
<label for="email">E-posta Adresi:</label>
</div>
<div class="col-sm-4">
<input type="text" id="email" name="email" class="form-control" value="<?php echo $row->email;?>" required>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<div class="col-sm-3">
<label for="password">Parola:</label>
</div>
<div class="col-sm-4">
<input type="password" name="password" id="password" class="form-control" value="">
</div>
<div class="col-sm-5">
<a href="#" class="link-password" id="olustur">Parola Oluştur</a>
<a href="#" class="link-password" id="confirm">Parolayı Kullan</a>
<span id="random"></span>
<span id="showpass"></span>
<span class="error" style="display: none; background-color: red;">Parolalar uyuşmuyor!</span>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<div class="col-sm-3">
<label for="email">Parola Tekrarı:</label>
</div>
<div class="col-sm-4">
<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="">
</div>
</div>
</div>
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
	<div class="panel-heading"><h4>Yönetim Paneli - Kullanıcılar</h4></div>
	<div class="panel-body">
	
<form action="index.php" method="post" name="adminForm" role="form">

<div align="right" style="float:right;">
<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="form-control" onChange="document.adminForm.submit();" placeholder="Kullanıcı adı yazın" />
</div>

<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Kullanıcı Ekle" onclick="javascript:submitbutton('add');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" />
<input type="button" name="button" value="Seçileni Blokla" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı pasif etmek istediğinize emin misiniz?')){ submitbutton('block');}" class="btn btn-info" /> 
<input type="button" name="button" value="Seçilenin Blok Kaldır" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı aktif etmek istediğinize emin misiniz?')){ submitbutton('unblock');}" class="btn btn-info" /> 
</div>
</div>

<div class="row">
<div class="col-sm-1">
<strong>SIRA</strong>
</div>
<div class="col-sm-1">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</div>
<div class="col-sm-2">
<strong>KULLANICI</strong>
</div>
<div class="col-sm-2">
<strong>KULLANICI ADI</strong>
</div>
<div class="col-sm-4">
<strong>E-POSTA</strong>
</div>
<div class="col-sm-2">
<strong>ÜYELİK DURUMU</strong>
</div>

</div>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$checked = mezunHTML::idBox( $i, $row->id );

$blok = $row->activated ? 'Aktif' : 'Pasif';
?>
<div class="row" id="detail<?php echo $row->id;?>">
<div class="col-sm-1">
<?php echo $pageNav->rowNumber( $i ); ?>
</div>
<div class="col-sm-1">
<?php echo $checked;?>
</div>
<div class="col-sm-2">
<a href="index.php?option=admin&bolum=user&task=editx&id=<?php echo $row->id;?>">
<?php echo $row->name;?>
</a>
</div>
<div class="col-sm-2">
<?php echo $row->username;?>
</div>
<div class="col-sm-4">
<?php echo $row->email;?>
</div>
<div class="col-sm-2">
<?php echo $blok;?>
</div>

</div>
<?php
$t = 1 - $t;
}
?>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="user" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />

<div class="form-group">
<div class="btn-group">
<input type="button" name="button" value="Yeni Kullanıcı Ekle" onclick="javascript:submitbutton('add');" class="btn btn-primary" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="btn btn-default" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="btn btn-warning" />
<input type="button" name="button" value="Seçileni Blokla" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı pasif etmek istediğinize emin misiniz?')){ submitbutton('block');}" class="btn btn-info" /> 
<input type="button" name="button" value="Seçilenin Blok Kaldır" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı aktif etmek istediğinize emin misiniz?')){ submitbutton('unblock');}" class="btn btn-info" /> 
</div>
</div>
</form>

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
