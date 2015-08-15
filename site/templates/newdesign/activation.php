<div id="activation" title="Hesap Aktivasyonu" style="display: none;">
<form action="index.php" method="post" role="form">
<span class="help-block">* E-posta adresinize gönderilen aktivasyon kodunu giriniz.</span>

<div class="form-group">
<div class="row">
<div class="col-sm-5">
<label for="code">Aktivasyon Kodu:</label>
</div>
<div class="col-sm-7">
<input type="text" name="code" id="code" class="form-control" required />
</div> 
</div>
</div>

<div class="form-group">
<div class="row">
<div class="col-sm-12">
<input type="submit" name="button" class="btn btn-warning" value="AKTİVE ET!" />
</div>
</div> 
</div>

<input type="hidden" name="option" value="activate" />
<input type="hidden" name="<?php echo $validate; ?>" value="1" />
</form>
</div>
