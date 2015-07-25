<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalHelper {	
	
	static function AkisTracker($text) {
		global $dbase, $my;
		
		mimport('tables.akis');
		
		$row = new mezunAkis($dbase);
		$row->userid = $my->id;
		$row->tarih = date('Y-m-d H:i:s');
		$row->text = $text;
		$row->store();
	}
	
	static function calculateAge($bday) {
		
		if ($bday) {
			$now = mezunGlobalHelper::time_stamp();
			list($day, $month, $year) = explode('-', $bday);
			$birth = mktime(0, 0, 0, $month, $day, $year);
		
			$diff = $now-$birth;
		
		return round($diff/(60*60*24*365));
		
		} else {
			return false;
		}
	}
	
	static function formButton($value, $onclick, $uyari=0) {
		$html = "";
		$html.= '<input type="button" name="button"';
		$html.= ' value="'.$value.'"';
		if ($uyari==1) {
			$html.= 'onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert(\'Lütfen listeden bir seçim yapın\'); } else {submitbutton(\''.$onclick.'\');}"';
		} elseif ($uyari==2) {
			$html.= 'onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert(\'Lütfen listeden bir seçim yapın\'); } else if (confirm(\'İşlemi onaylıyor musunuz?\')){ submitbutton(\''.$onclick.'\');}"';    
		} else {
			$html.= ' onclick="javascript:submitbutton(\''.$onclick.'\');"';
		}
		
			$html.= ' class="btn btn-default" />';
	
		return $html;
	}

	static function shortText($text, $len) {

		// It was already short enough!
		if (strlen($text) <= $len)
			return $text;

		// Shorten it by the length it was too long, and strip off junk from the end.
		return substr($text, 0, $len) . '...';
	}
	
	static function createMenu($id, $list, &$children, $maxlevel=9999, $level=0) {
	
		if (@$children[$id] && $level <= $maxlevel) {
			
			foreach ($children[$id] as $v) {
				
			$id = $v->id;
			
			$pt = $v->parent;
			$list[$id] = $v;
			$list[$id]->children = count( @$children[$id] );
			
			echo "\n<li";
			echo $v->children ? " class=has-sub":"";
			echo ">";
			
			echo "\n<a href=\"$v->link\"><span>".$v->name."</span></a>\n";
				
				if ($v->children) {
				echo "\n<ul>";
			
				echo mezunGlobalHelper::createMenu( $id, $list, $children, $maxlevel, $level+1);
				
				echo "\n</ul>";
				}
			
			echo "\n</li>";
			}   
		}	
	}
	
	static function siteMenu() {
		global $dbase, $my;
		
		if (!$my->id) {
			$where = "WHERE menu_type='site' AND access='0'";
		} else {
			if ($my->access_type == 'site') {
				if ($my->id == 1) {
					$where = "WHERE menu_type='site' AND (access='1' OR access='2')";
				} else {
					$where = "WHERE menu_type='site' AND access='1'";
				}    
			} else if ($my->access_type == 'admin') {
					$where = "WHERE menu_type='admin' AND access='2'";
			}
		}
		
		$dbase->setQuery("SELECT * FROM #__menu ".$where." AND published=1 ORDER BY parent ASC,  ordering ASC");
		$rows = $dbase->loadObjectList();
		
		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ($rows as $v ) {
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		
		//var_dump($children);
		
		$prepend = "<div id=\"cssmenu\">\n<ul>\n";
		$append  = "</ul>\n</div>\n";
		
		echo $prepend;
		
		echo "\n<li class=\"active\">\n<a href=\"".SITEURL."/index.php\"><span>Anasayfa</span></a>\n</li>\n";
		
		mezunGlobalHelper::createMenu( 0, array(), $children, 9 );
		
		echo $append;
}

	static function time_stamp($timestamp = null) {
	
	if ($timestamp === null) {
		$timestamp = time();
	} elseif ($timestamp == 0) {
		return 0;
	}

	return $timestamp + (OFFSET * 3600);
}

	static function timeformat($logTime, $show_today = true, $datetime=false) {
		
		$txt['days'] = array('Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi');
		$txt['days_short'] = array('Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt');
		// Months must start with 1 => 'January'. (or translated, of course.)
		$txt['months'] = array(1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık');
		$txt['months_short'] = array(1 => 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara');

		if ($datetime == true) {
			$logTime = strtotime($logTime);
		}
		// Offset the time.
		$time = $logTime + (OFFSET * 3600);

		// We can't have a negative date (on Windows, at least.)
		if ($time < 0) {
			$time = 0;
		}

		// Today and Yesterday?
		if (todayMod >= 1 && $show_today === true) {
		// Get the current time.
		$nowtime = mezunGlobalHelper::time_stamp();

		$then = @getdate($time);
		$now = @getdate($nowtime);

		// Try to make something of a time format string...
		$s = strpos(TIMEFORMAT, '%S') === false ? '' : ':%S';
		if (strpos(TIMEFORMAT, '%H') === false && strpos(TIMEFORMAT, '%T') === false) {
			$today_fmt = '%I:%M' . $s . ' %p';
		} else {
			$today_fmt = '%H:%M' . $s;
		}

		// Same day of the year, same year.... Today!
		if ($then['yday'] == $now['yday'] && $then['year'] == $now['year']) {
			return '<b>Bugün</b> ' . mezunGlobalHelper::timeformat($logTime, $today_fmt);
		}

		// Day-of-year is one less and same year, or it's the first of the year and that's the last of the year...
		if (todayMod == '2' && (($then['yday'] == $now['yday'] - 1 && $then['year'] == $now['year']) || ($now['yday'] == 0 && $then['year'] == $now['year'] - 1) && $then['mon'] == 12 && $then['mday'] == 31)) {
			return '<b>Dün</b> ' . mezunGlobalHelper::timeformat($logTime, $today_fmt);
		}
	}

	$str = !is_bool($show_today) ? $show_today : TIMEFORMAT;

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
}