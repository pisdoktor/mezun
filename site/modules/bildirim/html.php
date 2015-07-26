<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class systemMessage {
	
	static function Form($my, $list) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading">GERİ BİLDİRİM</div>
		<div class="panel-body">
		<form action="index.php" method="post" name="adminForm" role="form">
		
		<div class="col-sm-6">
		<div class="form-group">
		<div class="row">
		<label for="baslik">Mesaj Başlığı:</label>
		<input type="text" name="baslik" id="baslik" class="form-control" placeholder="Mesajınızın başlığı" required>
		</div>
		</div>
		
		<div class="form-group">
		
		<div class="row">
		<label for="text">Bölüm:</label>
		<?php echo $list;?>
		</div>
		
		</div>
	
		<div class="form-group">
		
		<div class="row">
		<label for="text">Mesajın İçeriği:</label>
		<textarea rows="5" name="text" id="text" class="form-control"  placeholder="Mesajınızın içeriği" required></textarea>
		</div>
		
		</div>
		
		</div>
				
		<div class="col-sm-6">
		<div class="form-group">
		<div class="text-info">
		* Site içerisinde gördüğünüz hataları bu sayfadan sistem yöneticisine bildirebilirsiniz.
		</div>
		<div class="text-warning">
		* Bu formu aynı zamanda istek, öneri ve görüşleriniz için de kullanabilirsiniz. Bölüm kısmından "Diğer" seçeneğini seçerek mesajınızı iletebilirsiniz.
		</div>
		</div>
		</div>
		
		<div class="col-sm-12">
		<div class="form-group">
		<div class="row">
		<button type="submit" class="btn btn-info">MESAJI GÖNDER</button>
		</div>
		</div>
		</div>
				
	
		<input type="hidden" name="option" value="site">
		<input type="hidden" name="bolum" value="bildirim">
		<input type="hidden" name="task" value="send">
		</form>
		</div>
		</div>
		<?php		
	}
	
}
