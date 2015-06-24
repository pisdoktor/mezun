<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class DuyuruHTML {
	static function editDuyuru($row) {
		?>
		<div id="module_header">Duyuru <?php echo $row->id ? 'Düzenle' : 'Ekle';?></div>
		<div id="module">
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.metin.value == ""){
				alert( "Duyuru metnini boş bırakmışsınız" );
			}  else {
		submitform( pressbutton );
			}
		}
		//-->
		</script> 
<form action="index.php" method="post" name="adminForm">

<table width="100%">
  <tr>
	<td width="30%">
	<strong>Duyuru Metni:</strong>
	</td>
	<td width="70%">
	<textarea id="metin" name="text" cols="60" rows="10" class="textbox"><?php echo $row->text;?></textarea>
	</td>
  </tr>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="duyuru" />
<input type="hidden" name="tarih" value="<?php echo $row->tarih ? $row->tarih : date('Y-m-d H:i:s');?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id;?>" />
</form>
</div>
<br />
<div align="right">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="button"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="button" />
</div>
<?php
}
	
	static function getDuyuruList($rows, $pageNav) {
		?>
<form action="index.php" method="post" name="adminForm">

<div align="right">
<input type="button" name="button" value="Yeni Duyuru Ekle" onclick="javascript:submitbutton('add');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu duyuruları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="button" /> 
</div>

<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
</th>
<th width="10%">
Duyuru Tarihi
</th>
<th>
Duyuru İçeriği
</th>
</tr>
</table>

<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

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
<td width="10%">
<center>
<a href="index.php?option=admin&bolum=duyuru&task=editx&id=<?php echo $row->id;?>"><?php echo $row->tarih;?></a>
</center>
</td>
<td>
<?php echo $row->text;?>
</td>
</tr>
</table>
</div>

<?php
$t = 1 - $t;
}
?>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="duyuru" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />
<div align="right">
<input type="button" name="button" value="Yeni Duyuru Ekle" onclick="javascript:submitbutton('add');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('edit');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu duyuruları silmek istediğinize emin misiniz?')){ submitbutton('delete');}" class="button" /> 
</div>
</form>

<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=duyuru';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>

<?php
		
	}
}
