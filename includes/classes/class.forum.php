<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Forum {
// Format a time to make it look purdy.
static function timeformat($logTime, $show_today = true) {
		
	$txt['days'] = array('Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi');
$txt['days_short'] = array('Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt');
// Months must start with 1 => 'January'. (or translated, of course.)
$txt['months'] = array(1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık');
$txt['months_short'] = array(1 => 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara');

	// Offset the time.
	$time = $logTime + (OFFSET * 3600);

	// We can't have a negative date (on Windows, at least.)
	if ($time < 0)
		$time = 0;

	// Today and Yesterday?
	if (todayMod >= 1 && $show_today === true) {
		// Get the current time.
		$nowtime = Forum::forum_time();

		$then = @getdate($time);
		$now = @getdate($nowtime);

		// Try to make something of a time format string...
		$s = strpos(time_format, '%S') === false ? '' : ':%S';
		if (strpos(time_format, '%H') === false && strpos(time_format, '%T') === false) {
			$today_fmt = '%I:%M' . $s . ' %p';
		} else {
			$today_fmt = '%H:%M' . $s;
		}

		// Same day of the year, same year.... Today!
		if ($then['yday'] == $now['yday'] && $then['year'] == $now['year']) {
			return '<b>Bugün</b> ' . Forum::timeformat($logTime, $today_fmt);
		}

		// Day-of-year is one less and same year, or it's the first of the year and that's the last of the year...
		if (todayMod == '2' && (($then['yday'] == $now['yday'] - 1 && $then['year'] == $now['year']) || ($now['yday'] == 0 && $then['year'] == $now['year'] - 1) && $then['mon'] == 12 && $then['mday'] == 31)) {
			return '<b>Dün</b> ' . Forum::timeformat($logTime, $today_fmt);
		}
	}

	$str = !is_bool($show_today) ? $show_today : time_format;

	if (setlocale(LC_TIME, 'tr_TR')) {
		foreach (array('%a', '%A', '%b', '%B') as $token)
			if (strpos($str, $token) !== false)
				$str = str_replace($token, ucwords(strftime($token, $time)), $str);
	} else {
		// Do-it-yourself time localization.  Fun.
		foreach (array('%a' => 'days_short', '%A' => 'days', '%b' => 'months_short', '%B' => 'months') as $token => $text_label)
			if (strpos($str, $token) !== false)
				$str = str_replace($token, $txt[$text_label][(int) strftime($token === '%a' || $token === '%A' ? '%w' : '%m', $time)], $str);
		if (strpos($str, '%p'))
			$str = str_replace('%p', (strftime('%H', $time) < 12 ? 'am' : 'pm'), $str);
	}

	// Format any other characters..
	return strftime($str, $time);
}
	
// Shorten a subject + internationalization concerns.
static function shorten_subject($subject, $len) {

	// It was already short enough!
	if (strlen($subject) <= $len)
		return $subject;

	// Shorten it by the length it was too long, and strip off junk from the end.
	return substr($subject, 0, $len) . '...';
}

// The current time with offset.
static function forum_time($timestamp = null) {
	
	if ($timestamp === null) {
		$timestamp = time();
	} elseif ($timestamp == 0) {
		return 0;
	}

	return $timestamp + (OFFSET * 3600);
}

// Get all parent boards (requires first parent as parameter)
static function getBoardParents($id_parent) {
	global $dbase;

	$boards = array();

	// Loop while the parent is non-zero.
	while ($id_parent != 0)	{
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
	
	$node[] = '<a href="index.php?option=site&bolum=forum&task=board&id='.$board_info->ID_BOARD.'">'.$board_info->name.'</a>';
	
	foreach ($board_info->parent_boards as $parent) {
		if ($parent == 0) {
			$node[] = '<a href="index.php?option=site&bolum=forum#cat'.$board_info->ID_CAT.'">'.$board_info->catname.'</a>';
			$node[] = '<a href="index.php?option=site&bolum=forum">FORUM</a>';
			
		} else {
			$dbase->setQuery("SELECT ID_BOARD, name FROM #__forum_boards WHERE ID_BOARD=".$parent);
			$dbase->loadObject($row);
			
			$node[] = '<a href="index.php?option=site&bolum=forum&task=board&id='.$row->ID_BOARD.'">'.$row->name.'</a>';
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

	$base_link = '<a class="navPages" href="' . ($flexible_start ? $base_url : strtr($base_url, array('%' => '%%')) . '&limitstart=%d&limit='.$limit) . '">%s</a> ';

	// Compact pages is off or on?
	if (empty(compactTopicPagesEnable)) {
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
			if ($limitstart >= $limit * $nCont)
			{
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
			if ($limitstart + $limit * $nCont <= $tmpMaxPages)
			{
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