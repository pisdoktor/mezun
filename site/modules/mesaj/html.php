<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Message {
	
	static function createMsg($my) {
		?>
		<form action="index.php" method="post" name="adminForm">
		<input type="text" name="baslik" class="inputbox">
		<textarea cols="50" rows="5" name="text"></textarea>
		
		<input type="text" name="aid" class="inputbox">
		<input type="submit" name="submit" value="Gönder" value="button">
		<input type="hidden" name="option" value="site">
		<input type="hidden" name="bolum" value="mesaj">
		<input type="hidden" name="task" value="send">
		<input type="hidden" name="gid" value="<?php echo $my->id;?>">
		</form>
		<?php
	}
	
	static function inBox($rows, $pageNav) {
		$inbox = '<a href="index.php?option=site&bolum=mesaj&task=inbox">Gelen Kutusu</a>';
		$outbox = '<a href="index.php?option=site&bolum=mesaj&task=outbox">Giden Kutusu</a>';
		$new = '<a href="index.php?option=site&bolum=mesaj&task=new">Yeni Mesaj</a>';
		?>
		<form action="index.php" method="post" name="adminForm">
		<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</th>
<th width="60%">
Başlık
</th>
<th width="20%">
Gönderen
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
$checked = mosHTML::idBox( $i, $row->id );
?>
<div id="detail<?php echo $row->id;?>">
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
<td width="60%">
<center>
<a href="index.php?option=site&bolum=mesaj&task=show&id=<?php echo $row->id;?>">
<?php echo $row->baslik;?>
</a>
</center>
</td>
<td width="20%">
<center>
<?php echo $row->gonderen;?>
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
<input type="hidden" name="boxchecked" value="0" />
</form>
		<?php
	}
}
