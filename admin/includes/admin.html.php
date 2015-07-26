<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunAdminMenuHTML {
	
	static function getOrderingList( $sql, $chop='30' ) {
		global $dbase;

	$order = array();
	$dbase->setQuery( $sql );
	if (!($orders = $dbase->loadObjectList())) {
		if ($dbase->getErrorNum()) {
			echo $dbase->stderr();
			return false;
		} else {
			$order[] = mezunHTML::makeOption( 1, 'en başta' );
			return $order;
		}
	}
	$order[] = mezunHTML::makeOption( 0, '0 en başta' );
	for ($i=0, $n=count( $orders ); $i < $n; $i++) {

		if (strlen($orders[$i]->text) > $chop) {
			$text = substr($orders[$i]->text,0,$chop)."...";
		} else {
			$text = $orders[$i]->text;
		}

		$order[] = mezunHTML::makeOption( $orders[$i]->value, $orders[$i]->value.' ('.$text.')' );
	}
	$order[] = mezunHTML::makeOption( $orders[$i-1]->value+1, ($orders[$i-1]->value+1).' en sonda' );

	return $order;
}
	/**
	* build the select list for Menu Ordering
	*/
	static function Ordering( &$row, $id ) {
		global $dbase;

		if ( $id ) {
			$query = "SELECT ordering AS value, name AS text"
			. "\n FROM #__menu"
			. "\n WHERE parent = " . (int) $row->parent
			. "\n AND access = ".$row->access
			. "\n ORDER BY ordering"
			;
			$order = mezunAdminMenuHTML::getOrderingList( $query );
			$ordering = mezunHTML::selectList( $order, 'ordering', 'size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" /> En Başta';
		}
		return $ordering;
	}
	
	static function BlockOrdering( &$row, $id ) {
		global $dbase;

		if ( $id ) {
			$query = "SELECT ordering AS value, title AS text"
			. "\n FROM #__blocks"
			. "\n WHERE position = ".$dbase->Quote($row->position)
			. "\n ORDER BY ordering"
			;
			$order = mezunAdminMenuHTML::getOrderingList( $query );
			$ordering = mezunHTML::selectList( $order, 'ordering', 'size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" /> En Başta';
		}
		return $ordering;
	}
	/**
	* build the select list for access level
	*/
	static function Access( &$row ) {
		$groups = array();
		$groups[] = mezunHTML::makeOption('0', 'Ziyaretçi');
		$groups[] = mezunHTML::makeOption('1', 'Üye');
		$groups[] = mezunHTML::makeOption('2', 'Admin');
		
		$access = mezunHTML::selectList( $groups, 'access', 'size="3"', 'value', 'text', intval( $row->access ) );

		return $access;
	}
	/**
	* build the select list for parent item
	*/
	static function Parent( &$row ) {
		global $dbase;

		$id = '';
		if ( $row->id ) {
			$id = "\n WHERE id != " . (int) $row->id;
		}

		// get a list of the menu items
		// excluding the current menu item and its child elements
		$query = "SELECT m.*"
		. "\n FROM #__menu m"
		. $id
		. "\n ORDER BY parent, ordering"
		;
		$dbase->setQuery( $query );
		$mitems = $dbase->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ( $mitems ) {
			// first pass - collect children
			foreach ( $mitems as $v ) {
				$pt     = $v->parent;
				$list     = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = treeRecurse( 0, '', array(), $children, 20, 0, 0 );

		// assemble menu items to the array
		$mitems     = array();
		$mitems[]     = mezunHTML::makeOption( '0', 'En Üst' );

		foreach ( $list as $item ) {
			$mitems[] = mezunHTML::makeOption( $item->id, '&nbsp;&nbsp;&nbsp;'. $item->treename );
		}

		$output = mezunHTML::selectList( $mitems, 'parent', 'size="10"', 'value', 'text', $row->parent );

		return $output;
	}
	/**
	* build a radio button option for published state
	*/
	static function Published( &$row ) {
		$published = mezunHTML::yesnoRadioList( 'published', '', $row->published );
		return $published;
	}
	
	static function ShowTitle( &$row ) {
		$showtitle = mezunHTML::yesnoRadioList( 'showtitle', '', $row->showtitle );
		return $showtitle;
	}
	
	/**
	* build a radio button option for published state
	*/
	static function MenuType( &$row ) {
		$type[] = mezunHTML::makeOption('site', 'Site');
		$type[] = mezunHTML::makeOption('admin', 'Yönetim');
		
		$mtype = mezunHTML::selectList($type, 'menu_type', '', 'value', 'text', $row->menu_type);
		return $mtype;
	}
	/**
	* build the select list for Ordering of a specified Table
	*/
	static function SpecificOrdering( &$row, $id, $query, $neworder=0 ) {
		global $dbase;

		if ( $neworder ) {
			$text = 'İlk Önce';
		} else {
			$text = 'En Sonra';
		}

		if ( $id ) {
			$order = mezunAdminMenuHTML::getOrderingList( $query );
			$ordering = mezunHTML::selectList( $order, 'ordering', 'size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text;
		}
		return $ordering;
	}
	
	static function BlockMenu($row) {
		global $dbase;
		
		//bölümleri alalım
		$dbase->setQuery("SELECT name FROM #__modules");
		$modules = $dbase->loadObjectList();
		
		foreach($modules as $module) {
			$m[] = mezunHTML::makeOption($module->name, $module->name);
		}
		
		return $list['modules'] = mezunHTML::selectList($m, 'block_menus[]', 'multiple size="10"', 'value', 'text', $row->block_menus);
	}
}