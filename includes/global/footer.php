<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );  

$version = new mezunGlobalVersion();
?>
<div align="center" class="footer">

<div>
<small>
<?php echo $version->getShortVersion();?>
</small>
</div>

<div>
<small>
<?php echo $version->getCopy();?>
</small>
</div>

<div>
<a href="#" id="opener"><small>Hakkında</small></a>
</div>

</div>

<div style="display:none;" id="dialog" title="Hakkında">
<table width="100%" class="table-striped">
  <tr>
	<td width="50%"><strong>Paket Adı:</strong></td>
	<td><?php echo $version->Product();?></td>
  </tr>
  <tr>
	<td><strong>Paket Sürümü:</strong></td>
	<td><?php echo $version->Release();?></td>
  </tr>
  <tr>
	<td><strong>Geliştirme Seviyesi:</strong></td>
	<td><?php echo $version->DevelopmentLevel();?></td>
  </tr>
	<tr>
	<td><strong>Paket Durumu:</strong></td>
	<td><?php echo $version->DevelopmentStatus();?></td>
  </tr>
	<tr>
	<td><strong>Paket Kod Adı:</strong></td>
	<td><?php echo $version->codeName();?></td>
  </tr>
	<tr>
	<td><strong>Paketleme Tarihi:</strong></td>
	<td><?php echo $version->ReleasedDate();?></td>
  </tr>
	<tr>
	<td><strong>Kodlama:</strong></td>
	<td>Soner Ekici</td>
  </tr>
</table>
</div>