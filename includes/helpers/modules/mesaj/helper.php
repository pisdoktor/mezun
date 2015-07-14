<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunMesajHelper {
	
	function __construct() {
		
	}
	
	static function loadMailBox() {
		global $dbase;
	$msg = new mezunMesajlar($dbase);
	?>
	<div class="col-sm-12">
	<div class="panel panel-default">
  <div class="panel-heading">Mesaj Kutunuz</div>
  <div class="panel-body"><?php echo $msg->newMsg();?>
  </div>
  </div>
	</div>
	
	 <?php
}
	
}