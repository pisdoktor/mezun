<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalLikes {
	
	static function totalLikes($itemid, $nerede) {
		global $dbase;
		
		//Toplam beğeniyi alalım
		$dbase->setQuery("SELECT COUNT(userid) FROM #__likes WHERE itemid=".$dbase->Quote($itemid)." AND bolum=".$dbase->Quote($nerede));
		
		$total = $dbase->loadResult();
		
		return $total;
	}
	
	static function haveLike($itemid, $nerede) {
		global $dbase, $my;
		
		//Daha önceden beğenip beğenmediğine bakalım
		$dbase->setQuery("SELECT userid FROM #__likes WHERE itemid=".$dbase->Quote($itemid)." AND bolum=".$dbase->Quote($nerede)." AND userid=".$dbase->Quote($my->id));
		
		return $dbase->loadResult();
	}
	
	static function likeButton($itemid, $nerede) {
		global $dbase, $my;
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('#likebutton<?php echo $itemid;?>').click(function (event){
				
				$('#likebutton<?php echo $itemid;?>').attr('disabled', true);
				
				$.ajax({
					type    : 'POST',
					url     : $(this).attr('url'),
					dataType: 'json',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					$('#likebutton<?php echo $itemid;?>').removeAttr('disabled');
					$('#likebutton<?php echo $itemid;?>').attr("url", data['url']);
					$('#likebutton<?php echo $itemid;?>').html(data['button']);
					$('.item-<?php echo $itemid;?>-count').html(data['count']);
				});
				
				event.preventDefault();
			});
		});
		
		</script>
		<?php
		if (mezunGlobalLikes::haveLike($itemid, $nerede)) {
		 ?>
		 <a id="likebutton<?php echo $itemid;?>" href="#" url="index2.php?option=site&bolum=like&task=unlike&id=<?php echo $itemid;?>&nerede=<?php echo $nerede;?>" class="btn btn-default btn-xs likebutton likeb-<?php echo $itemid;?>">Beğeni Kaldır</a> | <small><span class="item-<?php echo $itemid;?>-count"><?php echo mezunGlobalLikes::totalLikes($itemid, $nerede);?></span> Beğenme</small>
		 <?php   
		} else {
		 ?>
		 <a id="likebutton<?php echo $itemid;?>" href="#" url="index2.php?option=site&bolum=like&task=like&id=<?php echo $itemid;?>&nerede=<?php echo $nerede;?>" class="btn btn-default btn-xs likebutton likeb-<?php echo $itemid;?>">Beğen</a> | <small><span class="item-<?php echo $itemid;?>-count"><?php echo mezunGlobalLikes::totalLikes($itemid, $nerede);?></span> Beğenme</small>
		 <?php
		}
	}
}
