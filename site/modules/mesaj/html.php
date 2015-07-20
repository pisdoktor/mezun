<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
	static function showMsg($row, $type) {
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><h4>MESAJ KUTUSU: <?php echo $row->baslik;?></h4></div>
		<div class="panel-body">
		
		<div class="row">
		<div class="col-sm-3">
		<strong>Gönderen:</strong>
		</div>
		<div class="col-sm-9">
		<?php echo $row->gonderen;?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-3">
		<strong>Gönderim Tarihi:</strong>
		</div>
		<div class="col-sm-9">
		<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-3">
		<strong>Mesaj İçeriği:</strong>
		</div>
		<div class="col-sm-9">
		<?php echo $row->text;?>
		</div>
		</div>
		
		<?php if (!$type) {?>
		<a class="btn btn-default" href="#" id="sendamsg">Cevap Yaz</a>
		<?php }?>
		
		<!-- Mesaj Gönderme-->
		<div id="sendmessage" style="display: none;" title="Mesaj Gönder">
		<form action="index.php" method="post" role="form">
		<input type="text" name="baslik" class="form-control" value="Cvp: <?php echo $row->baslik;?>" required>
		<textarea rows="5" name="text" class="form-control" placeholder="Mesajınızın içeriği" required></textarea>
		<button type="submit" class="btn btn-primary">Mesajı Gönder</button>
		<input type="hidden" name="option" value="site" />
		<input type="hidden" name="bolum" value="mesaj" />
		<input type="hidden" name="task" value="send" />
		<input type="hidden" name="aid" value="<?php echo $row->gid;?>" />
		</form>
		</div>
		<!-- Mesaj Gönderme -->
		
		</div>
		</div>
		<?php
		
	}
	
	static function createMsg($my, $userlist) {
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><h4>MESAJ KUTUSU: YENİ MESAJ</h4></div>
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
		<label for="text">Mesajın İçeriği:</label>
		<textarea rows="5" name="text" id="text" class="form-control"  placeholder="Mesajınızın içeriği" required></textarea>
		</div>
		</div>
		</div>
				
		<div class="col-sm-6">
		<div class="form-group">
		<div class="row">
		<label for="aid">Gönderileceği Kişi:</label>
		<div><?php echo $userlist;?></div>
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
		<input type="hidden" name="bolum" value="mesaj">
		<input type="hidden" name="task" value="send">
		<input type="hidden" name="gid" value="<?php echo $my->id;?>">
		</form>
		</div>
		</div>
		<?php
	}
	
	static function inBox($rows, $pageNav, $type, $crpt) {
		$head = $type ? 'MESAJ KUTUSU: GİDEN' : 'MESAJ KUTUSU: GELEN';
		?>
	<div class="panel panel-warning">
		<div class="panel-heading"><h4><?php echo $head;?></h4></div>
		<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">
	
	<div class="form-group">
	<div class="btn-group">
	<?php echo $type == 0 ? mezunGlobalHelper::formButton("Yeni", 'new', 0) : '';?>
	<?php echo $type == 0 ? mezunGlobalHelper::formButton("Okunmadı Olarak İşaretle", 'unread', 1) : '';?>
	<?php echo $type == 0 ? mezunGlobalHelper::formButton("Okundu Olarak İşaretle", 'read', 1) : '';?>
	<?php echo mezunGlobalHelper::formButton('Mesajı Sil', 'delete', 2);?>
	</div>
	</div>
	
	
	<div class="row">
	<div class="col-sm-1">
	<strong>SIRA</strong>
	</div>
	<div class="col-sm-1">
	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>)"/>
	</div>
	<div class="col-sm-3">
	<strong><?php echo $type ? 'Gönderilen' : 'Gönderen';?></strong>
	</div>
	<div class="col-sm-4">
	<strong>BAŞLIK</strong>
	</div>
	<div class="col-sm-3">
	<strong>GÖNDERİM ZAMANI</strong>
	</div>
	</div>
<?php
	if (!$rows) {
		?>
		<div align="center">Henüz mesajınız yok!</div>
		<?php
	}
?>
<?php
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->baslik = mezunMesajHelper::cryptionText($row->baslik, 'decode');
$row->baslik = $row->okunma ? '<i>'.$row->baslik.'</i>' : '<strong>'.$row->baslik.'</strong>';
$row->gonderen = $row->okunma ? '<i>'.$row->gonderen.'</i>' : '<strong>'.$row->gonderen.'</strong>';
$row->giden = $row->okunma ? '<i>'.$row->giden.'</i>' : '<strong>'.$row->giden.'</strong>';
$checked = mezunHTML::idBox( $i, $row->id );
?>
<div class="row" id="<?php echo $row->id;?>">
	<div class="col-sm-1">
	<?php echo $pageNav->rowNumber( $i ); ?>
	</div>
	<div class="col-sm-1">
	<?php echo $checked;?>
	</div>
	<div class="col-sm-3">
	<?php echo $type ? $row->giden : $row->gonderen;?>
	</div>
	<div class="col-sm-4">
	<a href="<?php echo sefLink('index.php?option=site&bolum=mesaj&task=show&id='.$row->id);?>">
<?php echo $row->baslik;?>
</a>
	</div>
	<div class="col-sm-3">
	<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
	</div>
</div>
<?php
}
?>
<input type="hidden" name="option" value="site" />
<input type="hidden" name="bolum" value="mesaj" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
</div>
		<?php
	}
}
