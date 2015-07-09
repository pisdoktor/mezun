<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class Version {
	var $PRODUCT     = 'Mezun Sistemi';
	
	var $RELEASE     = '1.0';
	
	var $DEV_STATUS  = 'Beta';
	
	var $DEV_LEVEL   = '1';
	
	var $CODENAME    = 'Ankara';
	
	var $RELDATE     = '06 Temmuz 2015';
	
	var $RELTIME     = '20:00';
	
	var $COPYRIGHT   = "Copyright © 2015 Soner Ekici. Tüm hakları saklıdır.";
	
	var $URL         = 'Coded by <a href="http://www.sonerekici.com" target="_blank">Soner Ekici</a>';

	/**
	 * @return string Long format version
	 */
	function getLongVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' '
			. $this->DEV_STATUS
			.' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
			. $this->RELTIME;
	}

	/**
	 * @return string Short version format
	 */
	function getShortVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL;
	}
	
	function getCopy() {
		return $this->COPYRIGHT;
	}
}