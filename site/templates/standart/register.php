<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php showHead();?>
</head>
<body>
<script type="text/javascript">
$(function(){
	$('input[name=password2]').on('keyup', function(){
		
		var pwd = $('input[name=password]').val();
		var confirm_pwd = $(this).val();
	
		$('span.error').hide();
		
		if( pwd != confirm_pwd ){
			$('span.error').show();
		}
	});
});
</script>
<div id="container">

<div id="header">
<div id="logo">
<img src="<?php echo SITEURL;?>/site/templates/<?php echo SITETEMPLATE;?>/images/logo.png" border="0" alt="<?php echo SITEHEAD;?>" title="<?php echo SITEHEAD;?>" />
</div>
</div>

<div id="header-bar">
<?php siteMenu();?>
</div>

<div id="content" class="clearfix">

<div id="register-form">
<h3>ÜYE KAYIT FORMU</h3>
<form action="index.php" method="post" name="login" id="adminForm">
<div class="row">
<label for="name">Adınız ve Soyadınız:</label>
<input name="name" id="name" type="text" class="inputbox" alt="name" placeholder="Adınızı ve soyadınızı yazın" size="15" required /> * Lütfen adınızı ve soyadınızı doğru yazınız.
</div>

<div class="row">
<label for="username">Kullanıcı Adınız:</label>
<input name="username" id="username" type="text" class="inputbox" alt="username" placeholder="Kullanıcı adınızı yazın" size="15" required /> * Siteye girişte kullanacağınız kullanıcı adınızı yazınız.
</div>

<div class="row">
<label for="cinsiyet">Cinsiyetiniz:</label>
<?php echo $reg->userCinsiyet();?> * Cinsiyetinizi seçiniz. Kayıttan sonra değiştiremezsiniz.
</div>

<div class="row">
<label for="dogumtarihi">Doğum Tarihiniz:</label>
<input name="dogumtarihi" id="dogumtarihi" type="text" class="inputbox form-control bfh-phone" alt="dogumtarihi" placeholder="Doğum tarihinizi yazın" size="15" data-format="dd-dd-dddd" /> * Doğum tarihinizi GÜN-AY-YIL olarak yazınız.
</div>

<div class="row">
<label for="sehir">Doğum Yeriniz:</label>
<?php echo $reg->selectSehir('dogumyeri');?> * Doğduğunuz şehri seçin.
</div>

<div class="row">
<label for="password">Parolanız:</label>
<input name="password" id="password" type="password" class="inputbox" alt="password" placeholder="Parolanızı yazın" size="15" required /> * Siteye girişte kullanacağınız parolanızı yazınız.
</div>

<div class="row">
<label for="password2">Parolanız Tekrar:</label>
<input name="password2" id="password2" type="password" class="inputbox" alt="password2" placeholder="Parolanızı tekrar yazın" size="15" required />
<span class="error" style="display: none; background-color: red;"> * Parolalar uyuşmuyor!</span>  * Parolanızı tekrar yazınız.
</div>

<div class="row">
<label for="email">E-posta Adresiniz:</label>
<input name="email" id="email" type="text" class="inputbox" alt="email" placeholder="E-postanızı yazın" size="15" required /> * Size ulaşabilmemiz için geçerli bir e-posta adresi yazınız.
</div>

<div class="row">
<label for="phone">Telefon Numaranız:</label>
<input name="phone" id="phone" type="text" class="inputbox form-control bfh-phone" alt="phone" placeholder="Telefon numaranızı yazın" size="15" data-format="0 (ddd) ddd dd dd" /> * Size ulaşabilmemiz için lütfen bir telefon numarası yazınız.
</div>

<div class="row">
<label for="okulno">Okul Numaranız:</label>
<input name="okulno" id="okulno" type="text" class="inputbox" alt="okulno" placeholder="Okul numaranızı yazın" size="15" /> * Okul numaranızı hatırlıyorsanız lütfen yazınız.
</div>

<div class="row">
<label for="work">Şuanda Çalıştığınız Kurum:</label>
<input name="work" id="work" type="text" class="inputbox" alt="work" placeholder="Çalıştığınız kurumu yazın" size="15" required /> * Çalıştığınız kurumun adını yazınız.
</div>

<div class="row">
<label for="work">Branşınız:</label>
<?php echo $reg->selectBrans();?> * Branşınızı seçiniz.
</div>

<div class="row">
<label for="sehir">Yaşadığınız Şehir:</label>
<?php echo $reg->selectSehir('sehir');?> * Şuanda bulunduğunuz ili seçin.
</div>

<div class="row">
<label for="byili">Okula Başlangıç Yılınız:</label>
<?php echo $reg->selectYil('byili');?> * Okula başlama yılınızı seçin.
</div>

<div class="row">
<label for="myili">Okulu Bitiriş Yılınız:</label>
<?php echo $reg->selectYil('myili');?> * Okulu bitirme yılınızı seçin.
</div>
<br />
<div align="center">
<input type="submit" name="button" value="KAYIT OL!" class="button" />
</div>
<input type="hidden" name="option" value="reguser" />
</form>
</div>


</div><!-- content -->

<?php include(ABSPATH.'/includes/footer.php');?>

</div><!-- container -->
</body>
</html>