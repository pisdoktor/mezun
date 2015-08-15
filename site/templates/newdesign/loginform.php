<!-- login form -->
<div id="loginform" title="Üye Girişi" style="display: none;">
<div class="panel panel-default">
<div class="panel-body">
<script type="text/javascript">
$(document).ready(function(){
	$('#loginForm').submit(function(event) {
				var formData = {
					'username' : $('input[name=username]').val(),
					'passwd' : $('input[name=passwd]').val(),
					'remember' : $('input[name=remember]').is(':checked') ? "yes" : "no",
					'<?php echo $validate; ?>' : $('input[name=<?php echo $validate; ?>]').val(),
				};
				
				$('button[name=submit]').attr("disabled", "disabled");
				$('input[name=username]').attr("disabled", "disabled");
				$('input[name=passwd]').attr("disabled", "disabled");
						
				$.ajax({
					type    : 'POST',
					url     : 'index2.php?option=loginx',
					data    : formData,
					dataType: 'json',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					if (data['success'] == true) {
						$('#error').html('Yükleniyor...');
						window.location = $('input[name=return]').val();
					} else {
						$('button[name=submit]').removeAttr("disabled");
						$('input[name=username]').removeAttr("disabled");
						$('input[name=passwd]').removeAttr("disabled");
						$('#error').html('<div id="message" title="Uyarı">'+data['error']+'</div>');                
					}
				});
				event.preventDefault();
	});
});
</script>
<div id="error" align="center"></div>

<form action="index.php" method="post" name="login" id="loginForm" role="form">

<div class="form-group">
<label class="sr-only" for="username">Kullanıcı Adı:</label>
<input name="username" id="username" type="text" class="form-control" placeholder="Kullanıcı adınızı yazın" required />
</div>

<div class="form-group">
<label class="sr-only" for="password">Parola:</label>
<input name="passwd" type="password" id="password" class="form-control" placeholder="Parolanızı yazın" required />
</div>

 <div class="form-group">
 <div class="checkbox">
 <label>
 <input type="checkbox" name="remember" id="remember" value="yes" /> Beni hatırla</label>
  </div>
  </div>

<div class="form-group">
<button type="submit" name="submit" class="btn btn-primary">GİRİŞ YAP</button>
</div>  

<input type="hidden" name="option" value="login" />
<input type="hidden" name="return" value="index.php" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>

</div>
</div>
<!-- login form -->
