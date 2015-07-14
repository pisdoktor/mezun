<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php showHead();?>
<link rel="alternate" href="<?php echo SITEURL;?>" hreflang="tr" />
</head>
<body>
<?php
$validate = spoofValue(1);
?>
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
	
	$('select[name=myili]').on('change', function(){
			
	var byili = $('select[name=byili]').val();
	var myili = $(this).val();
	
	$('span.yil').hide();
	
		if (byili >= myili) {
		$('span.yil').show();
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

<div id="header-bar" class="clearfix">
<div class="header">
<?php mezunGlobalHelper::siteMenu();?> 
</div>
</div>

<div id="content" class="clearfix">

<div class="panel panel-primary">
<div class="panel-heading">ÜYE KAYIT FORMU</div>
<div class="panel-body">
<form action="index.php?option=reguser" method="post" id="adminForm" role="form">

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="name">Adınız ve Soyadınız:</label>
<div class="col-sm-6">
<input name="name" id="name" type="text" class="form-control" placeholder="Adınızı ve soyadınızı yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="username">Kullanıcı Adınız:</label>
<div class="col-sm-6">
<input name="username" id="username" type="text" class="form-control" placeholder="Kullanıcı adınızı yazın" required />
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Doğum Yeriniz:</label>
<div class="col-sm-3">
<?php echo $reg->selectSehir('dogumyeri');?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="dogumtarihi">Doğum Tarihiniz:</label>
<div class="col-sm-3">
<input name="dogumtarihi" id="dogumtarihi" type="text" class="form-control bfh-phone" data-format="dd-dd-dddd" required />
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="cinsiyet">Cinsiyetiniz:</label>
<div class="col-sm-6">
<?php echo $reg->userCinsiyet(1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="sehir">Yaşadığınız Şehir:</label>
<div class="col-sm-3">
<?php echo $reg->selectSehir('sehir', 1);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="password">Parolanız:</label>
<div class="col-sm-4">
<input name="password" id="password" type="password" class="form-control" placeholder="Parolanızı yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="password2">Parolanız Tekrar:</label>
<div class="col-sm-4">
<input name="password2" id="password2" type="password" class="form-control" placeholder="Parolanızı tekrar yazın" required />
<div class="col-sm-6">
<span class="error" style="display: none; background-color: red;"> * Parolalar uyuşmuyor!</span>
</div>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="email">E-posta Adresiniz:</label>
<div class="col-sm-4">
<input name="email" id="email" type="text" class="form-control" placeholder="E-postanızı yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="phone">Telefon Numaranız:</label>
<div class="col-sm-3">
<input name="phone" id="phone" type="text" class="form-control bfh-phone" data-format="d (ddd) ddd dd dd" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="okulno">Okul Numaranız:</label>
<div class="col-sm-4">
<input name="okulno" id="okulno" type="text" class="form-control" placeholder="Okul numaranızı yazın" />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Şuanda Çalıştığınız Kurum:</label>
<div class="col-sm-6">
<input name="work" id="work" type="text" class="form-control" placeholder="Çalıştığınız kurumu yazın" required />
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Ünvanınız:</label>
<div class="col-sm-4">
<?php echo $reg->selectUnvan(1);?>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="work">Branşınız:</label>
<div class="col-sm-4">
<?php echo $reg->selectBrans(1);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="byili">Okula Başlangıç Yılınız:</label>
<div class="col-sm-3">
<?php echo $reg->selectYil('byili', 1);?>
</div>
<div class="col-sm-5">
<span class="yil" style="display: none; background-color: red;">Başlangıç yılı mezuniyet yılına eşit veya büyük olamaz!</span>
</div>
</div>
</div>

<div class="form-group">
<div class="row">
<label class="control-label col-sm-4" for="myili">Okulu Bitiriş Yılınız:</label>
<div class="col-sm-3">
<?php echo $reg->selectYil('myili', 1);?>
</div>
</div>
</div>


<div class="form-group">
<div class="row">
<div class="col-sm-12">
<button type="submit" class="btn btn-primary" />SİTEYE KAYIT OL!</button>
</div>
</div>
</div>
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>
</div>


</div><!-- content -->

<?php include(ABSPATH.'/includes/footer.php');?>

</div><!-- container -->
</body>
</html>