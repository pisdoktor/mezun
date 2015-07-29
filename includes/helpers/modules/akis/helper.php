<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAkisHelper {
	
	static function getSiteAkis($limitstart=0, $limit=5, $all=true) {
		global $dbase, $my;
		
		//tüm üyelerin mi yoksa sadece arkadaşlarımın mı paylaşımlarını göreyim? 
		if (!$all) {
		mimport('helpers.modules.arkadas.helper');
		//arkadaşları alalım
		$friends = mezunArkadasHelper::getMyFriends();
		//sorguya uygun hale getirelim
		$users = implode(', ', $friends);
		}
		
		$query = "SELECT a.*, u.name, u.image, u.id as userid FROM #__akis AS a"
		. "\n LEFT JOIN #__users AS u ON u.id=a.userid "
		. ($all ? "":"\n WHERE a.userid IN (".$users.") OR a.userid=".$dbase->Quote($my->id))
		. "\n ORDER BY a.tarih DESC LIMIT ".$limitstart.", ".$limit
		;
		$dbase->setQuery($query);
		
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
	
	static function getRow($row) {
		
		mimport('global.likes');
		?>
		<div class="row">
			<div class="col-sm-2">
			<a href="index.php?option=site&bolum=profil&task=show&id=<?php echo $row->userid;?>">
			<?php echo $row->image;?>
			</a>
			</div>
			  
			<div class="col-sm-10">
				<div class="row">
				<?php echo mezunAkisHelper::getAkisTime($row->tarih);?>
				</div>
			  
				<div class="row">
				<?php echo $row->name;?>: <?php echo $row->text;?>
				</div>
					  
			</div>
		 </div>
		 
		 <div align="right">
		 <?php echo mezunGlobalLikes::likeButton($row->id, 'akis');?>
		 </div>
		 <hr>
		<?php
	}
	
	static function getAkisTime($time) {
		
		$stime = strtotime($time);
		
		$clock = FormatDate($time, "%H:%M");
		
		$difference = time()-$stime;

		$second = 1;
		$minute = 60*$second;
		$hour   = 60*$minute;
		$day    = 24*$hour;
		$week   = 7*$day;
		$month  = 4*$week;
		
		$ans["month"]  = floor($difference/$month);
		$ans["week"]   = floor($difference/$week);
		$ans["day"]    = floor($difference/$day);
		$ans["hour"]   = floor(($difference%$day)/$hour);
		$ans["minute"] = floor((($difference%$day)%$hour)/$minute);
		$ans["second"] = floor(((($difference%$day)%$hour)%$minute)/$second);

		$html = '';
		
		if (!$difference) {
			$html.= '1 sn önce';
		} else 
		
		if ($ans["month"]) {
			$html.= $ans["month"]." ay önce";
			$html.= ' Saat:'.$clock;
		} else
		
		if ($ans["week"]) {
			$html.= $ans["week"]." hafta önce";
			$html.= ' Saat:'.$clock;
		} else 

		if ($ans["day"]) {
			$html.= $ans["day"]. " gün önce";
			$html.= ' Saat:'.$clock;
		} else 

		if ($ans["hour"]) {
			$html.= $ans["hour"]. " saat önce";
		} else 

		if ($ans["minute"]) {
			$html.= $ans["minute"]. " dakika önce";
		} else 

		if ($ans["second"]) {
			$html.= $ans["second"]. " saniye önce";
		}

		return $html;
	}
	
}