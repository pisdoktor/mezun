<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class ForumHTML {
	
	static function Boards($list, $pageNav) {
		
		?>
<form action="index.php" method="post" name="adminForm">

<div align="right">
<input type="button" name="button" value="Yeni Forum Ekle" onclick="javascript:submitbutton('addboard');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('editboard');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu forumları silmek istediğinize emin misiniz?')){ submitbutton('deleteboard');}" class="button" /> 
</div>

<table width="100%" border="0" class="veritable">
<tr>
<th width="5%">
SIRA
</th>
<th width="1%">
<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $list ); ?>);" />
</th>
<th width="20%">
Kategori Adı
</th>
<th width="50%">
Forum Adı
</th>
<th>
Sıralaması
</th>
</tr>
</table>

<?php
$t = 0;
$i = 0;
foreach ($list as $row) {

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
<td width="20%">
<?php echo $row->catname;?>
</td>
<td width="50%">
<a href="index.php?option=admin&bolum=forum&task=editboard&id=<?php echo $row->id;?>"><?php echo $row->treename;?></a>
</td>
<td>
<?php echo $row->boardOrder;?>
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
<input type="hidden" name="bolum" value="forum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />
<div align="right">
<input type="button" name="button" value="Yeni Forum Ekle" onclick="javascript:submitbutton('addboard');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('editboard');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kategorileri silmek istediğinize emin misiniz?')){ submitbutton('deleteboard');}" class="button" /> 
</div>
</form>

<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=forum&task=boards';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>

<?php
		
		
	}
	
	static function editCategory($row) {
			?>
		<div id="module_header">Forum Kategori <?php echo $row->ID_CAT ? 'Düzenle' : 'Ekle';?></div>
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
			if (form.name.value == ""){
				alert( "Kategori adını boş bırakmışsınız" );
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
	<strong>Kategori Adı:</strong>
	</td>
	<td width="70%">
	<input type="text" name="name" class="inputbox" value="<?php echo $row->name;?>" />
	</td>
  </tr>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="forum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="ID_CAT" value="<?php echo $row->ID_CAT;?>" />
</form>
</div>
<br />
<div align="right">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('savecat');" class="button"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancelcat');" class="button" />
</div>
<?php
	}
	
	static function Categories($rows, $pageNav) {
		?>
<form action="index.php" method="post" name="adminForm">

<div align="right">
<input type="button" name="button" value="Yeni Kategori Ekle" onclick="javascript:submitbutton('addcat');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('editcat');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kategorileri silmek istediğinize emin misiniz?')){ submitbutton('deletecat');}" class="button" /> 
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
Kategori Adı
</th>
<th>
Sıralaması
</th>
</tr>
</table>

<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$checked = mosHTML::idBox( $i, $row->ID_CAT );
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
<a href="index.php?option=admin&bolum=forum&task=editcat&id=<?php echo $row->ID_CAT;?>"><?php echo $row->name;?></a>
</center>
</td>
<td>
<?php echo $row->catOrder;?>
</td>
</tr>
</table>
</div>

<?php
$t = 1 - $t;
}
?>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="forum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<br />
<div align="right">
<input type="button" name="button" value="Yeni Kategori Ekle" onclick="javascript:submitbutton('addcat');" class="button" />
<input type="button" name="button" value="Seçileni Düzenle" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else {submitbutton('editcat');}" class="button" /> 
<input type="button" name="button" value="Seçileni Sil" onclick="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Lütfen listeden bir seçim yapın'); } else if (confirm('Bu kategorileri silmek istediğinize emin misiniz?')){ submitbutton('deletecat');}" class="button" /> 
</div>
</form>

<div align="center">
<div class="pagenav_counter">
<?php echo $pageNav->writePagesCounter();?>
</div>
<div class="pagenav_links">
<?php 
$link = 'index.php?option=admin&bolum=forum&task=categories';
echo $pageNav->writePagesLinks($link);?>
</div>
</div>

<?php
		
	}
}