<div id="top-bar" class="clearfix">

<div class="header-container">

<div class="menu">
<?php mezunGlobalHelper::siteMenu();?> 
</div>

<div class="user-panel">
<div class="dropdown">
  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  
  <span class="glyphicon glyphicon-list"></span> Üye İşlemleri
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><a href="#" id="login">Giriş Yap</a></li>
	<li><a href="#" id="forgot">Şifremi Unuttum?</a></li>
	<?php if (USER_ACTIVATION) { ?>
	<li><a href="#" id="activ">Hesap Aktivasyonu</a></li>
	<?php } ?>  
  </ul>
</div>
</div>

</div>
</div>

<!-- Header Başlangıç -->
<div class="header-container">
<div id="header">
<div id="logo">
<h4><?php echo SITEHEAD;?></h4>
</div>
</div>
</div>
<!-- Header Bitiş -->
