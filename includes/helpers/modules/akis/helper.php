<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAkisHelper {
	
	static function getRow($row) {
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