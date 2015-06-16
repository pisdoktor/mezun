<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
	static function createMsg($my, $userlist) {
		?>
		<h3>MESAJ KUTUSU: YENİ MESAJ</h3>
		<form action="index.php" method="post" name="adminForm">
		<table width="100%">
		<tr>
		<td valign="top" width="50%">
		<h3>Mesaj İçeriği:</h3>
		<div><input type="text" name="baslik" class="inputbox" placeholder="Mesajınızın başlığı"></div>
		<div><textarea cols="50" rows="5" name="text" class="textbox"  placeholder="Mesajınızın içeriği"></textarea></div>
		<div><input type="submit" name="submit" value="Gönder" value="button"></div>
		</td>
		<td valign="top" align="left"  width="50%">
		<h3>Mesajın Gideceği Kişi:</h3>
		<div><?php echo $userlist;?></div>
		
		</td>
		</tr>
		</table>
		
		<input type="hidden" name="option" value="site">
		<input type="hidden" name="bolum" value="mesaj">
		<input type="hidden" name="task" value="send">
		<input type="hidden" name="gid" value="<?php echo $my->id;?>">
		</form>
		<?php
	}
	
	static function inBox($rows, $pageNav, $type) {
		$head = $type ? 'MESAJ KUTUSU: GİDEN' : 'MESAJ KUTUSU: GELEN';
		?>
	<h3><?php echo $head;?></h3>	
	<form action="index.php" method="post" name="adminForm">
	<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>)"/>
</th>
<th width="20%">
<?php echo $type ? 'Gönderilen' : 'Gönderen';?>
</th>
<th width="60%">
Başlık
</th>
<th width="15%">
Gönderim Zamanı
</th>
</tr>
</table>
<?php
	if (!$rows) {
		?>
		<div align="center">Henüz mesajınız yok!</div>
		<div align="center">Ama siz birilerine mesaj atabilirsiniz.</div>
		<?php
	}
?>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$row->baslik = base64_decode($row->baslik);
$row->baslik = $row->okunma ? '<i>'.$row->baslik.'</i>' : '<strong>'.$row->baslik.'</strong>';
$row->gonderen = $row->okunma ? '<i>'.$row->gonderen.'</i>' : '<strong>'.$row->gonderen.'</strong>';
$row->giden = $row->okunma ? '<i>'.$row->giden.'</i>' : '<strong>'.$row->giden.'</strong>';
$checked = mosHTML::idBox( $i, $row->id );
?>
<div id="<?php echo $row->id;?>">
<table width="100%" border="0" class="veriitem<?php echo $t;?>">
<tr>
<td width="5%">
<center>
<?php echo $pageNav->rowNumber( $i ); ?>
</center>
</td>
<td width="1%">
<center>
<?php echo $checked;?>
</center>
</td>
<td width="20%">
<center>
<?php echo $type ? $row->giden : $row->gonderen;?>
</center>
</td>
<td width="60%">
<center>
<a href="index.php?option=site&bolum=mesaj&task=show&id=<?php echo $row->id;?>">
<?php echo $row->baslik;?>
</a>
</center>
</td>
<td width="15%">
<center>
<?php echo $row->tarih;?>
</center>
</td>

</tr>
</table>
</div>
<?php
$t = 1 - $t;
}
?>
<input type="hidden" name="option" value="site" />
<input type="hidden" name="bolum" value="mesaj" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="boxchecked" value="0" />
</form>
		<?php
	}
}
