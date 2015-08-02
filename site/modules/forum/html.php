<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class ForumHTML {
	
	static function newMessage($topic, $my, $topic_info, $board_info) {
		?>
		<script type="text/javascript">
		$(document).ready(function() {
				$('#textarea').summernote({
					lang : 'tr-TR',
					height: 150,
					minHeight: 100,
					maxHeight: 500,
					focus: true,
					toolbar: [
	//[groupname, [button list]]
	 
	['style', ['bold', 'italic', 'underline', 'clear']],
	['font', ['strikethrough', 'superscript', 'subscript']],
	['fontsize', ['fontsize']],
	['color', ['color']],
	['para', ['ul', 'ol', 'paragraph']],
	['height', ['height']],
	['insert', ['link']]
  ]
				});
			});
		</script>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo mezunForumHelper::forumBreadCrumb($board_info);?> - <small><?php echo $topic_info->subject;?></small></div>
		<div class="panel-body">
		
		<form action="index.php?option=site&bolum=forum&task=savemessage" method="post" role="form">
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-8">
		<input type="text" name="subject" value="Cvp: <?php echo $topic_info->subject;?>" placeholder="Mesajınızın başlığı" class="form-control" required>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<textarea id="textarea" rows="5" name="body" class="form-control"></textarea>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">
		<button type="submit" class="btn btn-primary">MESAJI GÖNDER</button>
		</div>
		</div>
		</div>
		
		<input type="hidden" name="ID_BOARD" value="<?php echo $topic_info->ID_BOARD;?>">
		<input type="hidden" name="ID_TOPIC" value="<?php echo $topic_info->ID_TOPIC;?>">
		</form>
		
		</div>
		<div class="panel-footer"></div>
		</div>
		<?php
		
	}
	
	static function newTopic($board, $my, $board_info, $list) {
		?>
		<script type="text/javascript">
		var icon_urls = {
				'xx': '<?php echo SITEURL;?>/images/forum/xx.gif',
				'thumbup': '<?php echo SITEURL;?>/images/forum/thumbup.gif',
				'thumbdown': '<?php echo SITEURL;?>/images/forum/thumbdown.gif',
				'exclamation': '<?php echo SITEURL;?>/images/forum/exclamation.gif',
				'question': '<?php echo SITEURL;?>/images/forum/question.gif',
				'lamp': '<?php echo SITEURL;?>/images/forum/lamp.gif',
				'smiley': '<?php echo SITEURL;?>/images/forum/smiley.gif',
				'angry': '<?php echo SITEURL;?>/images/forum/angry.gif',
				'cheesy': '<?php echo SITEURL;?>/images/forum/cheesy.gif',
				'grin': '<?php echo SITEURL;?>/images/forum/grin.gif',
				'sad': '<?php echo SITEURL;?>/images/forum/sad.gif',
				'wink': '<?php echo SITEURL;?>/images/forum/wink.gif',
				'solved': '<?php echo SITEURL;?>/images/forum/solved.gif'
			};
			function showimage() {
				document.images.icons.src = icon_urls[document.form.icon.options[document.form.icon.selectedIndex].value];
			}
			
			$(document).ready(function() {
				$('#textarea').summernote({
					lang : 'tr-TR',
					height: 150,
					minHeight: 100,
					maxHeight: 500,
					focus: true,
					toolbar: [
	//[groupname, [button list]]
	 
	['style', ['bold', 'italic', 'underline', 'clear']],
	['font', ['strikethrough', 'superscript', 'subscript']],
	['fontsize', ['fontsize']],
	['color', ['color']],
	['para', ['ul', 'ol', 'paragraph']],
	['height', ['height']],
	['insert', ['link']]
  ]
				});
			});
		</script>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo mezunForumHelper::forumBreadCrumb($board_info);?> - <small>Yeni Başlık</small></div>
		<div class="panel-body">
		
		<form action="index.php?option=site&bolum=forum&task=savetopic" method="post" name="form" role="form">
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-8">
		<input type="text" id="subject" name="subject" placeholder="Mesajınızın konusu" class="form-control" required>
		</div>
		</div>
		</div>
		
		<div class="form-group">
		
		<div class="row">
		
		<div class="col-sm-3">
		<?php echo $list['icons'];?>
		</div>
		
		<div class="col-sm-1">
		<img src="<?php echo SITEURL;?>/images/forum/xx.gif" name="icons" hspace="15" alt="" />
		</div>
		
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
		<div class="col-sm-12">
		<textarea id="textarea" rows="10" name="body" class="form-control"></textarea>
		</div>
		</div>
		</div>
		
		
		<?php if ($my->id == 1) { ?>
		<div class="form-group">
		<div class="row">
		<div class="col-sm-2">
		<label for="locked">Kilitli</label>
		</div>
		<div class="col-sm-1">
		<input id="locked" type="checkbox" name="locked" value="1" class="form-control" />
		</div>
		</div>
		
		<div class="row">
		<div class="col-sm-2">
		<label for="sticky">Yapışkan</label>
		</div>
		<div class="col-sm-1">
		<input id="sticky" type="checkbox" name="isSticky" value="1" class="form-control" />
		</div>
		
		</div>
		</div>		 
		 <?php } ?>
		 
		<div class="form-group">
		<div class="row">
		<div class="col-sm-5">
		<button type="submit" class="btn btn-primary">BAŞLIĞI OLUŞTUR</button>
		</div>
		</div>
		</div>
		
		<input type="hidden" name="ID_BOARD" value="<?php echo $board_info->ID_BOARD;?>">
		</form>
		</div>
		<div class="panel-footer"></div>
		</div>
		<?php
	}
	
	static function TopicSeen($context, $pageNav, $topic_info, $board_info, $topiclink) {
		
		$topic_icon =  '<img src="'.SITEURL.'/images/forum/'.$topic_info->icon.'.gif" alt="" title="" />'; 
		
		?>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo mezunForumHelper::forumBreadCrumb($board_info);?> - <small><?php echo $topic_info->subject;?></small></div>
		<div class="panel-body">
		
		<div class="row">
		<div class="col-sm-10">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=topic&id='.$topic_info->ID_TOPIC);?>
		</div>
		<div class="col-sm-2">
		<?php if (!$topic_info->locked) {?>
		<a href="<?php echo sefLink('index.php?option=site&bolum=forum&task=newmessage&topic='.$topic_info->ID_TOPIC);?>" class="btn btn-default btn-sm">Yeni Mesaj</a>
		<?php } ?>
		</div>
		</div>
		
		<table class="table table-striped">
		<thead>
		<tr class="titlebg">
		<th width="15%">
		Gönderen
		</th>
		<th width="85%" align="left">
		Başlık: <?php echo $topic_icon;?> <?php echo $topic_info->subject;?>
		</th>
		<th>
		<?php if (mezunForumHelper::canDeleteTopic($topic_info->ID_TOPIC)) {?>
		<div class="dropdown">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> 
			<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><?php echo $topiclink['sticky'];?></li>
				<li><?php echo $topiclink['lock'];?></li>
				<li><?php echo $topiclink['delete'];?></li>
			</ul>
			</div>
			<?php }?>
		</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$t = 0;		
		foreach ($context['topic']['messages'] as $row) {
			//message links
			$msglink = array();
			if (mezunForumHelper::canEditMessage($row['id'])) {
				$msglink['edit'] = '<a href="index.php?option=site&bolum=forum&task=editmessage&id='.$row['id'].'">Düzenle</a>';
			} else {
				$msglink['edit'] = '';
			}
			
			if (mezunForumHelper::canDeleteMessage($row['id'])) {
				if ($row['id'] == $topic_info->ID_FIRST_MSG) {
					$msglink['delete'] = '';
				} else {
					$msglink['delete'] = '<a href="index.php?option=site&bolum=forum&task=deletemessage&id='.$row['id'].'">Mesajı Sil</a>';
				}
			} else {
				$msglink['delete'] = '';
			}
			
			if ($row['id'] == $context['topic']['lastMsg']) {
				$new = 'id="new"';
			} else {
				$new = '';
			}
			?>
			
			<tr class="windowbg<?php echo $t;?>" <?php echo $new;?>>
			<td width="15%" valign="top" height="100%">
			
			<div align="center" class="msg-profil">
			<a href="<?php echo $row['member']['href'];?>">
			<?php echo $row['member']['imagelink'];?>
			</a>
			<br />
			<?php echo $row['member']['link'];?>
			<br />
			<?php echo $row['member']['cinsiyet'];?>
			<br />
			<?php echo $row['member']['sehir'];?>
			<br />
			M. Yılı: <?php echo $row['member']['mezuniyet'];?>
			<div align="center"><?php mezunOnlineHelper::isOnline($row['member']['id']);?></div>
			</div>
			
			</td>
			<td valign="top" width="85%" height="100%">
			<div class="msg-info">
			<small><?php echo $row['subject'];?></small><br />
			<small>Gönderim Tarihi: <?php echo $row['time'];?></small>
			<small>#<?php echo $row['id'];?></small>
			</div>
			
			<div class="msg-body">
			<?php echo mezunForumHelper::makeHTML($row['body']);?>
			</div>
			
			</td>
			<td>
			<?php if (mezunForumHelper::canEditMessage($row['id'])) {?>
			<div class="dropdown">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
			<span class="glyphicon glyphicon-cog"></span> 
			<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><?php echo $msglink['edit'];?></li>
				<li><?php echo $msglink['delete'];?></li>
			</ul>
			</div>
			<?php } ?>
			</td>
			</tr>
			
			<?php
			$t = 1- $t;
		}
		?>
		</tbody>
		</table>
		
		<div class="row">
		<div class="col-sm-10">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=topic&id='.$topic_info->ID_TOPIC);?>
		</div>
		<div class="col-sm-2">
		<?php if (!$topic_info->locked) {?>
		<a href="<?php echo sefLink('index.php?option=site&bolum=forum&task=newmessage&topic='.$topic_info->ID_TOPIC);?>" class="btn btn-default btn-sm">Yeni Mesaj</a>
		<?php } ?>
		</div>
		</div>

		
		</div>
		
		<div class="panel-footer"></div>
		</div>
<?php	
	}
	
	static function BoardSeen($context, $pageNav, $board_info) {
		global $my;
		?>
		<div class="panel panel-default">
		<div class="panel-heading"><?php echo mezunForumHelper::forumBreadCrumb($board_info);?> - <small><?php echo $board_info->aciklama;?></small></div>
		<div class="panel-body">
		
		<?php
		if (isset($context['boards'])) {
		?>
		<table class="table table-striped">
		<thead>
			<tr>
				<th colspan="4" class="catbg">Alt Kategoriler</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($context['boards'] as $board) {
		?>	
			<tr>
			<td <?php echo !empty($board['children']) ? 'rowspan="2"' : '';?> class="windowbg" width="6%" align="center" valign="top">
			<?php
			// If the board is new, show a strong indicator.
			if ($board['new'])
				echo '<img src="'.SITEURL.'/images/forum/on.png" alt="Yeni Mesaj Var" title="Yeni Mesaj Var" />';
			// This board doesn't have new posts, but its children do.
			elseif ($board['children_new'])
				echo '<img src="'.SITEURL.'/images/forum/on2.png" alt="Yeni Mesaj Var" title="Yeni Mesaj Var" />';
			// No new posts at all! The agony!!
			else
				echo '<img src="'.SITEURL.'/images/forum/off.png" alt="Yeni Mesaj Yok" title="Yeni Mesaj Yok" />';
				?>
				</td>
				
				<td class="windowbg2">
					<b>
					<a href="<?php echo $board['href'];?>" name="b<?php echo $board['id'];?>">
					<?php echo $board['name'];?>
					</a>
					</b>
					<br />
					<?php echo $board['aciklama'];?>
				</td>
				
				<td class="windowbg" valign="middle" align="center" style="width: 12ex;">
				<small>
					<?php echo $board['posts'];?> mesaj <br />
					<?php echo $board['topics'];?> başlık</small>
				</td>
				
				<td class="windowbg2" valign="middle" width="22%"><small>

			<?php
			if (!empty($board['last_post']['id']))
				echo '
					<b>Son Mesaj</b><br /> Gönderen ', $board['last_post']['member']['link'] , '<br />
					İçerik ', $board['last_post']['link'], '<br />
					Zaman ', $board['last_post']['time'];
					?>
				</small>
				</td>
			</tr>
			<?php

			// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
			if (!empty($board['children'])) {
				// Sort the links into an array with new boards bold so it can be imploded.
				$children = array();
				/* Each child in each board's children has:
					id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
				foreach ($board['children'] as $child) {
					$child['link'] = '<a href="' . $child['href'] . '" title="' . ($child['new'] ? 'Yeni Mesaj Var' : 'Yeni Mesaj Yok') . ' (Yeni Mesaj Var: ' . $child['topics'] . ', Mesajlar: ' . $child['posts'] . ')">' . $child['name'] . '</a>';
					
					$children[] = $child['new'] ? '<b>' . $child['link'] . '</b>' : $child['link'];
				}
				?>
			<tr>
				<td colspan="3" class="windowbg">
					<small><b>Alt Kategoriler</b>: <?php echo implode(', ', $children);?></small>
				</td>
			</tr>
			<?php
			}
		}
	?>
		</tbody>
		</table>
		<?php
		}
		?>
		
		<div class="row">
		<div class="col-sm-10">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=board&id='.$board_info->ID_BOARD);?>
		</div>
		<div class="col-sm-2">
		<a href="<?php echo sefLink('index.php?option=site&bolum=forum&task=newtopic&board='.$board_info->ID_BOARD);?>" class="btn btn-default btn-sm">Yeni Başlık</a>
		</div>
		</div>

		<table class="table table-striped">
		<thead>
		<tr>
		<?php
		if (!empty($context['topics'])) {
			?>
			<th width="4%" class="catbg3"></th>
			<th width="5%" class="catbg3"></th>
			<th class="catbg3">Başlık</th>
			<th class="catbg3" width="11%">Başlatan</th>
			<th class="catbg3" width="4%" align="center">Mesaj</th>
			<th class="catbg3" width="4%" align="center">Okunma</th>
			<th class="catbg3" width="22%">Son Mesaj</th>
			<?php
		} else {
			?>
			<th class="catbg2" width="100%" colspan="7"><b>Henüz bir başlık açılmamış</b></th>
			<?php
		}
			?>
		</tr>
		</thead>
		<tbody>
			<?php
		if (!empty($context['topics'])) {			
		foreach ($context['topics'] as $topic) {
			$image_locked = $topic['is_locked'] ? '<img src="'.SITEURL.'/images/forum/locked_topic.png" alt="Başlık Kilitli" title="Başlık Kilitli" />' : '';
			$image_sticky = $topic['is_sticky'] ? '<img src="'.SITEURL.'/images/forum/sticky.png" alt="Başlık Yapışkan" title="Başlık Yapışkan" />' : ''; 
			
			$topic_icon = '<img src="'.SITEURL.'/images/forum/'.$topic['icon'].'.gif" alt="" title="" />';
			?>
			<tr>
			
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle" align="center" width="4%">
			<?php echo $topic_icon;?>
			</td>
			
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle" align="center" width="5%">
		   <?php echo $image_locked;?> <?php echo $image_sticky;?>
			</td>
		
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle">
			<?php
			echo $topic['is_sticky'] ? '<b>' : '' , '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], '</span>', $topic['is_sticky'] ? '</b>' : '';
			
			
			
			// Is this topic new? (assuming they are logged in!)
			if ($topic['new']) {
			echo '<a href="', $topic['new_href'], '" id="newicon' . $topic['first_post']['id'] . '">
			<img src="'.SITEURL.'/images/forum/yeni.png" alt="Yeni Mesaj Var" />
			</a>';
			}
			
			echo  '<br /><small>'.$topic['pages'].'</small>';
			?>
			</td>		
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle" width="14%">
			<?php echo $topic['first_post']['member']['link'];?>
			</td>
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3' : '';?>" valign="middle" width="4%" align="center">
			<?php echo $topic['replies'];?>
			</td>
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3' : '';?>" valign="middle" width="4%" align="center">
			<?php echo $topic['views'];?>
			</td>
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle" width="22%">
			<a href="<?php echo $topic['last_post']['href'];?>">
			<img src="<?php echo SITEURL;?>/images/forum/last_post.gif" alt="Son Mesaj" title="Son Mesaj" style="float: right;" />
			</a>
			<span class="smalltext">
			<?php echo $topic['last_post']['time'], '<br />
			Gönderen ', $topic['last_post']['member']['link'];?>
			</span>
			</td>
			</tr>
			<?php
		}
	}
	?>
		</tbody>
		</table>
		<div class="row">
		<div class="col-sm-10">
		<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=board&id='.$board_info->ID_BOARD);?>
		</div>
		<div class="col-sm-2">
		<a href="<?php echo sefLink('index.php?option=site&bolum=forum&task=newtopic&board='.$board_info->ID_BOARD);?>" class="btn btn-default btn-sm">Yeni Başlık</a>
		</div>
		</div>
	
		</div>
		<div class="panel-footer"></div>
		</div>
		<?php
		
	}
	
	static function BoardIndex($context) {
		?>
		<div class="panel panel-default">
		<div class="panel-heading">FORUM</div>
		<div class="panel-body">
		
		<table class="table table-striped">
		<thead>	
			<tr class="titlebg">
				<th colspan="2">Forum Adı</th>
				<th width="6%" align="center">Başlıklar</th>
				<th width="6%" align="center">Mesajlar</th>
				<th width="22%" align="center">Son Mesaj</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		
		foreach ($context['categories'] as $category) {
		?>
		<tr>
		<td colspan="5" class="catbg">
		<?php echo $category['link'];?>
		</td>
		</tr>
		<?php
		foreach ($category['boards'] as $board) {
			$image = $board['new'] ? 'on.png' : 'off.png';
			$text = $board['new'] ? 'Yeni Mesaj Var' : 'Yeni Mesaj Yok';
			?>
			<tr>
			
			<td class="windowbg" width="6%" align="center" valign="top">
			<img src="<?php echo SITEURL;?>/images/forum/<?php echo $image;?>" alt="<?php echo $text;?>" title="<?php echo $text;?>" border="0" />
			</td>
			
			<td class="windowbg2" align="left" width="60%">
			<a id="b<?php echo $board['id'];?>"></a>
			<b><?php echo $board['link'];?></b><br />
			<?php echo $board['aciklama'];?>
			<?php
				if (!empty($board['children']))	{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
						id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
				foreach ($board['children'] as $child)
						$children[] = $child['new'] ? '<b>' . $child['link'] . '</b>' : $child['link'];
					echo '<i class="smalltext"><br />Alt Forumlar: ', implode(', ', $children), '</i>';
				}
				?>
				
		</td>
		
		<td class="windowbg" valign="middle" align="center" width="6%"><?php echo $board['topics'];?></td>
		
		<td class="windowbg" valign="middle" align="center" width="6%"><?php echo $board['posts'];?></td>

		<td class="windowbg2" valign="middle" width="22%">
			<span class="smalltext">
				<?php echo $board['last_post']['time'];?><br />
				Başlık <?php echo $board['last_post']['link'];?><br />
				Gönderen <?php echo $board['last_post']['member']['link'];?>
			</span>
		</td>
		
	</tr>
	<?php
	}
		}
		?>
		</tbody>
		</table>
		<?php
		
		if ($context['latestmsg']) {
		?>
		
	<div class="tborder" style="width: 100%;">
	 <div class="catbg" style="padding: 6px; vertical-align: middle; text-align: center;">
	 Forum - Bilgi Merkezi
	 </div>
		<table class="table table-striped">
		<thead>
		<tr>
		<th class="titlebg" colspan="4">Son Gönderilen <?php echo latestPostCount;?> Mesaj</th>
		</tr>
		</thead>
		<tbody>
		<?php
			foreach ($context['latestmsg'] as $post) {
				?>
			<tr>
			<td class="middletext" valign="top"><b><?php echo $post['link'];?></b></td>
			<td><?php echo $post['poster']['link'];?></td>
			<td><?php echo $post['board']['link'];?></td>
			<td class="middletext" align="right" valign="top"><?php echo $post['time'];?></td>
			</tr>
		<?php 
			} 
		?>
		
		</tbody>
		</table>
		
		<table class="table table-striped">
		<thead>
		<tr>
		<th class="titlebg">Şuanda Forumda Olan Üyeler</th>
		</tr>
		</thead>
		<tbody>
			<tr>
			<td class="middletext" nowrap="nowrap">
			<?php echo implode(', ', mezunForumHelper::getForumUsers());?>
			</td>
			</tr>
		</tbody>
		</table>
	</div>
		<?php
	}
		?>
		</div>
		<div class="panel-footer"></div>
		</div>
		<?php
	}
}