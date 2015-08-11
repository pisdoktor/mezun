<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForumboard extends mezunTable {
	
	var $ID_BOARD     = null;
	
	var $ID_CAT = null;
	
	var $ID_PARENT = null;
	
	var $boardOrder = null;
	
	var $ID_LAST_MSG = null;
	
	var $ID_MSG_UPDATED = null;
	
	var $name  = null;
	
	var $aciklama = null;
	
	var $numTopics = null;
	
	var $numPosts = null;
	
	var $countPosts = null;
	
	function mezunForumboard( &$db ) {
		$this->mezunTable( '#__forum_boards', 'ID_BOARD', $db );
	}
	
	public function updateOrder( $where='' ) {
		$k = $this->_tbl_key;

		if (!array_key_exists( 'ordering', get_class_vars( strtolower(get_class( $this )) ) )) {
			$this->_error = "UYARI: ".strtolower(get_class( $this ))." sıralamayı desteklemiyor.";
			return false;
		}

		$order2 = '';


		$query = "SELECT $this->_tbl_key, boardOrder"
		. "\n FROM $this->_tbl"
		. ( $where ? "\n WHERE $where" : '' )
		. "\n ORDER BY boardOrder$order2 "
		;
		$this->_db->setQuery( $query );
		if (!($orders = $this->_db->loadObjectList())) {
			$this->_error = $this->_db->getErrorMsg();
			return false;
		}
		// first pass, compact the ordering numbers
		for ($i=0, $n=count( $orders ); $i < $n; $i++) {
			if ($orders[$i]->ordering >= 0) {
				$orders[$i]->ordering = $i+1;
			}
		}

		$shift = 0;
		$n=count( $orders );
		for ($i=0; $i < $n; $i++) {
			//echo "i=$i id=".$orders[$i]->$k." order=".$orders[$i]->ordering;
			if ($orders[$i]->$k == $this->$k) {
				// place 'this' record in the desired location
				$orders[$i]->ordering = min( $this->ordering, $n );
				$shift = 1;
			} else if ($orders[$i]->ordering >= $this->ordering && $this->ordering > 0) {
				$orders[$i]->ordering++;
			}
		}
	//echo '<pre>';print_r($orders);echo '</pre>';
		// compact once more until I can find a better algorithm
		for ($i=0, $n=count( $orders ); $i < $n; $i++) {
			if ($orders[$i]->ordering >= 0) {
				$orders[$i]->ordering = $i+1;
				$query = "UPDATE $this->_tbl"
				. "\n SET boardOrder = " . (int) $orders[$i]->ordering
				. "\n WHERE $k = " . $this->_db->Quote( $orders[$i]->$k )
				;
				$this->_db->setQuery( $query);
				$this->_db->query();
	//echo '<br />'.$this->_db->getQuery();
			}
		}

		// if we didn't reorder the current record, make it last
		if ($shift == 0) {
			$order = $n+1;
			$query = "UPDATE $this->_tbl"
			. "\n SET boardOrder = " . (int) $order
			. "\n WHERE $k = " . $this->_db->Quote( $this->$k )
			;
			$this->_db->setQuery( $query );
			$this->_db->query();
	//echo '<br />'.$this->_db->getQuery();
		}
		return true;
	}
}