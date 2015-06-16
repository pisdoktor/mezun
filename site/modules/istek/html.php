<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class Istek {
	
	static function inBox($rows, $pageNav, $type) {
		$head = $type ? 'ARKADAŞLIK İSTEKLERİ: GİDEN' : 'ARKADAŞLIK İSTEKLERİ: GELEN';
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
<th width="15%">
Gönderim Zamanı
</th>
</tr>
</table>
<?php
	if (!$rows) {
		?>
		<div align="center">Henüz bir istek yok!</div>
		<?php
	}
?>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];
$row->giden = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->aid.'">'.$row->giden.'</a>';
$row->gonderen = '<a href="index.php?option=site&bolum=profil&task=show&id='.$row->gid.'">'.$row->gonderen.'</a>';
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
<input type="hidden" name="bolum" value="istek" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
</form>
		<?php
		
	}
	
}
