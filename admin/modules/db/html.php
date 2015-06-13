<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class BackupHTML {
	
	static function getDBTableList($rows, $pageNav) {
		global $dbase;
		?><form action="index.php" method="post" name="adminForm">

<div align="right">
<input type="button" name="button" value="Yedekle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('backup');}" class="button" /> 
<input type="button" name="button" value="Onar" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('repair');}" class="button" /> 
<input type="button" name="button" value="Kontrol Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('check');}" class="button" /> 
<input type="button" name="button" value="Optimize Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('optimize');}" class="button" /> 
<input type="button" name="button" value="Analiz Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('analyze');}" class="button" /> 
</div>

<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</th>
<th width="30%">
Tablo Adı
</th>
<th width="30%">
Satır Sayısı
</th>
<th>
Toplam Boyut
</th>
</tr>
</table>

<?php
$t = 0;
$i = 0;
foreach ($rows as $key=>$value) {
	
	$dbase->setQuery('SELECT COUNT(*) FROM '.$value);
	$total = $dbase->loadResult();

$checked = mosHTML::idBox( $i, $value );
?>

<div id="detail<?php echo $key;?>">
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
<td width="30%">
<?php echo $value;?>
</td>
<td width="30%">
<center>
<?php echo $total;?>
</center>
</td>
<td>
<center>
<?php echo tabloBoyutu($value);?> KByte
</center>
</td>
</tr>
</table>
</div>

<?php
$t = 1 - $t;
$i++;
}
?>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="db" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />
<div align="right">
<input type="button" name="button" value="Yedekle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('backup');}" class="button" /> 
<input type="button" name="button" value="Onar" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('repair');}" class="button" /> 
<input type="button" name="button" value="Kontrol Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('check');}" class="button" /> 
<input type="button" name="button" value="Optimize Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('optimize');}" class="button" /> 
<input type="button" name="button" value="Analiz Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('analyze');}" class="button" />
</div>
</form>
<?php
		
		
	}
	
}
