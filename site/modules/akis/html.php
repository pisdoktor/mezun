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
			
			$(function() {
				$( "#formtabs" ).tabs();
			});
		
			// process the form
			$('#form-msg').submit(function(event) {
				var formData = {
					'text' : $('#msgfield').val(),
					'userid' : $('input[name=userid]').val()
				};
				
				$('button[name=submit]').attr("disabled", "disabled");
				$('#msg-loading').html('Mesaj gönderiliyor...');
						
				$.ajax({
					type    : 'POST',
					url     : 'index2.php?option=site&bolum=akis&task=send',
					data    : formData,
					dataType: 'html'
				})
						
				.done(function(data) {
					console.log(data);
					$('#msg-loading').hide();
					$('#msgfield').val('');
					$('#charNum').html('255');
					$('#akis-messages').prepend(data);
					$('button[name=submit]').removeAttr("disabled");
				});
				event.preventDefault();
			});
			
			// process the form
			$("#form-image").submit(function(event) {
				
				var data = new FormData();
				
				jQuery.each(jQuery('#file')[0].files, function(i, file) {
				data.append('file-'+i, file);
				});
				
				data.append('text', $('#imgfield').val());
				
				$('#image-loading').html('Resim yükleniyor...');
								
				$('button[name=submit]').attr("disabled", "disabled");
				
				$.ajax({
					url: "index2.php?option=site&bolum=akis&task=sendimage", 
					 data: data,
					 cache: false,
					 contentType: false,
					 processData: false,
					 type: 'POST',
					 success: function(data){
						console.log(data);
						$('#image-loading').hide();
						$('#imgfield').val('');
						$('#imgcharNum').html('255');
						$('#file').val('');
						$('#akis-messages').prepend(data);
						$('button[name=submit]').removeAttr("disabled");
					}
					});
				event.preventDefault();
			});
		});
		</script>

		<div class="panel panel-danger">
		<div class="panel-heading"></div>
		<div class="panel-body">
		
		<div id="formtabs">
		<ul>
		<li><a href="#form-message">Mesaj Paylaş</a></li>
		<li><a href="#form-image">Resim Paylaş</a></li>
		</ul>
		
		<div id="form-message">
		<div class="form-group">
				<div class="row">
				<div class="col-sm-12">
				<form id="form-msg" method="post" role="form">
				
				<textarea rows="2" id="msgfield" maxlength="255" name="text" class="form-control" placeholder="Ne düşünüyorsun?" required></textarea>
				<div align="right"><small><span id="charNum">255</span></small></div>
				
				<button name="submit" class="btn btn-default btn-sm">Gönder</button>
				<div id="msg-loading"></div>
				<input type="hidden" name="userid" value="<?php echo $my->id;?>" />
				</form>
				</div>
				</div>
		</div>
		</div>
		
		<div id="form-image">
		<div class="form-group">
				<div class="row">
				<div class="col-sm-12">
				<form id="form-image" enctype="multipart/form-data" method="post" role="form">
				
				<textarea rows="2" id="imgfield" maxlength="255" name="text" class="form-control" placeholder="Resim hakkında ne söylemek istersin?" required></textarea>
				<div align="right"><small><span id="imgcharNum">255</span></small></div>
				
				<div class="form-group">
				<div class="row">
				<div class="col-sm-4">
				<input type="file" id="file" name="file" class="btn btn-default btn-xs" required />
				</div>
				</div>
				</div>
				
				<div class="form-group">
				<div class="row">
				<div class="col-sm-4">
				<button name="submit" class="btn btn-default btn-sm">Gönder</button>
				<div id="image-loading"></div>
				</div>
				</div>
				</div>
				</form>
				</div>
				</div>
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
