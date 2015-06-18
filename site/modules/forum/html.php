<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class ForumHTML {
	
	static function BoardSeen($context, $pageNav, $boardid) {
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
			<a href="index.php?option=site&bolum=forum&task=unread&id=<?php echo $board['id'];?>">
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
				</a>
				</td>
				<td class="windowbg2">
					<b>
					<a href="<?php echo $board['href'];?>" name="b<?php echo $board['id'];?>"><?php echo $board['name'];?>
					</a>
					</b>
					<br />
					<?php echo $board['aciklama'];?>
				</td>
				<td class="windowbg" valign="middle" align="center" style="width: 12ex;"><small>
					<?php echo $board['posts'];?> mesaj <br />
					<?php echo $board['topics'];?> başlık</small>
				</td>
				<td class="windowbg2" valign="middle" width="22%"><small>';

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
					<small><b>Alt Kategoriler</b>: <?php implode(', ', $children);?></small>
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
					<small><?php echo $context['topics']['aciklama'];?></small>
				</td>
			</tr>
		</table>
		<?php
		// Are there actually any topics to show?
		if (!empty($context['topics'])) {
				echo '
						<td class="catbg3" width="4%" valign="middle" align="center"></td>';
		}
		// No topics.... just say, "sorry bub".
		else
			echo '
						<td class="catbg3" width="100%" colspan="7"><b>Henüz bir başlık açılmamış</b></td>';

		echo '
					</tr>';
					
		foreach ($context['topics'] as $topic) {
			// Do we want to seperate the sticky and lock status out?	
			echo '
					<tr>
						<td class="windowbg2" valign="middle" align="center" width="5%">
							<img src="" alt="" />
						</td>
						<td class="windowbg2" valign="middle" align="center" width="4%">
							
						</td>
						<td class="windowbg3" valign="middle">';

			if (!empty($settings['seperate_sticky_lock']))
				echo '
							' , $topic['is_locked'] ? '<img src="' . $settings['images_url'] . '/icons/quick_lock.gif" align="right" alt="" id="lockicon' . $topic['first_post']['id'] . '" style="margin: 0;" />' : '' , '
							' , $topic['is_sticky'] ? '<img src="' . $settings['images_url'] . '/icons/show_sticky.gif" align="right" alt="" id="stickyicon' . $topic['first_post']['id'] . '" style="margin: 0;" />' : '';

			echo '
							', $topic['is_sticky'] ? '<b>' : '' , '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], '</span>', $topic['is_sticky'] ? '</b>' : '';

			// Is this topic new? (assuming they are logged in!)
			if ($topic['new'])
					echo '
							<a href="', $topic['new_href'], '" id="newicon' . $topic['first_post']['id'] . '"><img src="new.gif" alt="Yeni" /></a>';

			echo '
							<small id="pages' . $topic['first_post']['id'] . '">Sayfalar:</small>
						</td>
						<td class="windowbg2" valign="middle" width="14%">
							', $topic['first_post']['member']['link'], '
						</td>
						<td class="windowbg' , $topic['is_sticky'] ? '3' : '' , '" valign="middle" width="4%" align="center">
							', $topic['replies'], '
						</td>
						<td class="windowbg' , $topic['is_sticky'] ? '3' : '' , '" valign="middle" width="4%" align="center">
							', $topic['views'], '
						</td>
						<td class="windowbg2" valign="middle" width="22%">
							<a href="', $topic['last_post']['href'], '"><img src="last_post.gif" alt="Son Mesaj" title="Son Mesaj" style="float: right;" /></a>
							<span class="smalltext">
								', $topic['last_post']['time'], '<br />
								Gönderen ', $topic['last_post']['member']['link'], '
							</span>
						</td>';
			echo '
					</tr>';
		}
		echo '
				</table>';
				
				?>
				<div id="newtopicwindow">
		<form action="index.php?option=site&bolum=forum&task=newtopic" method="post">
		<input type="text" name="subject">
		<textarea cols="50" rows="5" name="body"></textarea>
		<input type="submit" value="Gönder">
		<input type="hidden" name="ID_BOARD" value="<?php echo $boardid;?>">
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
