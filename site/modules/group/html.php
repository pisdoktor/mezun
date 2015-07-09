<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class GroupHTML {
	
	static function showGroupMembers($group, $rows, $pageNav) {
		$status = $group->status ? 'KAPALI GRUP':'AÇIK GRUP';
		
		$groupimage = $group->image ? '<img src="'.SITEURL.'/images/group/'.$group->image.'" width="55" height="55" />':'<img src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
		
		if ($group->isGroupMember()) {
			$joinlink = '<a href="index.php?option=site&bolum=group&task=leave&id='.$group->id.'" class="btn btn-primary">Gruptan Ayrıl</a>';
		} else {
			if ($group->canJoinGroup()) {
			$joinlink = '<a href="index.php?option=site&bolum=group&task=join&id='.$group->id.'" class="btn btn-primary">Gruba Katıl</a>';    
			} else {
				$joinlink = 'Grup kapalı ve siz katılamazsınız';
			}
		}
		
		$editlink = $group->canEditGroup() ? '<a href="index.php?option=site&bolum=group&task=edit&id='.$group->id.'" class="btn btn-info">Grubu Düzenle</a>':'';
		$deletelink = $group->canDeleteGroup() ? '<a href="index.php?option=site&bolum=group&task=delete&id='.$group->id.'" class="btn btn-warning">Grubu Sil</a>':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading"><h4>GRUP : <?php echo $group->name;?> [<?php echo $status;?>]</h4><small><?php echo $group->aciklama;?></small></div>
		<div class="panel-body">
		
		<div class="row">
			<div class="col-sm-7">
			<div class="panel panel-warning">
		<div class="panel-heading"><h4>Grup Üyeleri</h4></div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-7">
		Adı, Soyadı
		</div>
		<div class="col-sm-5">
		Katılma Tarihi
		</div>
		</div>
		</div>
		
		<?php
		  for($i=0;$i<count($rows);$i++) {
			  $row = $rows[$i];
			  $creator = $row->userid == $row->creatorid ? '<small style="color:red">(Kurucu Üye)</small>':'';
			  $admin = $row->isadmin ? '<small style="color:blue;">Grup Yöneticisi</small>': '';
			  $row->name = '<a href="index.php?option=site&bolum=profil&task=view&id='.$row->userid.'">'.$row->name.'</a>';	
			  ?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-7">
		<?php echo $row->name;?> <?php echo $creator;?> <?php echo $admin;?>
		</div>
		<div class="col-sm-5">
		<?php echo Forum::timeformat($row->joindate, true, true);?>
		</div>
		</div>
		</div>
		<?php  
		  }
		  ?>
		  <div align="center">
		  <?php  echo $pageNav->writePagesLinks('index.php?option=site&bolum=group&task=showmembers&id='.$group->id);  
		?>
		</div>
		
		</div>
		</div>
		
		</div>
		
		<div class="col-sm-5">
			<div class="panel panel-default">
		<div class="panel-heading"><h4>Grup Bilgisi</h4></div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<div align="center">
		<?php echo $groupimage;?>
		</div>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Grup Şekli:</div>
		<div class="col-sm-7"><?php echo $status;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Oluşturan:</div>
		<div class="col-sm-7"><?php echo $group->creatorName();?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Oluşturma Zamanı:</div>
		<div class="col-sm-7"><?php echo Forum::timeformat($group->creationdate, true, true);?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Toplam Üye:</div>
		<div class="col-sm-7"><a href="index.php?option=site&bolum=group&task=showmembers&id=<?php echo $group->id;?>"><?php echo $group->totalmember;?></a> Üye</div>
		</div>
		</div>        
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Grup Yöneticileri:</div>
		<div class="col-sm-7"><?php echo $group->admins;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12" align="center"><?php echo $joinlink;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12" align="center"><?php echo $editlink;?> <?php echo $deletelink;?></div>
		</div>
		</div>
		
		</div>
		</div>
			</div>
			</div>
		
		
		
		</div>
		</div>
		<?php		
	}
	
	static function editMyGroup($row, $list) {
		
		$groupimage = $row->image ? '<img src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading"><h4>GRUP : <?php echo $row->name;?> <?php echo $row->id ? 'DÜZENLE':'YENİ';?></h4></div>
		<div class="panel-body">
		
		<form action="index.php?option=site&bolum=group&task=save" role="form" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="name">Grubun Adı:</label>
		<div class="col-sm-6">
		<input name="name" id="name" type="text" class="form-control" placeholder="Grubun adını yazın" value="<?php echo $row->name;?>" required />
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="aciklama">Grubun Açıklaması:</label>
		<div class="col-sm-6">
		<input name="aciklama" id="aciklama" type="text" class="form-control" placeholder="Grubun açıklamasını yazın" value="<?php echo $row->aciklama;?>" required />
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="status">Grubun Şekli:</label>
		<div class="col-sm-6">
		<?php echo $list['status'];?>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<label class="control-label col-sm-4" for="image">Grubun Simgesi:</label>
		<div class="col-sm-6">
		<input type="file" name="image" id="image" class="btn btn-default">
		<?php echo $groupimage;?>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<button type="submit" class="btn btn-primary">Grubu Kaydet</button>
		</div>
		</div>
		</div>
		
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		</form>
		</div>
		</div>
		<?php
	}
	
	static function viewGroup($row, $msgs) {
		$status = $row->status ? 'KAPALI GRUP':'AÇIK GRUP';
		
		$groupimage = $row->image ? '<img src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
		
		if ($row->isGroupMember()) {
			$joinlink = '<a href="index.php?option=site&bolum=group&task=leave&id='.$row->id.'" class="btn btn-primary">Gruptan Ayrıl</a>';
		} else {
			if ($row->canJoinGroup()) {
			$joinlink = '<a href="index.php?option=site&bolum=group&task=join&id='.$row->id.'" class="btn btn-primary">Gruba Katıl</a>';    
			} else {
				$joinlink = 'Grup kapalı ve siz katılamazsınız';
			}
		}
		
		$editlink = $row->canEditGroup() ? '<a href="index.php?option=site&bolum=group&task=edit&id='.$row->id.'" class="btn btn-info">Grubu Düzenle</a>':'';
		$deletelink = $row->canDeleteGroup() ? '<a href="index.php?option=site&bolum=group&task=delete&id='.$row->id.'" class="btn btn-warning">Grubu Sil</a>':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading"><h4>GRUP : <?php echo $row->name;?> [<?php echo $status;?>]</h4><small><?php echo $row->aciklama;?></small></div>
		<div class="panel-body">
		
			<div class="row">
			<div class="col-sm-7">
			<div class="panel panel-warning">
		<div class="panel-heading"><h4>Grup Mesajları</h4></div>
		<div class="panel-body">
		<?php
			if (!$row->canViewGroup()) {
				echo 'Bu kapalı bir gruptur! Grubun içeriğini görmeniz için gruba katılmanız gerekiyor. Bunun için ise grup yöneticisiyle iletişime geçmelisiniz';
				
			} else if (!$row->isGroupMember()) {
				echo 'Grubun içeriğini görmeniz için gruba katılmanız gerekiyor.';
				
			} else  {
				?>
				<div class="form-group">
				<div class="row">
				<div class="col-sm-12">
				<script type="text/javascript">
				$(document).ready(function() {
					// process the form
					$('form').submit(function(event) {
						var formData = {
							'text' : $('#msgfield').val(),
							'groupid' : $('input[name=groupid]').val()
						};
						
						$.ajax({
							type : 'POST',
							url  : 'index2.php?option=site&bolum=group&task=send',
							data : formData,
							dataType    : 'json',
							encode          : true
						})
						
						.done(function(data) {
							console.log(data);
							$('#msgfield').val('');
							$('#charNum').html('255');
							$('#group-messages').html(data);
							
						});
						
						event.preventDefault();
					});
				});
				</script>
				<form action="index2.php?option=site&bolum=group&task=send" method="post" role="form">
				
				<textarea rows="2" id="msgfield" maxlength="255" name="text" class="form-control" placeholder="Mesajınızı yazın" required></textarea>
				<div align="right"><small><span id="charNum">255</span></small></div>
				
				<button name="submit" class="btn btn-default">Gönder</button>
				<input type="hidden" name="groupid" value="<?php echo $row->id;?>" />
				</form>
				
				</div>
				</div>
				</div>
				
				<div class="form-group">
				<div class="row">
				<div id="group-messages">
				<?php
				for($i=0; $i<count($msgs);$i++) {
					$msg= $msgs[$i];
					?>
					
					<div class="col-sm-12">
					<div class="form-group">
					
					<div class="row">
					<div class="col-sm-12">
					<small>Gönderen: <?php echo $msg->gonderen;?></small> 
					<small>Tarih: <?php echo Forum::timeformat($msg->tarih, true, true);?></small>
					</div>
					</div>
					
					<div class="row">
					<div class="col-sm-12">
					<?php echo $msg->text;?>
					</div>
					</div>
					
					</div>
					</div>
										
					<?php
					if ($i < count($msgs)-1) {
					echo '<hr>';
					}
				}    
				?>
				</div>
				</div>
				</div>
				<?php
			}
		?>
		</div>
		</div>
			</div>
			<div class="col-sm-5">
			<div class="panel panel-default">
		<div class="panel-heading"><h4>Grup Bilgisi</h4></div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<div align="center">
		<?php echo $groupimage;?>
		</div>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Grup Şekli:</div>
		<div class="col-sm-7"><?php echo $status;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Oluşturan:</div>
		<div class="col-sm-7"><?php echo $row->creatorName();?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Oluşturma Zamanı:</div>
		<div class="col-sm-7"><?php echo Forum::timeformat($row->creationdate, true, true);?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Toplam Üye:</div>
		<div class="col-sm-7"><a href="index.php?option=site&bolum=group&task=showmembers&id=<?php echo $row->id;?>"><?php echo $row->totalmember;?></a> Üye</div>
		</div>
		</div>		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Grup Yöneticileri:</div>
		<div class="col-sm-7"><?php echo $row->admins;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12" align="center"><?php echo $joinlink;?></div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12" align="center"><?php echo $editlink;?> <?php echo $deletelink;?></div>
		</div>
		</div>
		
		</div>
		</div>
			</div>
			</div>
			
		</div>
		</div>
		<?php
	}
	
	static function listGroups($rows, $pageNav) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading"><h4>ÜYE GRUPLARI</h4></div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<?php
		
		foreach ($rows as $row) {
			$row->image = $row->image ? '<img src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
			$row->name = shortText($row->name, 20);
			$row->aciklama = shortText($row->aciklama, 30);
			?>
			<div class="col-sm-3">
			<div align="center">
			<div class="row">
			<a href="index.php?option=site&bolum=group&task=view&id=<?php echo $row->id;?>">
			<?php echo $row->image;?>
			</a>
			</div>
			<div class="row">
			<a href="index.php?option=site&bolum=group&task=view&id=<?php echo $row->id;?>">
			<?php echo $row->name;?>
			</a>
			</div>
			<div class="row">
			<?php echo $row->aciklama;?>
			</div>
			<div class="row">
			<small><?php echo $row->totaluser;?> Üye</small>
			</div>
			</div>
			</div>
			<?php
		}
			
		?>
		</div>
		</div>
		
		<div align="center">
		<div class="row">
		<?php echo $pageNav->writePagesCounter();?>
		</div>
		<div class="row">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=group&task=all');?>
		</div>
		</div>
		
		</div>
		</div>
		<?php
	}
	
	static function getMyGroups($rows, $pageNav) {
		?>
		<div class="panel panel-info">
		<div class="panel-heading"><h4>GRUPLARIM</h4></div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<?php
		
		foreach ($rows as $row) {
			$row->image = $row->image ? '<img src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
			$row->name = shortText($row->name, 20);
			$row->aciklama = shortText($row->aciklama, 30);
			?>
			<div class="col-sm-3">
			<div align="center">
			<div class="row">
			<a href="index.php?option=site&bolum=group&task=view&id=<?php echo $row->id;?>">
			<?php echo $row->image;?>
			</a>
			</div>
			<div class="row">
			<a href="index.php?option=site&bolum=group&task=view&id=<?php echo $row->id;?>">
			<?php echo $row->name;?>
			</a>
			</div>
			<div class="row">
			<?php echo $row->aciklama;?>
			</div>
			<div class="row">
			<small><?php echo $row->totaluser;?> Üye</small>
			</div>
			</div>
			</div>
			<?php
		}
			
		?>
		</div>
		</div>
		
		<div align="center">
		<div class="row">
		<?php echo $pageNav->writePagesCounter();?>
		</div>
		<div class="row">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=group&task=all');?>
		</div>
		</div>
		
		</div>
		</div>
		<?php
	}
	
	
}
