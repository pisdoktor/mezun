<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php $mainframe->showHead();?>

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/admin/templates/ege_admindark/css/style.css" />
<script src="<?php echo SITEURL;?>/admin/templates/ege_admindark/js/script.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/admin/templates/ege_admindark/css/cssmenu.css" />
<script src="<?php echo SITEURL;?>/admin/templates/ege_admindark/js/cssmenu.js" type="text/javascript"></script>

</head>
<body>
<?php
	if ($mosmsg) {
		echo '<div id="message">'.$mosmsg.'</div>';
	}
?>
<div id="container">

<div id="header">
<div id="logo">
<img src="<?php echo SITEURL;?>/admin/templates/ege_admindark/images/logo.png" border="0" alt="<?php echo SITEHEAD;?>" title="<?php echo SITEHEAD;?>" />
</div>
</div>

<div id="header-bar" class="clearfix">
<div class="header">
<?php adminMenu();?> 
</div>
</div>

<div id="content" class="clearfix">
<?php loadAdminModule();?>
</div><!-- content -->

<?php getFooter();?>

</div><!-- container -->
</body>
</html>