<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class ConfigHTML {
	
	static function ConfigFile($data) {
		?>
		<div id="module_header">Ayar Dosyası Düzenleme</div>
		<div id="module">
		<form action="index.php" method="post" name="adminForm">
		<table width="100%">
		<tr>
		 <td width="20%">
		  <strong>Dosya İçeriği:</strong>
		  </td>
		  <td width="80%">
		  <textarea id="data" name="data" cols="80" rows="20" class="textbox"><?php echo $data;?></textarea>
		  </td>
		  </tr>
		  </table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="ayarlar" />
<input type="hidden" name="task" value="" />
		</form>
		</div>
		<br />
<div align="right">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="button"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="button" />
</div>
		<?php
		
	}

}
