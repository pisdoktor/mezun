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

</script>
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

<div id="left-side">
<span class="loginmsg">
Eğer siz de bir <strong>9 Eylül Üniversitesi Tıp Fakültesi</strong> mezunu iseniz,

Bu siteye kayıt olarak;
<ul>
<li>Sizinle aynı dönem okumuş olan arkadaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı bölümde okumuş diğer meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde çalışmakta olan meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde doğmuş meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Arkadaşlarınızın size ulaşmasını sağlayabilirsiniz,</li>
<li>Arkadaşlarınızla iletişim kurabilir, mesajlaşabilirsiniz,</li>
</ul>

<h3>Hemen şimdi <a href="index.php?option=register">Kayıt Ol!</a></h3>
</span>
</div>

<div id="right-side">

<div id="login">
<h3>ÜYE GİRİŞİ</h3>
<form action="index.php" method="post" name="login" id="loginForm">

<div class="row">
<input name="username" id="username" type="text" class="inputbox" alt="username" placeholder="Kullanıcı adınızı yazın" size="15" required />
</div>

<div class="row">
<input type="password" id="password" name="passwd" class="inputbox" size="15" alt="password" placeholder="Parolanızı yazın" required />
</div>

<div class="row">
<input type="checkbox" name="remember" value="yes" /> Beni hatırla
</div> 

<div class="row">
<a href="#" id="forgot">ŞİFREMİ UNUTTUM!</a>
</div> 
<div class="row">
<a href="#" id="activ">HESAP AKTİVASYONU</a>
</div> 

<div class="row">
<input type="submit" name="button" class="button" value="GİRİŞ YAP" />
</div>  



<input type="hidden" name="option" value="login" />
<input type="hidden" name="op2" value="login" />
<input type="hidden" name="return" value="index.php" />
<input type="hidden" name="force_session" value="1" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div> 


</div>


</div><!-- content -->

<div id="forgotpass">
<form action="index.php" method="post" name="forgot" id="forgotForm">
* Şifrenizi sıfırlamak için lütfen kayıtlı e-posta adresinizi yazın.
<div class="row">
<label for="email">E-posta Adresiniz:</label><input type="text" name="email" id="email" class="inputbox" /> 
</div>
<div class="row">
<input type="submit" name="button" class="button" value="PAROLA SIFIRLA" />
</div> 

<input type="hidden" name="option" value="forgot" />
</form>
</div>

<div id="activation">
<form action="index.php" method="post" name="forgot" id="forgotForm">
* E-posta adresinize gönderilen aktivasyon kodunu giriniz.
<div class="row">
<label for="code">Aktivasyon Kodu:</label><input type="text" name="code" id="code" class="inputbox" /> 
</div>
<div class="row">
<input type="submit" name="button" class="button" value="AKTİVE ET!" />
</div> 

<input type="hidden" name="option" value="activate" />
</form>
</div>

<?php include(ABSPATH.'/includes/footer.php');?>

</div><!-- container -->
</body>
</html>