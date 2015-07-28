<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

	mimport('helpers.modules.istek.helper');
	
	$total = mezunIstekHelper::totalWaiting();
	
	$total = $total ? '<a href="'.sefLink('index.php?option=site&bolum=istek&task=inbox').'">'.$total.'</a>' : $total;
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		
		setInterval(checkIstek, 60000); // 60 seconds
		
		function checkIstek() {
			
			$.ajax({
				type: "GET",
				url: 'index2.php?option=site&bolum=istek&task=checkistek',
				dataType: "html",
				success: function(data) {
					$('#istek').html(data);
				}
			});
			
		}
	});
	</script>
	
	Toplam <span id="istek" class="badge"><?php echo $total;?></span> yeni arkadaşlık isteğiniz var