<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Forum {
// Format a time to make it look purdy.
static function timeformat($logTime, $show_today = true) {
	
	$time_format = '%d %B %Y, %H:%M:%S';
	$todayMod = 2;
	
	$txt['days'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$txt['days_short'] = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
// Months must start with 1 => 'January'. (or translated, of course.)
$txt['months'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$txt['months_titles'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$txt['months_short'] = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

	// Offset the time.
	$time = $logTime + (OFFSET * 3600);

	// We can't have a negative date (on Windows, at least.)
	if ($time < 0)
		$time = 0;

	// Today and Yesterday?
	if ($todayMod >= 1 && $show_today === true) {
		// Get the current time.
		$nowtime = Forum::forum_time();

		$then = @getdate($time);
		$now = @getdate($nowtime);

		// Try to make something of a time format string...
		$s = strpos($time_format, '%S') === false ? '' : ':%S';
		if (strpos($time_format, '%H') === false && strpos($time_format, '%T') === false) {
			$today_fmt = '%I:%M' . $s . ' %p';
		} else {
			$today_fmt = '%H:%M' . $s;
		}

		// Same day of the year, same year.... Today!
		if ($then['yday'] == $now['yday'] && $then['year'] == $now['year']) {
			return '<b>Bugün</b> ' . Forum::timeformat($logTime, $today_fmt);
		}

		// Day-of-year is one less and same year, or it's the first of the year and that's the last of the year...
		if ($todayMod == '2' && (($then['yday'] == $now['yday'] - 1 && $then['year'] == $now['year']) || ($now['yday'] == 0 && $then['year'] == $now['year'] - 1) && $then['mon'] == 12 && $then['mday'] == 31)) {
			return '<b>Dün</b> ' . Forum::timeformat($logTime, $today_fmt);
		}
	}

	$str = !is_bool($show_today) ? $show_today : $time_format;

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
}