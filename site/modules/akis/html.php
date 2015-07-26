<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunAkisHTML {
	
	static function siteAkis($rows, $my, $limit) {
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			//scroll
			$('.scroll').jscroll({
				autoTrigger: true
			});
		
			// process the form
			$('form').submit(function(event) {
				var formData = {
					'text' : $('#msgfield').val(),
					'userid' : $('input[name=userid]').val()
				};
				
				$('button[name=submit]').attr("disabled", "disabled");
						
				$.ajax({
					type    : 'POST',
					url     : 'index2.php?option=site&bolum=akis&task=send',
					data    : formData,
					dataType: 'json',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					$('#msgfield').val('');
					$('#charNum').html('255');
					$('#akis-messages').prepend(data);
					$('button[name=submit]').removeAttr("disabled");
				});
				event.preventDefault();
			});
		});
		</script>

		<div class="panel panel-danger">
		<div class="panel-heading"></div>
		<div class="panel-body">
		<div class="form-group">
				<div class="row">
				<div class="col-sm-12">
				<form action="index2.php?option=site&bolum=akis&task=send" method="post" role="form">
				
				<textarea rows="2" id="msgfield" maxlength="255" name="text" class="form-control" placeholder="Ne düşünüyorsun?" required></textarea>
				<div align="right"><small><span id="charNum">255</span></small></div>
				
				<button name="submit" class="btn btn-default btn-sm">Gönder</button>
				<input type="hidden" name="userid" value="<?php echo $my->id;?>" />
				</form>
				
				</div>
				</div>
		</div>
		<hr>
		<div id="akis-messages"></div>
		<div class="scroll">
		<?php
		  foreach ($rows as $row) {
			  $row->image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" alt="'.$row->name.'" title="'.$row->name.'" width="50" height="50" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" alt="'.$row->name.'" title="'.$row->name.'" width="50" height="50" />';
			  
			  mezunAkisHelper::getRow($row);
		  }
		  ?>
		  <a href="index2.php?option=site&bolum=akis&task=next&limitstart=<?php echo $limit+1;?>&limit=<?php echo $limit;?>" class="next">Sonraki <?php echo $limit;?></a>
				
		</div>
		
		</div>
		<div class="panel-footer"></div>
		</div>

		  <?php
	}
	
}
