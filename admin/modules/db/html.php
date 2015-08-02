<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class BackupHTML {
	
	static function getDBTableList($rows, $pageNav) {
		global $dbase;
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Veritabanı Yönetimi</h4></div>
	<div class="panel-body">
	
	
		<form action="index.php" method="post" name="adminForm" role="form">

		<div class="form-group">
		<div class="btn-group">
<input type="button" name="button" value="Yedekle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('backup');}" class="btn btn-primary" /> 
<input type="button" name="button" value="Onar" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('repair');}" class="btn btn-default" /> 
<input type="button" name="button" value="Kontrol Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('check');}" class="btn btn-warning" /> 
<input type="button" name="button" value="Optimize Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('optimize');}" class="btn btn-info" /> 
<input type="button" name="button" value="Analiz Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('analyze');}" class="btn btn-default" /> 
</div>
</div>

<table class="table table-striped">
<thead>
<tr>
<th>SIRA</th>
<th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
<th>TABLO ADI</th>
<th>SATIR SAYISI</th>
<th>TOPLAM BOYUT</th>
</tr>
</thead>
<tbody>
<?php
$t = 0;
$i = 0;
foreach ($rows as $key=>$value) {
	
	$dbase->setQuery('SELECT COUNT(*) FROM '.$value);
	$total = $dbase->loadResult();

$checked = mezunHTML::idBox( $i, $value );
?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<?php echo $checked;?>
</td>
<td>
<?php echo $value;?>
</td>
<td>
<?php echo $total;?>
</td>
<td>
<?php echo tabloBoyutu($value);?> KByte
</td>
</tr>
<?php
$t = 1 - $t;
$i++;
}
?>
</tbody>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="db" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />

<div class="btn-group">
<input type="button" name="button" value="Yedekle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('backup');}" class="btn btn-primary" /> 
<input type="button" name="button" value="Onar" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('repair');}" class="btn btn-default" /> 
<input type="button" name="button" value="Kontrol Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('check');}" class="btn btn-warning" /> 
<input type="button" name="button" value="Optimize Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('optimize');}" class="btn btn-info" /> 
<input type="button" name="button" value="Analiz Et" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('analyze');}" class="btn btn-default" /> 
</div>

</form>

</div>
</div>
<?php
		
		
	}
	
}
