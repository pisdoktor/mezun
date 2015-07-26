<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class GroupHTML {
	
	static function showGroupMembers($group, $rows, $pageNav, $list, $other) {
		$status = $group->status ? 'KAPALI GRUP':'AÇIK GRUP';
		
		$groupimage = $group->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/group/'.$group->image.'" width="55" height="55" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
		
		if ($group->isGroupMember()) {
			$joinlink = '<a href="'.sefLink('index.php?option=site&bolum=group&task=leave&id='.$group->id).'" class="btn btn-default btn-sm">Gruptan Ayrıl</a>';
		} else {
			if ($group->canJoinGroup()) {
			$joinlink = '<a href="'.sefLink('index.php?option=site&bolum=group&task=join&id='.$group->id).'" class="btn btn-default btn-sm">Gruba Katıl</a>';    
			} else {
				$joinlink = 'Grup kapalı ve siz katılamazsınız';
			}
		}
		
		$editlink = $group->canEditGroup() ? '<a href="'.sefLink('index.php?option=site&bolum=group&task=edit&id='.$group->id).'" class="btn btn-default btn-sm">Grubu Düzenle</a>':'';
		
		$deletelink = $group->canDeleteGroup() ? '<a href="'.sefLink('index.php?option=site&bolum=group&task=delete&id='.$group->id).'" class="btn btn-default btn-sm">Grubu Sil</a>':'';
		
		$addnewmember = ($group->isGroupMember() && $group->status && $other) ? '[ <a href="#" id="newmember">Yeni Üye Ekle</a> ]':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading">GRUP : <?php echo $group->name;?> [<?php echo $status;?>]<small><?php echo $group->aciklama;?></small></div>
		<div class="panel-body">
		
		<div class="row">
			<div class="col-sm-7">
			<div class="panel panel-warning">
		<div class="panel-heading">Grup Üyeleri <?php echo $addnewmember;?></div>
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
		<script type="text/javascript">
		$(document).ready(function() {
			$('.modsend').click(function (event){
				
				$('.modsend').attr('disabled', true);
				
				$.ajax({
					type    : 'POST',
					url     : $(this).attr('url'),
					dataType: 'json',
					encode  : true
				})
						
				.done(function(data) {
					console.log(data);
					$('.modsend').removeAttr('disabled');
					$('.modstyle-'+data['userid']).html(data['style']);
					$('.moderator-'+data['userid']).replaceWith(data['message']);
					
				});
				
				event.preventDefault();
			});
		});
		</script>
		<?php
		  for($i=0;$i<count($rows);$i++) {
			  $row = $rows[$i];
			  $creator = $row->userid == $row->creatorid ? '<small style="color:red">(Kurucu)</small>':'';
			  
			  $admin = $row->isadmin ? '<small style="color:blue;" class="modstyle-'.$row->userid.'">Grup Moderatörü</small>': '<small style="color:blue;" class="modstyle-'.$row->userid.'"></small>';
			  
			  $row->name = '<a href="'.sefLink('index.php?option=site&bolum=profil&task=view&id='.$row->userid).'">'.$row->name.'</a>';	
			  if ($group->canEditGroup() && $row->userid !== $row->creatorid) {
				$moderatorlink = $row->isadmin ? '<a url="index2.php?option=site&bolum=group&task=getmod&groupid='.$group->id.'&userid='.$row->userid.'" class="btn btn-default btn-xs modsend moderator-'.$row->userid.'">Görevi Al</a>':'<a url="index2.php?option=site&bolum=group&task=setmod&groupid='.$group->id.'&userid='.$row->userid.'" class="btn btn-default btn-xs modsend moderator-'.$row->userid.'">Moderatör Yap</a>';    
			  } else {
				$moderatorlink = '';
			  }
			  
			  ?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-7">
		<?php echo $moderatorlink;?> <?php echo $row->name;?> <?php echo $creator;?> <?php echo $admin;?>
		</div>
		<div class="col-sm-5">
		<?php echo mezunGlobalHelper::timeformat($row->joindate, true, true);?>
		</div>
		</div>
		</div>
		<?php  
		  }
		  ?>		
		</div>
		<div class="panel-footer">
		<div align="center">
		  <?php  echo $pageNav->writePagesLinks('index.php?option=site&bolum=group&task=showmembers&id='.$group->id);?>
		  </div>
		</div>
		</div>
		
		</div>
		
		<div class="col-sm-5">
			<div class="panel panel-default">
		<div class="panel-heading">Grup Bilgisi</div>
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
		<div class="col-sm-7"><?php echo mezunGlobalHelper::timeformat($group->creationdate, true, true);?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Toplam Üye:</div>
		<div class="col-sm-7"><a href="<?php echo sefLink('index.php?option=site&bolum=group&task=showmembers&id='.$group->id);?>"><?php echo $group->totalmember;?></a> Üye</div>
		</div>
		</div>        
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Moderatörler:</div>
		<div class="col-sm-7"><?php echo $group->admins;?></div>
		</div>
		</div>

		
		<div class="form-group" align="center">
		<div class="btn-group-vertical">
		<?php echo $joinlink;?><?php echo $editlink;?> <?php echo $deletelink;?>
		</div>
		</div>
		
		</div>
		</div>
			</div>
			</div>
		
		
		
		</div>
		</div>
		
		<div id="addnewmember" title="Gruba Arkadaşını Ekle">
		<form method="post" action="index.php?option=site&bolum=group&task=addmember" role="form">
		<?php echo $list['invite'];?>
		<input type="checkbox" name="isadmin" value="1" />
		<button class="btn btn-primary">Gruba Ekle</button>
		<input type="hidden" name="groupid" value="<?php echo $group->id;?>" />
		</form>
		</div>
		<?php		
	}
	
	static function editMyGroup($row, $list) {
		
		$groupimage = $row->image ? '<img src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading">GRUP : <?php echo $row->name;?> <?php echo $row->id ? 'DÜZENLE':'YENİ';?></div>
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
		
		$groupimage = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
		
		if ($row->isGroupMember()) {
			$joinlink = '<a href="'.sefLink('index.php?option=site&bolum=group&task=leave&id='.$row->id).'" class="btn btn-default btn-sm">Gruptan Ayrıl</a>';
		} else {
			if ($row->canJoinGroup()) {
			$joinlink = '<a href="'.sefLink('index.php?option=site&bolum=group&task=join&id='.$row->id).'" class="btn btn-default btn-sm">Gruba Katıl</a>';    
			} else {
				$joinlink = 'Grup kapalı ve siz katılamazsınız';
			}
		}
		
		$editlink = $row->canEditGroup() ? '<a href="'.sefLink('index.php?option=site&bolum=group&task=edit&id='.$row->id).'" class="btn btn-default btn-sm">Grubu Düzenle</a>':'';
		
		$deletelink = $row->canDeleteGroup() ? '<a href="'.sefLink('index.php?option=site&bolum=group&task=delete&id='.$row->id).'" class="btn btn-default btn-sm">Grubu Sil</a>':'';
		?>
		<div class="panel panel-info">
		<div class="panel-heading">GRUP : <?php echo $row->name;?> [<?php echo $status;?>]<small><?php echo $row->aciklama;?></small></div>
		<div class="panel-body">
		
			<div class="row">
			<div class="col-sm-7">
			<div class="panel panel-warning">
		<div class="panel-heading">Grup Mesajları</div>
		<div class="panel-body">
		<?php
			if (!$row->canViewGroup() && !$row->canJoinGroup()) {
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
						
						$('button[name=submit]').attr("disabled", "disabled");
						
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
							$('button[name=submit]').removeAttr("disabled");
						});
						
						event.preventDefault();
					});
				});
				</script>
				<form action="index2.php?option=site&bolum=group&task=send" method="post" role="form">
				
				<textarea rows="2" id="msgfield" maxlength="255" name="text" class="form-control" placeholder="Mesajınızı yazın" required></textarea>
				<div align="right"><small><span id="charNum">255</span></small></div>
				
				<button name="submit" class="btn btn-default btn-sm">Gönder</button>
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
					<small>Tarih: <?php echo mezunGlobalHelper::timeformat($msg->tarih, true, true);?></small>
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
		<div class="panel-heading">Grup Bilgisi</div>
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
		<div class="col-sm-7"><?php echo mezunGlobalHelper::timeformat($row->creationdate, true, true);?></div>
		</div>
		</div>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Toplam Üye:</div>
		<div class="col-sm-7"><a href="<?php echo sefLink('index.php?option=site&bolum=group&task=showmembers&id='.$row->id);?>"><?php echo $row->totalmember;?></a> Üye</div>
		</div>
		</div>		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">Moderatörler:</div>
		<div class="col-sm-7"><?php echo $row->admins;?></div>
		</div>
		</div>
		
		<div class="form-group" align="center">
		<div class="btn-group-vertical">
		<?php echo $joinlink;?> <?php echo $editlink;?> <?php echo $deletelink;?>
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
		<div class="panel-heading">ÜYE GRUPLARI</div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<?php
		
		foreach ($rows as $row) {
			
			$row->image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
			
			$row->name = mezunGlobalHelper::shortText($row->name, 20);
			
			$row->aciklama = mezunGlobalHelper::shortText($row->aciklama, 30);
			?>
			<div class="col-sm-3">
			<div align="center">
			<div class="row">
			<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
			<?php echo $row->image;?>
			</a>
			</div>
			<div class="row">
			<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
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
		
		</div>
		<div class="panel-footer">
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
		<div class="panel-heading">GRUPLARIM</div>
		<div class="panel-body">
		
		<div class="form-group">
		<div class="row">
		<?php
		
		foreach ($rows as $row) {
			$row->totaluser = mezunGroupHelper::getGroupUsers($row->id, true);
			
			$row->image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/group/'.$row->image.'" width="55" height="55" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/group/group.jpg" width="55" height="55" />';
			
			$row->name = mezunGlobalHelper::shortText($row->name, 20);
			
			$row->aciklama = mezunGlobalHelper::shortText($row->aciklama, 30);
			
			?>
			<div class="col-sm-3">
			<div align="center">
			<div class="row">
			<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
			<?php echo $row->image;?>
			</a>
			</div>
			<div class="row">
			<a href="<?php echo sefLink('index.php?option=site&bolum=group&task=view&id='.$row->id);?>">
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
		
		</div>
		<div class="panel-footer">
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
