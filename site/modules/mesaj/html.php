<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
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
	
	static function inBox($rows, $pageNav, $type) {
		$head = $type ? 'MESAJ KUTUSU: GİDEN' : 'MESAJ KUTUSU: GELEN';
		?>
	<div class="panel panel-warning">
		<div class="panel-heading"><h4><?php echo $head;?></h4></div>
		<div class="panel-body">
	<form action="index.php" method="post" name="adminForm" role="form">
	
	<div class="form-group">
	<div class="btn-group">
	<?php echo $type == 0 ? formButton("Okunmadı Olarak İşaretle", 'unread', 1) : '';?>
	<?php echo $type == 0 ? formButton("Okundu Olarak İşaretle", 'read', 1) : '';?>
	<?php echo formButton('Mesajı Sil', 'delete', 2);?>
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
		<div align="center">Henüz arkadaşlarınızdan mesajınız yok!</div>
		<div align="center">Ama siz arkadaşlarınıza mesaj atabilirsiniz.</div>
		<?php
	}
?>
<?php
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->baslik = base64_decode($row->baslik);
$row->baslik = $row->okunma ? '<i>'.$row->baslik.'</i>' : '<strong>'.$row->baslik.'</strong>';
$row->gonderen = $row->okunma ? '<i>'.$row->gonderen.'</i>' : '<strong>'.$row->gonderen.'</strong>';
$row->giden = $row->okunma ? '<i>'.$row->giden.'</i>' : '<strong>'.$row->giden.'</strong>';
$checked = mosHTML::idBox( $i, $row->id );
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
	<a href="index.php?option=site&bolum=mesaj&task=show&id=<?php echo $row->id;?>">
<?php echo $row->baslik;?>
</a>
	</div>
	<div class="col-sm-3">
	<?php echo mosFormatDate($row->tarih);?>
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
