<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$footer = new Version();  
?>
<div align="center" class="footer">
<div>
<?php echo $footer->getShortVersion();?>
<br />
<?php echo $footer->getCopy();?>
</div>
<div>
<a href="#" id="opener">Hakkında</a>
</div>
</div>

<div style="display:none;" id="dialog" title="Hakkında">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
  <tr class="row1">
	<td width="50%"><strong>Paket Adı:</strong></td>
	<td><?php echo $footer->PRODUCT;?></td>
  </tr>
  <tr class="row0">
	<td><strong>Paket Sürümü:</strong></td>
	<td><?php echo $footer->RELEASE;?></td>
  </tr>
  <tr class="row1">
	<td><strong>Geliştirme Seviyesi:</strong></td>
	<td><?php echo $footer->DEV_LEVEL;?></td>
  </tr>
	<tr class="row0">
	<td><strong>Paket Durumu:</strong></td>
	<td><?php echo $footer->DEV_STATUS;?></td>
  </tr>
	<tr class="row1">
	<td><strong>Paket Kod Adı:</strong></td>
	<td><?php echo $footer->CODENAME;?></td>
  </tr>
	<tr class="row0">
	<td><strong>Paketleme Tarihi:</strong></td>
	<td><?php echo $footer->RELDATE;?></td>
  </tr>
	<tr class="row1">
	<td><strong>Kodlama:</strong></td>
	<td>Soner Ekici</td>
  </tr>
</table>
</div>