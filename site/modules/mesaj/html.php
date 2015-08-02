<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
	static function showMsg($row, $type) {
		
		$marklink = '<a href="index.php?option=site&bolum=mesaj&task=unread&id='.$row->id.'">Okunmadı Yap</a>';
		$deletelink = '<a href="index.php?option=site&bolum=mesaj&task=delete&id='.$row->id.'">Sil</a>';
		$sendmsg = '<a href="#" id="sendamsg">Cevap Yaz</a>';
		?>
		<div class="panel panel-warning">
		<div class="panel-heading"><h4>MESAJ KUTUSU: <?php echo $row->baslik;?></h4></div>
		<div class="panel-body">
		
		<div class="row">
		<div class="col-sm-7">
		<strong>Gönderen: </strong><?php echo $row->gonderen;?>
		</div>
		<div class="col-sm-4">
		<strong>Gönderim Tarihi: </strong><?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
		</div>
		<div class="col-sm-1">
		
		<div class="dropdown">
		<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
		<span class="glyphicon glyphicon-cog"></span> 
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><?php echo $type ? '': $sendmsg;?></li>
			<li><?php echo $type ? '': $marklink;?></li>
			<li><?php echo $deletelink;?></li>
		</ul>
		</div>
		
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
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		
		</div>
		</div>
		</div>
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
		<div class="panel-heading"><?php echo $head;?></div>
		<div class="panel-body">
		<div class="row">
<div class="col-sm-8">
<a href="index.php?option=site&bolum=mesaj&task=new" class="btn btn-default btn-sm">Yeni Mesaj</a>
</div>
</div>
		<table class="table table-striped">
		<thead>
		<tr>
		<th>SIRA</th>
		<th>İŞLEM</th>
		<th><?php echo $type ? 'GÖNDERİLEN' : 'GÖNDEREN';?></th>
		<th>BAŞLIK</th>
		<th>GÖNDERİM ZAMANI</th>
		</tr>
		</thead>
		<tbody>
		<?php
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->baslik = mezunMesajHelper::cryptionText($row->baslik, 'decode');

$row->baslik = $row->okunma ? '<i>'.$row->baslik.'</i>' : '<strong>'.$row->baslik.'</strong>';

$row->gonderen = $row->okunma ? '<i>'.$row->gonderen.'</i>' : '<strong>'.$row->gonderen.'</strong>';

$row->giden = $row->okunma ? '<i>'.$row->giden.'</i>' : '<strong>'.$row->giden.'</strong>';

$showlink = '<a href="index.php?option=site&bolum=mesaj&task=view&id='.$row->id.'">Göster</a>';
$marklink = $row->okunma ? '<a href="index.php?option=site&bolum=mesaj&task=unread&id='.$row->id.'">Okunmadı Yap</a>':'<a href="index.php?option=site&bolum=mesaj&task=read&id='.$row->id.'">Okundu Yap</a>';
$deletelink = '<a href="index.php?option=site&bolum=mesaj&task=delete&id='.$row->id.'">Sil</a>';
?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  <span class="glyphicon glyphicon-cog"></span> 
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><?php echo $showlink;?></li>
	<li><?php echo $type ? '': $marklink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $type ? $row->giden : $row->gonderen;?>
</td>
<td>
<a href="index.php?option=site&bolum=mesaj&task=view&id=<?php echo $row->id;?>">
<?php echo $row->baslik;?>
</a>
</td>
<td>
<?php echo mezunGlobalHelper::timeformat($row->tarih, true, true);?>
</td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
<div class="panel-footer">

</div>
</div>
<?php
	}
	
}