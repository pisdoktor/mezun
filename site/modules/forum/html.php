<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class ForumHTML {
	
	static function TopicSeen($rows, $pageNav, $topic_info) {
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="bordercolor">
		<?php
		
		
		
		?>
		<tr>
		<td style="padding: 0 0 1px 0;"></td>
		</tr>
</table>
<a name="lastPost"></a>
<div>
<?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=topic&id='.$topic_info->ID_TOPIC);?>
</div>
				<?php if (!$topic_info->locked) {?>
				<a href="#" class="newmsg">Yeni Mesaj</a>
		<div id="newmessagewindow">
		<form action="index.php?option=site&bolum=forum&task=newmessage" method="post">
		<input type="text" name="subject" value="Cvp:<?php echo $topic_info->subject;?>" placeholder="Mesajınızın başlığı" class="inputbox" required ><br />
		<textarea cols="50" rows="5" name="body" placeholder="Mesajınızın içeriği" class="textbox" required></textarea><br />
		<input type="submit" value="MESAJI GÖNDER" class="button">
		<input type="hidden" name="ID_BOARD" value="<?php echo $topic_info->ID_BOARD;?>">
		<input type="hidden" name="ID_TOPIC" value="<?php echo $topic_info->ID_TOPIC;?>">
		</form>
		</div>
		<?php } ?>
<?php	
	}
	
	static function BoardSeen($context, $pageNav, $board_info) {
		global $my;
		if (isset($context['boards'])) {
		?>
		<table border="0" width="100%" cellspacing="1" cellpadding="5" class="bordercolor">
			<tr>
				<td colspan="4" class="catbg">Alt Kategoriler</td>
			</tr>
		<?php
		foreach ($context['boards'] as $board) {
		?>	
			<tr>
			<td <?php echo !empty($board['children']) ? 'rowspan="2"' : '';?> class="windowbg" width="6%" align="center" valign="top">
			<?php
			// If the board is new, show a strong indicator.
			if ($board['new'])
				echo '<img src="'.SITEURL.'/images/on.png" alt="Yeni Mesaj Var" title="Yeni Mesaj Var" />';
			// This board doesn't have new posts, but its children do.
			elseif ($board['children_new'])
				echo '<img src="'.SITEURL.'/images/on2.png" alt="Yeni Mesaj Var" title="Yeni Mesaj Var" />';
			// No new posts at all! The agony!!
			else
				echo '<img src="'.SITEURL.'/images/off.png" alt="Yeni Mesaj Yok" title="Yeni Mesaj Yok" />';
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
					<b>Son Mesaj</b> Gönderen ', $board['last_post']['member']['link'] , '<br />
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
		</table>
		<?php
		}
		?>
		<table width="100%" cellpadding="6" cellspacing="1" border="0" class="tborder" style="padding: 0; margin-bottom: 2ex;">
			<tr>
				<td class="titlebg2" width="100%" height="24" style="border-top: 0;">
					<small><?php echo $board_info->aciklama;?></small>
				</td>
			</tr>
		</table>
		<a href="#" class="newtopic">Yeni Başlık</a>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
					<tr>
		<?php
		if (!empty($context['topics'])) {
			?>
			<td width="9%" class="catbg3"></td>
			<td class="catbg3">Başlık</td>
			<td class="catbg3" width="11%">Başlatan</td>
			<td class="catbg3" width="4%" align="center">Mesaj</td>
			<td class="catbg3" width="4%" align="center">Okunma</td>
			<td class="catbg3" width="22%">Son Mesaj</td>
			<?php
		} else {
			?>
			<td class="catbg3" width="100%" colspan="7"><b>Henüz bir başlık açılmamış</b></td>
			<?php
		}
			?>
			</tr>
			<?php
		if (!empty($context['topics'])) {			
		foreach ($context['topics'] as $topic) {
			$image_locked = $topic['is_locked'] ? '<img src="'.SITEURL.'/images/locked_topic.png" alt="Başlık Kilitli" title="Başlık Kilitli" />' : '<img src="'.SITEURL.'/images/unlocked_topic.png" alt="Başlık Kilitli Değil" title="Başlık Kilitli Değil" />';
			$image_sticky = $topic['is_sticky'] ? '<img src="'.SITEURL.'/images/sticky.png" alt="Başlık Yapışkan" title="Başlık Yapışkan" />' : ''; 
			?>
			<tr>
			
			<td class="windowbg2" valign="middle" align="center" width="9%">
			<?php echo $image_locked;?> <?php echo $image_sticky;?>
			</td>
		
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3': '2';?>" valign="middle">
			<?php
			echo $topic['is_sticky'] ? '<b>' : '' , '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], '</span>', $topic['is_sticky'] ? '</b>' : '';
			// Is this topic new? (assuming they are logged in!)
			if ($topic['new']) {
			echo '<a href="', $topic['new_href'], '" id="newicon' . $topic['first_post']['id'] . '"><img src="'.SITEURL.'/images/yeni.png" alt="Yeni Mesaj Var" /></a>';
			}
			?>
			</td>		
			<td class="windowbg2" valign="middle" width="14%">
			<?php echo $topic['first_post']['member']['link'];?>
			</td>
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3' : '';?>" valign="middle" width="4%" align="center">
			<?php echo $topic['replies'];?>
			</td>
			<td class="windowbg<?php echo $topic['is_sticky'] ? '3' : '';?>" valign="middle" width="4%" align="center">
			<?php echo $topic['views'];?>
			</td>
			<td class="windowbg2" valign="middle" width="22%">
			<a href="<?php echo $topic['last_post']['href'];?>">
			<img src="<?php echo SITEURL;?>/images/last_post.gif" alt="Son Mesaj" title="Son Mesaj" style="float: right;" />
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
		echo '</table>';
				?>
				<div><?php echo $pageNav->writePagesLinks('index.php?option=site&bolum=forum&task=board&id='.$board_info->ID_BOARD);?></div>
				
		<a href="#" class="newtopic">Yeni Başlık</a>
		<div id="newtopicwindow">
		<form action="index.php?option=site&bolum=forum&task=newtopic" method="post">
		<input type="text" name="subject" placeholder="Mesajınızın başlığı" class="inputbox" required ><br />
		<textarea cols="50" rows="5" name="body" placeholder="Mesajınızın içeriği" class="textbox" required></textarea><br />
		<?php if ($my->id == 1) {
		 ?>
		 <label for="locked">Kilitli</label><input id="locked" type="checkbox" name="locked" value="1" class="checkbox" />
		 <label for="sticky">Yapışkan</label><input id="sticky" type="checkbox" name="isSticky" value="1" class="checkbox" />
		 <?php   
		}
		?>
		<input type="submit" value="MESAJI GÖNDER" class="button">
		<input type="hidden" name="ID_BOARD" value="<?php echo $board_info->ID_BOARD;?>">
		</form>
		</div>
		<?php
		
	}
	
	static function BoardIndex($context) {
		?>
		<table border="0" width="100%" cellspacing="1" cellpadding="5" class="bordercolor">
	<tr class="titlebg">
		<td colspan="2">Forum Adı</td>
		<td width="6%" align="center">Başlıklar</td>
		<td width="6%" align="center">Mesajlar</td>
		<td width="22%" align="center">Son Mesaj</td>
	</tr>
		<?php 
		
		foreach ($context['categories'] as $category) {
			?>
		<tr>
		<td colspan="5" class="catbg" height="18">
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
		<img src="<?php echo SITEURL;?>/images/<?php echo $image;?>" alt="<?php echo $text;?>" title="<?php echo $text;?>" border="0" />
		</td>
		<td class="windowbg2" align="left" width="60%">
			<a name="b<?php echo $board['id'];?>"></a>
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
					echo '
			<i class="smalltext"><br />
			Alt Forumlar: ', implode(', ', $children), '</i>';
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
	
	echo '</table>';
		
	}
	
}
