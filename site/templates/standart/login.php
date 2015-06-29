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
</head>
<body>
<?php
$validate = spoofValue(1);
?>
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
<?php
	if ($mosmsg) {
	echo '<div id="message" title="Uyarı">'.$mosmsg.'</div>';
	}
?>

<div class="col-sm-8">
<div class="panel panel-default">
<div class="panel-heading">HOŞGELDİNİZ!</div>
<div class="panel-body">
Eğer siz de bir <strong>9 Eylül Üniversitesi Tıp Fakültesi</strong> mezunu iseniz,

Bu siteye kayıt olarak;
<ul>
<li>Sizinle aynı dönem okumuş olan arkadaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı bölümde okumuş diğer meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde çalışmakta olan meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde doğmuş meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Arkadaşlarınızın size ulaşmasını sağlayabilirsiniz,</li>
<li>Arkadaşlarınızla iletişim kurabilir, mesajlaşabilirsiniz,</li>
<li>Forumda paylaşımda bulunabilir, arkadaşlarınızın paylaşımlarına cevap verebilirsiniz</li>
</ul>

<h4>Hemen şimdi <a href="index.php?option=register">Kayıt Ol!</a></h4>
</div>
</div>
</div>

<div class="col-sm-4">
<div class="panel panel-default">
<div class="panel-heading">ÜYE GİRİŞİ</div>
<div class="panel-body">
<form action="index.php" method="post" name="login" id="loginForm" role="form">

<div class="form-group">
<label class="sr-only" for="username">Kullanıcı Adı:</label>
<input name="username" id="username" type="text" class="form-control" placeholder="Kullanıcı adınızı yazın" required />
</div>

<div class="form-group">
<label class="sr-only" for="password">Parola:</label>
<input type="password" id="password" name="passwd" class="form-control" placeholder="Parolanızı yazın" required />
</div>

 <div class="form-group">
 <div class="checkbox">
 <label>
 <input type="checkbox" name="remember" id="remember" value="yes" /> Beni hatırla</label>
  </div>
  </div>

<div class="form-group">
<button type="submit" class="btn btn-primary">GİRİŞ YAP</button>
</div>   

<div class="form-group">
<a href="#" id="forgot">ŞİFREMİ UNUTTUM!</a>
</div>

<div class="form-group">
<a href="#" id="activ">HESAP AKTİVASYONU</a>
</div>





<input type="hidden" name="option" value="login" />
<input type="hidden" name="op2" value="login" />
<input type="hidden" name="return" value="index.php" />
<input type="hidden" name="force_session" value="1" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>
</div>
</div>


</div><!-- content -->

<div id="forgotpass">
<form action="index.php" method="post" role="form">
<span class="help-block">* Şifrenizi sıfırlamak için lütfen kayıtlı e-posta adresinizi yazın.</span>

<div class="form-group">
<div class="row">
<div class="col-sm-5">
<label for="email">E-posta Adresiniz:</label>
</div>
<div class="col-sm-7">
<input type="text" name="email" id="email" class="form-control" required />
</div> 
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-12">
<input type="submit" name="button" class="btn btn-info" value="PAROLAYI SIFIRLA" />
</div>
</div> 
</div>

<input type="hidden" name="option" value="forgot" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>

<div id="activation">
<form action="index.php" method="post" role="form">
<span class="help-block">* E-posta adresinize gönderilen aktivasyon kodunu giriniz.</span>

<div class="form-group">
<div class="row">
<div class="col-sm-5">
<label for="code">Aktivasyon Kodu:</label>
</div>
<div class="col-sm-7">
<input type="text" name="code" id="code" class="form-control" required />
</div> 
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-12">
<input type="submit" name="button" class="btn btn-warning" value="AKTİVE ET!" />
</div>
</div> 
</div>

<input type="hidden" name="option" value="activate" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>

<?php include(ABSPATH.'/includes/footer.php');?>

</div><!-- container -->
</body>
</html>