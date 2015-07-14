<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunVersion {
	
	var $PRODUCT     = 'Mezun Sistemi';
	
	var $RELEASE     = '2.0';
	
	var $DEV_STATUS  = 'Beta';
	
	var $DEV_LEVEL   = '0';
	
	var $CODENAME    = 'Türkiye';
	
	var $RELDATE     = '14 Temmuz 2015';
	
	var $RELTIME     = '11:00';
	
	var $COPYRIGHT   = "Copyright © 2015 Soner Ekici. Tüm hakları saklıdır.";
	
	var $URL         = 'Coded by <a href="http://www.sonerekici.com" target="_blank">Soner Ekici</a>';

	function getLongVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' '
			. $this->DEV_STATUS
			.' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
			. $this->RELTIME;
	}

	function getShortVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL;
	}
	
	function getCopy() {
		return $this->COPYRIGHT;
	}
}