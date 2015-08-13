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

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/site/templates/ege_light/css/style.css" />
<script src="<?php echo SITEURL;?>/site/templates/ege_light/js/script.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/site/templates/ege_light/css/cssmenu.css" />
<script src="<?php echo SITEURL;?>/site/templates/ege_light/js/cssmenu.js" type="text/javascript"></script>

<link rel="alternate" href="<?php echo SITEURL;?>" hreflang="tr" />
</head>
<body>
<!-- Container Başlangıç -->
<div id="container">

<!-- Header Başlangıç -->
<div id="header">
<div id="logo">
<img src="<?php echo SITEURL;?>/site/templates/ege_light/images/logo.png" border="0" alt="<?php echo SITEHEAD;?>" title="<?php echo SITEHEAD;?>" />
</div>
</div>
<!-- Header Bitiş -->

<!-- HeaderBar Başlangıç -->
<div id="header-bar" class="clearfix">
<div class="header">
<?php mezunGlobalHelper::siteMenu();?> 
</div>
</div>
<!-- HeaderBar Bitiş -->

<!-- Content Başlangıç -->
<div id="content" class="clearfix">
<div class="row">
<?php
	if ($mosmsg) {
	echo '<div id="message" title="Mesaj">'.$mosmsg.'</div>';
	}
?>

<?php if (CountBlocks('left')) {?>
<div class="col-sm-4">
<?php LoadBlocks('left');?>
</div>
<?php } ?>

<div class="col-sm-<?php echo CountBlocks('left') ? '8':'12';?>">
<?php 
if (CountBlocks('top')) { ?>

<?php  LoadBlocks('top');?>
 
<?php } ?>

<?php loadSiteModule();?>
</div>

</div>
</div>
<!-- Content Bitiş -->


<!-- Footer Başlangıç -->
<?php getFooter();?>
<!-- Footer Bitiş -->

</div>
<!-- Container Bitiş -->

<!-- ScrollTop -->
<a href="#" id="scroll-top"></a>
<!-- ScrollTop -->
</body>
</html>