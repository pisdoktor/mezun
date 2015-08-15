<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $mainframe->showHead();?>

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/site/templates/newdesign/css/style.css" />
<script src="<?php echo SITEURL;?>/site/templates/newdesign/js/script.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/site/templates/newdesign/css/cssmenu.css" />
<script src="<?php echo SITEURL;?>/site/templates/newdesign/js/cssmenu.js" type="text/javascript"></script>

<link rel="alternate" href="<?php echo SITEURL;?>" hreflang="tr" />
</head>
<body>
<?php
$validate = spoofValue(1);
?>

<div id="container">

<?php include_once('header.php');?>

<div id="content" class="clearfix">

<div style="float: left;">
<div class="panel panel-default">
<div class="panel-heading">HOŞGELDİNİZ!</div>
<div class="panel-body">
Eğer siz de bir <strong>9 Eylül Üniversitesi Tıp Fakültesi</strong> mezunu iseniz,

Bu siteye kayıt olarak;
<ul>
<li>Sizinle aynı dönem okumuş olan arkadaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı fakültede okumuş diğer meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde çalışmakta olan meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı şehirde doğmuş meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Sizinle aynı branştaki meslektaşlarınıza ulaşabilirsiniz,</li>
<li>Arkadaşlarınızın size ulaşmasını sağlayabilirsiniz,</li>
<li>Arkadaşlarınızla iletişim kurabilir, mesajlaşabilirsiniz,</li>
<li>Forumda paylaşımda bulunabilir, arkadaşlarınızın paylaşımlarına cevap verebilirsiniz,</li>
<li>Gruplar kurabilir, gruplara katılarak paylaşımda bulunabilirsiniz.</li>
</ul>

<h4>Hemen şimdi <a href="index.php?option=register">Kayıt Ol!</a></h4>
</div>
</div>

</div>
<div style="float: right;">
<img src="<?php echo SITEURL;?>/site/templates/newdesign/images/mezuniyet.jpg" width="380" height="290" class="img-thumbnail" />
</div>


</div>

<?php getFooter();?>

</div><!-- container -->

<?php include_once('loginform.php');?>

<?php include_once('forgotpass.php');?>

<?php if (USER_ACTIVATION) { 
include_once('activation.php');
} ?>

</body>
</html>