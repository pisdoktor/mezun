<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class KullaniciHTML {
	static function editKullanici($row) {
		?>
		<div id="module_header">Kullanici <?php echo $row->id ? 'Düzenle' : 'Ekle';?></div>
		<div id="module">
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
<form action="index.php" method="post" name="adminForm">
<table width="100%">
  <tr>
	<td width="30%">
	<strong>Kullanıcı İsim:</strong>
	</td>
	<td width="70%">
	<input type="text" name="name" class="inputbox" value="<?php echo $row->name;?>">
	</td>
  </tr>
  <tr>
	<td width="30%">
	<strong>Kullanıcı Adı:</strong>
	</td>
	<td width="70%">
	<input type="text" name="username" class="inputbox" value="<?php echo $row->username;?>">
	</td>
  </tr>
  <tr>
	<td width="30%">
	<strong>Okul Numarası:</strong>
	</td>
	<td width="70%">
	<input type="text" name="okulno" class="inputbox" value="<?php echo $row->okulno;?>">
	</td>
  </tr>
  <tr>
	<td width="30%">
	<strong>E-posta Adresi:</strong>
	</td>
	<td width="70%">
	<input type="text" name="email" class="inputbox" value="<?php echo $row->email;?>">
	</td>
  </tr>
   <tr>
	<td width="30%">
	<strong>Parola:</strong>
	</td>
	<td width="70%">
	
	<input type="password" name="password" id="password" class="inputbox" value="">
	<a href="#" class="link-password" id="olustur">Parola Oluştur</a>
	<a href="#" class="link-password" id="confirm">Parolayı Kullan</a>
	<span id="random"></span>
	<span id="showpass"></span>
	<span class="error" style="display: none; background-color: red;">Parolalar uyuşmuyor!</span>
	</td>
  </tr>
	<tr>
	<td width="30%">
	<strong>Parola Tekrarı:</strong>
	</td>
	<td width="70%">
	<input type="password" name="confirm_password" id="confirm_password" class="inputbox" value="">
	</td>
  </tr>
  <tr>
  <td>
  
  </td>
  </tr>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="user" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />
<br />
</form>
</div>
<br />
<div align="right">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="button"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="button" />
</div>
<?php
}
	
	static function getKullaniciList($rows, $pageNav, $search) {
		?>
<form action="index.php" method="post" name="adminForm">
<div align="left" style="float:left;">
Kullanıcı Adı: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="text_area" onChange="document.adminForm.submit();" />
</div>
<div align="right">
<input type="button" name="button" value="Yeni Kullanıcı Ekle" onclick="javascript:submitbutton('add');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="button" /> 
<input type="button" name="button" value="Seçileni Blokla" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı pasif etmek istediğinize emin misiniz?')){ submitbutton('block');}" class="button" /> 
<input type="button" name="button" value="Seçilenin Blok Kaldır" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı aktif etmek istediğinize emin misiniz?')){ submitbutton('unblock');}" class="button" />
</div>

<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</th>
<th width="10%">
Kullanıcı
</th>
<th width="10%">
Kullanıcı Adı
</th>
<th width="15%">
E-posta
</th>
<th width="10%">
Bulunduğu Şehir
</th>
<th width="10%">
Branş
</th>
<th width="10%">
Üyelik Durumu
</th>
</tr>
</table>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$checked = mosHTML::idBox( $i, $row->id );

$blok = $row->activated ? 'Aktif' : 'Pasif';
?>
<div id="detail<?php echo $row->id;?>">
<table width="100%" border="0" class="veriitem<?php echo $t;?>">
<tr>
<td width="5%">
<center>
<?php echo $pageNav->rowNumber( $i ); ?>
</center>
</td>
<td width="1%">
<center>
<?php echo $checked;?>
</center>
</td>
<td width="10%">
<center>
<a href="index.php?option=admin&bolum=user&task=editx&id=<?php echo $row->id;?>">
<?php echo $row->name;?>
</a>
</center>
</td>
<td width="10%">
<center>
<?php echo $row->username;?>
</center>
</td>
<td width="15%">
<center>
<?php echo $row->email;?>
</center>
</td>
<td width="10%">
<center>
<?php echo $row->sehir;?>
</center>
</td>
<td width="10%">
<center>
<?php echo $row->brans;?>
</center>
</td>
<td width="10%">
<center>
<?php echo $blok;?>
</center>
</td>
</tr>
</table>
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
<div align="right">
<input type="button" name="button" value="Yeni Kullanıcı Ekle" onclick="javascript:submitbutton('add');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="button" />
<input type="button" name="button" value="Seçileni Blokla" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı pasif etmek istediğinize emin misiniz?')){ submitbutton('block');}" class="button" /> 
<input type="button" name="button" value="Seçilenin Blok Kaldır" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kullanıcı(lar)ı aktif etmek istediğinize emin misiniz?')){ submitbutton('unblock');}" class="button" /> 
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

<?php
		
	}
}
