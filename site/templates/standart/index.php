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
<script type="text/javascript">
		tinymce.init({
			selector: "#textarea",
			plugins: "autolink emoticons advlist bbcode code wordcount visualchars visualblocks preview paste link",
			toolbar: "emoticons | visualchars | preview | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
			language: "tr"

		});
	
// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
	if ($(e.target).closest(".mce-window").length) {
		e.stopImmediatePropagation();
	}
});
</script>

</head>
<body>
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
</div><!-- content -->

<?php getFooter();?>

</div><!-- container -->
<a href="#" id="scroll-top"></a>
</body>
</html>