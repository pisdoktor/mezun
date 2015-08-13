<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalVersion {
	
	var $PRODUCT     = 'Mezun';
	
	var $RELEASE     = '2.0';
	
	var $DEV_STATUS  = 'Beta';
	
	var $DEV_LEVEL   = '2';
	
	var $CODENAME    = 'Şehit';
	
	var $RELDATE     = '2 Ağustos 2015';
	
	var $RELTIME     = '23:00';
	
	var $COPYRIGHT   = "Copyright © 2015 Soner Ekici. Tüm hakları saklıdır.";
	
	var $URL         = 'Coded by <a href="http://www.sonerekici.com" target="_blank">Soner Ekici</a>';
	
	function getLongVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' '
			. $this->DEV_STATUS
			.' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
			. $this->RELTIME;
	}

	function getShortVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL.' ['.$this->DEV_STATUS.']';
	}
	
	function getCopy() {
		return $this->COPYRIGHT;
	}
	
	function Product() {
		return $this->PRODUCT;
	}
	
	function Release() {
		return $this->RELEASE;
	}
	
	function DevelopmentLevel() {
		return $this->DEV_LEVEL;
	}
	
	function DevelopmentStatus() {
		return $this->DEV_STATUS;
	}
	function codeName() {
		return $this->CODENAME;
	}
	
	function ReleasedDate() {
		return $this->RELDATE.'-'.$this->RELTIME;
	}
}