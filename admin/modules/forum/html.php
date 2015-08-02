<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class ForumHTML {
	
	static function editBoard($row, $lists, $nodes) {
			?>
			<script type="text/javascript">
			function getBoards() {
				catid = $("select[name=ID_CAT]").val();
				
				$("#parent").hide();
				$("#loading").show();
	
				$.ajax({

				type: "POST",

				url: "index2.php?option=admin&bolum=forum&task=getboards&catid="+catid+"&id=<?php echo $row->ID_BOARD;?>",

				data: "",

				contentType: "application/json; charset=utf-8",

				success: function (data) {
					$("#loading").hide();
					$("#parent").show().html(data);

				}

			});
			}
			</script>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Forum Board <?php echo $row->ID_BOARD ? 'Düzenle' : 'Ekle';?></h4></div>
	<div class="panel-body">	
			
<form action="index.php" method="post" name="adminForm" role="form">

<table width="100%">
  <tr>
	<td width="30%">
	<strong>Adı:</strong>
	</td>
	<td width="70%">
	<input type="text" name="name" class="form-control" value="<?php echo $row->name;?>" />
	</td>
  </tr>
  <tr>
	<td width="30%">
	<strong>Açıklama:</strong>
	</td>
	<td width="70%">
	<textarea cols="10" rows="5" class="form-control" name="aciklama"><?php echo $row->aciklama;?></textarea>
	</td>
  </tr>
   <tr>
	<td width="30%">
	<strong>Kategori:</strong>
	</td>
	<td width="70%">
	<?php echo $lists['cat'];?>
	</td>
  </tr>
   <tr>
	<td width="30%">
	<strong>Ana Board:</strong>
	</td>
	<td width="70%">
	<span id="parent">
	<?php echo $lists['parent'];?>
	</span>
	<span id="loading" style="display: none;">Yükleniyor...</span>
	</td>
  </tr>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="forum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="ID_BOARD" value="<?php echo $row->ID_BOARD;?>" />
</form>
<br />
<div class="btn-group">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('saveboard');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancelboard');" class="btn btn-warning" />
</div>

</div>
</div>
<?php
		
	}
	
	static function Boards($list, $pageNav) {
		
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Forum Board Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=forum&task=newboard" class="btn btn-default btn-sm">Yeni Board Ekle</a>
</div>
</div>
	<table class="table table-striped">
	<thead>
	<tr>
	<th>SIRA</th>
	<th>İŞLEM</th>
	<th>BOARD ADI</th>
	<th>KATEGORİ ADI</th>
	</tr>
	</thead>
	<tbody>
<?php
$t = 0;
$i = 0;
foreach ($list as $row) {
	$editlink = '<a href="index.php?option=admin&bolum=forum&task=editboard&id='.$row->ID_BOARD.'">Düzenle</a>';
	$deletelink = '<a href="dex.php?option=admin&bolum=forum&task=deleteboard&id='.$row->ID_BOARD.'">Sil</a>';
?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
 <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  <span class="glyphicon glyphicon-cog"></span> 
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><?php echo $editlink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $row->treename;?>
</td>
<td>
<?php echo $row->catname;?>
</td>
</tr>
<?php
$t = 1 - $t;
$i++;
}
?>
</tbody>
</table>
</div>

<div class="panel-footer">
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
</div>
</div>
<?php		
	}
	
	static function editCategory($row) {
			?>
			<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Forum Kategori <?php echo $row->ID_CAT ? 'Düzenle' : 'Ekle';?></h4></div>
	<div class="panel-body">
	
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancelcat') {
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
<form action="index.php" method="post" name="adminForm" role="form">

<table width="100%">
  <tr>
	<td width="30%">
	<strong>Kategori Adı:</strong>
	</td>
	<td width="70%">
	<input type="text" name="name" class="form-control" value="<?php echo $row->name;?>" />
	</td>
  </tr>
</table>
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="forum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="ID_CAT" value="<?php echo $row->ID_CAT;?>" />
</form>

<br />
<div class="btn-group">
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('savecat');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancelcat');" class="btn btn-warning" />
</div>

</div>
</div>
<?php
	}
	
	static function Categories($rows, $pageNav) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Forum Kategori Yönetimi</h4></div>
	<div class="panel-body">
	<div class="row">
<div class="col-sm-8">
<a href="index.php?option=admin&bolum=forum&task=newcat" class="btn btn-default btn-sm">Yeni Kategori Ekle</a>
</div>
</div>

<table class="table table-striped">
<thead>
<tr>
<th>SIRA</th>
<th>İŞLEM</th>
<th>KATEGORİ ADI</th>
</tr>
</thead>
<tbody>
<?php
$t = 0;
for($i=0; $i<count($rows);$i++) {
$row = $rows[$i];

$editlink = '<a href="index.php?option=admin&bolum=forum&task=editcat&id='.$row->ID_CAT.'">Düzenle</a>';
$deletelink = '<a href="index.php?option=admin&bolum=forum&task=deletecat&id='.$row->ID_CAT.'">Sil</a>';

?>
<tr>
<td>
<?php echo $pageNav->rowNumber( $i ); ?>
</td>
<td>
<div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
  <span class="glyphicon glyphicon-cog"></span> 
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<li><?php echo $editlink;?></li>
	<li><?php echo $deletelink;?></li>
  </ul>
</div>
</td>
<td>
<?php echo $row->name;?>
</td>
</tr>
<?php
$t = 1 - $t;
}
?>
</tbody>
</table>
	
	</div>
	<div class="panel-footer">
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
	</div>
	</div>
	<?php
	}
}
