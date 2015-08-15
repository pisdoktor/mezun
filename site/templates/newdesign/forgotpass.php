<!-- forgot pass -->
<div id="forgotpass" title="Şifremi Unuttum?" style="display: none;">
<form action="index.php" method="post" role="form">
<span class="help-block">* Şifrenizi sıfırlamak için lütfen kayıtlı e-posta adresinizi yazın.</span>

<div class="form-group">
<div class="row">
<div class="col-sm-5">
<label for="email">E-posta Adresiniz:</label>
</div>
<div class="col-sm-7">
<input type="text" name="email" id="email" class="form-control" required />
</div> 
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-12">
<input type="submit" name="button" class="btn btn-info" value="PAROLAYI SIFIRLA" />
</div>
</div> 
</div>

<input type="hidden" name="option" value="forgot" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>
<!-- forgot pass -->
