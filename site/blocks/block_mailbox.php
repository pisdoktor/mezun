<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

mimport('helpers.modules.mesaj.helper');

		$total = mezunMesajHelper::totalUnread();
		
		$total = $total ? '<a href="'.sefLink('index.php?option=site&bolum=mesaj&task=inbox').'">'.$total.'</a>' : $total;
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		
		setInterval(checkMail, 60000); // 60 seconds
		
		function checkMail() {
			
			$.ajax({
				type: "GET",
				url: 'index2.php?option=site&bolum=mesaj&task=checkmail',
				dataType: "html",
				success: function(data) {
					$('#mail').html(data);
				}
			});
			
		}
	});
	</script>
	Toplam <span id="mail" class="badge"><?php echo $total;?></span> yeni mesajınız var
