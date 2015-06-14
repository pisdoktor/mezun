<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
	static function inBox($rows) {
		$inbox = '<a href="index.php?option=site&bolum=mesaj&task=inbox">Gelen Kutusu</a>';
		$outbox = '<a href="index.php?option=site&bolum=mesaj&task=outbox">Giden Kutusu</a>';
		$new = '<a href="index.php?option=site&bolum=mesaj&task=new">Yeni Mesaj</a>';
		
		echo $inbox;
		echo $outbox;
		echo $new;
	}
}
