<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForumHelper {
	
	static function canDeleteMessage($id) {
		global $dbase, $my;
		
		mimport('tables.forummessage');
		
		$can = false;
		//admine can feda...
		if ($my->id == 1) {
			$can = true;
		}
		$row = new mezunForummessage($dbase);
		$row->load($id);
		
		if ($row->ID_MEMBER == $my->id) {
			$can = true;
		}
		
		return $can;
	}
	
	static function canEditMessage($id) {
		global $dbase, $my;
		
		mimport('tables.forummessage');
		
		$can = false;
		//admine can feda...
		if ($my->id == 1) {
			$can = true;
		}
		$row = new mezunForummessage($dbase);
		$row->load($id);
		
		if ($row->ID_MEMBER == $my->id) {
			$can = true;
		}
		
		return $can;		
	}
	
	static function canLockTopic($topicid) {
		global $dbase, $my;
		
		mimport('tables.forumtopic');
		mimport('tables.forummessage');
		
		$can = false;
		//kullanıcı id:1 ise admindir...yetkisi var
		if ($my->id == 1) {
			$can = true;
		}
		
		/*
		$row = new mezunForumtopic($dbase);
		$row->load($topicid);
		
		$msg = new mezunForummessage($dbase);
		$msg->load($row->ID_FIRST_MSG);
		
		if ($msg->ID_MEMBER == $my->id) {
			$can = true;
		}
		*/
		return $can;
		
	}
	
	static function canStickyTopic($topicid) {
		global $dbase, $my;
		
		mimport('tables.forumtopic');
		mimport('tables.forummessage');
		
		$can = false;
		//kullanıcı id:1 ise admindir...yetkisi var
		if ($my->id == 1) {
			$can = true;
		}
		/*
		$row = new mezunForumtopic($dbase);
		$row->load($topicid);
		
		$msg = new mezunForummessage($dbase);
		$msg->load($row->ID_FIRST_MSG);
		
		if ($msg->ID_MEMBER == $my->id) {
			$can = true;
		}
		*/
		return $can;
	}
	
	static function canDeleteTopic($topicid) {
		global $dbase, $my;
		
		mimport('tables.forumtopic');
		mimport('tables.forummessage');
		
		$can = false;
		//kullanıcı id:1 ise admindir...yetkisi var
		if ($my->id == 1) {
			$can = true;
		}
		/*
		$row = new mezunForumtopic($dbase);
		$row->load($topicid);
		
		$msg = new mezunForummessage($dbase);
		$msg->load($row->ID_FIRST_MSG);
		
		if ($msg->ID_MEMBER == $my->id) {
			$can = true;
		}
		*/
		return $can;
	}
	
	static function makesafeHTML($text) {
		
		$text = htmlentities($text);
		
		return $text;
	}
	
	static function makeHTML($text) {
		
		$text = html_entity_decode($text);
		
		return $text;
	}
	/**
	* Forumda bulunan kullanıcıları getiren fonksiyon
	* 
	*/
	static function getForumUsers() {
		global $dbase;
		
		$query = "SELECT s.userid, s.username, u.name FROM #__sessions AS s
		LEFT JOIN #__users AS u ON u.id=s.userid
		 WHERE s.nerede='forum' AND s.userid>0 AND s.access_type='site'";
		
		$dbase->setQuery($query);
		
		$rows = $dbase->loadObjectList();
		
		$list = array();
		
		foreach ($rows as $row) {
			$list[] = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->userid).'">'.$row->name.'</a>';
		}
		
		return $list;
		
	}
	
	// Get all parent boards (requires first parent as parameter)
	static function getBoardParents($id_parent) {
		global $dbase;

		$boards = array();

		// Loop while the parent is non-zero.
		while ($id_parent != 0)    {
			$dbase->setQuery("SELECT ID_PARENT FROM #__forum_boards WHERE ID_BOARD = $id_parent");
			$id_parent = $dbase->loadResult();
			$boards[] = $dbase->loadResult();
		}
		return $boards;
	}
	
	//Forum yönlendirme 
	static function forumBreadCrumb($board_info) {
		global $dbase;
	
		$node = array();
	
		$node[] = '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id='.$board_info->ID_BOARD).'">'.$board_info->name.'</a>';
	
		foreach ($board_info->parent_boards as $parent) {
			if ($parent == 0) {
				$node[] = '<a href="'.sefLink('index.php?option=site&bolum=forum#cat'.$board_info->ID_CAT).'">'.$board_info->catname.'</a>';
				$node[] = '<a href="'.sefLink('index.php?option=site&bolum=forum').'">FORUM</a>';
			} else {
				$dbase->setQuery("SELECT ID_BOARD, name FROM #__forum_boards WHERE ID_BOARD=".$parent);
				$dbase->loadObject($row);
			
				$node[] = '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id='.$row->ID_BOARD).'">'.$row->name.'</a>';
			}
		}    
		return implode(' » ', array_reverse($node));
}

	//Forum sayfalandırma
	static function constructPageIndex($base_url, $total, $limitstart, $limit=10, $flexible_start = false) {
		// Save whether $limitstart was less than 0 or not.
		$limitstart_invalid = $limitstart < 0;

		// Make sure $limitstart is a proper variable - not less than 0.
		if ($limitstart_invalid)
			$limitstart = 0;
		// Not greater than the upper bound.
		elseif ($limitstart >= $total)
			$limitstart = max(0, (int) $total - (((int) $total % (int) $limit) == 0 ? $limit : ((int) $total % (int) $limit)));
		// And it has to be a multiple of $limit!
		else
			$limitstart = max(0, (int) $limitstart - ((int) $limitstart % (int) $limit));

		$base_link = '<a class="navPages" href="'.sefLink(($flexible_start ? $base_url : strtr($base_url, array('%' => '%%')) . '&limitstart=%d&limit='.$limit)).'">%s</a> ';

		// Compact pages is off or on?
		if (!compactTopicPagesEnable) {
			// Show the left arrow.
			$pageindex = $limitstart == 0 ? ' ' : sprintf($base_link, $limitstart - $limit, '&#171;');

			// Show all the pages.
			$display_page = 1;
			for ($counter = 0; $counter < $total; $counter += $limit)
				$pageindex .= $limitstart == $counter && !$limitstart_invalid ? '<b>' . $display_page++ . '</b> ' : sprintf($base_link, $counter, $display_page++);

			// Show the right arrow.
			$display_page = ($limitstart + $limit) > $total ? $total : ($limitstart + $limit);
			
			if ($limitstart != $counter - $total && !$limitstart_invalid)
				$pageindex .= $display_page > $counter - $limit ? ' ' : sprintf($base_link, $display_page, '&#187;');
		} else {
		// If they didn't enter an odd value, pretend they did.
		$PageContiguous = (int) (compactTopicPagesContiguous - (compactTopicPagesContiguous % 2)) / 2;

		// Show the first page. (>1< ... 6 7 [8] 9 10 ... 15)
		if ($limitstart > $limit * $PageContiguous)
			$pageindex = sprintf($base_link, 0, '1');
		else
			$pageindex = '';

		// Show the ... after the first page.  (1 >...< 6 7 [8] 9 10 ... 15)
		if ($limitstart > $limit * ($PageContiguous + 1))
			$pageindex .= '<b> ... </b>';

		// Show the pages before the current one. (1 ... >6 7< [8] 9 10 ... 15)
		for ($nCont = $PageContiguous; $nCont >= 1; $nCont--)
			if ($limitstart >= $limit * $nCont) {
				$tmpStart = $limitstart - $limit * $nCont;
				$pageindex.= sprintf($base_link, $tmpStart, $tmpStart / $limit + 1);
			}

		// Show the current page. (1 ... 6 7 >[8]< 9 10 ... 15)
		if (!$limitstart_invalid)
			$pageindex .= '[<b>' . ($limitstart / $limit + 1) . '</b>] ';
		else
			$pageindex .= sprintf($base_link, $limitstart, $limitstart / $limit + 1);

		// Show the pages after the current one... (1 ... 6 7 [8] >9 10< ... 15)
		$tmpMaxPages = (int) (($total - 1) / $limit) * $limit;
		for ($nCont = 1; $nCont <= $PageContiguous; $nCont++)
			if ($limitstart + $limit * $nCont <= $tmpMaxPages) {
				$tmpStart = $limitstart + $limit * $nCont;
				$pageindex .= sprintf($base_link, $tmpStart, $tmpStart / $limit + 1);
			}

		// Show the '...' part near the end. (1 ... 6 7 [8] 9 10 >...< 15)
		if ($limitstart + $limit * ($PageContiguous + 1) < $tmpMaxPages)
			$pageindex .= '<b> ... </b>';

		// Show the last number in the list. (1 ... 6 7 [8] 9 10 ... >15<)
		if ($limitstart + $limit * $PageContiguous < $tmpMaxPages)
			$pageindex .= sprintf($base_link, $tmpMaxPages, $tmpMaxPages / $limit + 1);
	}

	return $pageindex;
	}	
	
}