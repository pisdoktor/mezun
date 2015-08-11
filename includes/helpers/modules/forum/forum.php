<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunForum {
	/**
	* Forum ana sayfası
	*/
	static function ForumIndex() {
		global $my, $dbase, $limit;
		
		$most_recent_topic = array(
		'timestamp' => 0,
		'ref' => null
		);
		
		$query = "SELECT c.name AS catName, c.ID_CAT, b.ID_BOARD, b.name AS boardName, "
		. "\n b.aciklama, b.numPosts, b.numTopics, t.numReplies, b.ID_PARENT, "
		. "\n IFNULL(m.posterTime, 0) AS posterTime, mem.username AS posterName, "
		. "\n m.subject, m.ID_TOPIC, mem.name AS realName, "
		. "\n (IFNULL(lb.ID_MSG, 0) >= b.ID_MSG_UPDATED) AS isRead, "
		. "\n (IFNULL(lb.ID_MSG, -1) + 1) AS new_from, IFNULL(mem.id, 0) AS ID_MEMBER, m.ID_MSG "
		. "\n FROM #__forum_boards AS b "
		. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT = b.ID_CAT "
		. "\n LEFT JOIN #__forum_messages AS m ON m.ID_MSG = b.ID_LAST_MSG "
		. "\n LEFT JOIN #__forum_topics AS t ON t.ID_LAST_MSG=b.ID_LAST_MSG"
		. "\n LEFT JOIN #__users AS mem ON mem.id = m.ID_MEMBER "
		. "\n LEFT JOIN #__forum_log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = ".$my->id.")"
		. "\n ORDER BY c.catOrder ASC"
		;
	
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		// Run through the categories and boards....
		$context['categories'] = array();
		
		foreach ($rows as $row_board) {
		// Haven't set this category yet.
if (empty($context['categories'][$row_board['ID_CAT']])) {
	$context['categories'][$row_board['ID_CAT']] = array(
	'id' => $row_board['ID_CAT'],
	'name' => $row_board['catName'],
	'href' => sefLink('index.php?option=site&bolum=forum#cat' . $row_board['ID_CAT']),
	'boards' => array(),
	'new' => false
	);
	$context['categories'][$row_board['ID_CAT']]['link'] = '<a id="cat' . $row_board['ID_CAT'] . '" href="' . $context['categories'][$row_board['ID_CAT']]['href'] . '">' . $row_board['catName'] . '</a>';
}

// Let's save some typing.  Climbing the array might be slower, anyhow.
$this_category = &$context['categories'][$row_board['ID_CAT']]['boards'];

		// This is a parent board.
if (empty($row_board['ID_PARENT'])) {
			// Is this a new board, or just another moderator?
if (!isset($this_category[$row_board['ID_BOARD']])) {
				// Not a child.
$isChild = false;

$this_category[$row_board['ID_BOARD']] = array(
'new' => empty($row_board['isRead']),
'id' => $row_board['ID_BOARD'],
'name' => $row_board['boardName'],
'aciklama' => $row_board['aciklama'],
'children' => array(),
'link_children' => array(),
'children_new' => false,
'topics' => $row_board['numTopics'],
'posts' => $row_board['numPosts'],
'href' => sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']),
'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']).'">' . $row_board['boardName'] . '</a>'
);
}
}
		// Found a child board.... make sure we've found its parent and the child hasn't been set already.
elseif (isset($this_category[$row_board['ID_PARENT']]['children']) && !isset($this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']])) {
			// A valid child!
$isChild = true;

$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']] = array(
'id' => $row_board['ID_BOARD'],
'name' => $row_board['boardName'],
'aciklama' => $row_board['aciklama'],
'new' => empty($row_board['isRead']) && $row_board['posterName'] != '',
'topics' => $row_board['numTopics'],
'posts' => $row_board['numPosts'],
'href' => sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']),
'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']).'">' . $row_board['boardName'] . '</a>'
);

// Counting child board posts is... slow :/.
if (countChildPosts) {
$this_category[$row_board['ID_PARENT']]['posts'] += $row_board['numPosts'];
$this_category[$row_board['ID_PARENT']]['topics'] += $row_board['numTopics'];
}

			// Does this board contain new boards?
$this_category[$row_board['ID_PARENT']]['children_new'] |= empty($row_board['isRead']);

			// This is easier to use in many cases for the theme....
$this_category[$row_board['ID_PARENT']]['link_children'][] = &$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['link'];
}
		// Child of a child... just add it on...
elseif (countChildPosts) {
if (!isset($parent_map))
$parent_map = array();

if (!isset($parent_map[$row_board['ID_PARENT']]))
foreach ($this_category as $id => $board) {
if (!isset($board['children'][$row_board['ID_PARENT']]))
continue;

$parent_map[$row_board['ID_PARENT']] = array(&$this_category[$id], &$this_category[$id]['children'][$row_board['ID_PARENT']]);
$parent_map[$row_board['ID_BOARD']] = array(&$this_category[$id], &$this_category[$id]['children'][$row_board['ID_PARENT']]);

break;
}

if (isset($parent_map[$row_board['ID_PARENT']])) {
$parent_map[$row_board['ID_PARENT']][0]['posts'] += $row_board['numPosts'];
$parent_map[$row_board['ID_PARENT']][0]['topics'] += $row_board['numTopics'];
$parent_map[$row_board['ID_PARENT']][1]['posts'] += $row_board['numPosts'];
$parent_map[$row_board['ID_PARENT']][1]['topics'] += $row_board['numTopics'];

continue;
}

continue;
}
		// Found a child of a child - skip.
else
continue;

		// Prepare the subject, and make sure it's not too long.
$row_board['short_subject'] = mezunGlobalHelper::shortText($row_board['subject'], 24);
$this_last_post = array(
'id' => $row_board['ID_MSG'],
'time' => $row_board['posterTime'] > 0 ? mezunGlobalHelper::timeformat($row_board['posterTime']) : 'N/A',
'timestamp' => mezunGlobalHelper::time_stamp($row_board['posterTime']),
'subject' => $row_board['short_subject'],
'member' => array(
	'id' => $row_board['ID_MEMBER'],
	'username' => $row_board['posterName'] != '' ? $row_board['posterName'] : 'N/A',
	'name' => $row_board['realName'],
	'href' => $row_board['posterName'] != '' && !empty($row_board['ID_MEMBER']) ? sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER']) : '',
	'link' => $row_board['posterName'] != '' ? (!empty($row_board['ID_MEMBER']) ? '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER']).'">' . $row_board['realName'] . '</a>' : $row_board['realName']) : 'N/A',
			),
'start' => '',
'topic' => $row_board['ID_TOPIC']
);

		// Provide the href and link.
if ($row_board['subject'] != '') {
$this_last_post['href'] = sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC']. ($row_board['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row_board['numReplies']/ $limit)) * $limit) : '') . '#new');
$this_last_post['link'] = '<a href="' . $this_last_post['href'] . '" title="' . $row_board['subject'] . '">' . $row_board['short_subject'] . '</a>';
} else {
$this_last_post['href'] = '';
$this_last_post['link'] = 'N/A';
}

		// Set the last post in the parent board.
if (empty($row_board['ID_PARENT']) || ($isChild && !empty($row_board['posterTime']) && $this_category[$row_board['ID_PARENT']]['last_post']['timestamp'] < mezunGlobalHelper::time_stamp($row_board['posterTime'])))
$this_category[$isChild ? $row_board['ID_PARENT'] : $row_board['ID_BOARD']]['last_post'] = $this_last_post;
		// Just in the child...?
if ($isChild) {
$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['last_post'] = $this_last_post;

			// If there are no posts in this board, it really can't be new...
$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['new'] &= $row_board['posterName'] != '';
}
		// No last post for this board?  It's not new then, is it..?
elseif ($row_board['posterName'] == '')
$this_category[$row_board['ID_BOARD']]['new'] = false;

// Determine a global most recent topic.
if (!empty($row_board['posterTime']) && mezunGlobalHelper::time_stamp($row_board['posterTime']) > $most_recent_topic['timestamp'])
$most_recent_topic = array(
'timestamp' => mezunGlobalHelper::time_stamp($row_board['posterTime']),
'ref' => &$this_category[$isChild ? $row_board['ID_PARENT'] : $row_board['ID_BOARD']]['last_post'],
);
}

	return $context['categories'];
	}
	/**
	* Bir board içerisindeki alt boardları alalım
	* 
	* @param mixed $id : board id 
	*/
	static function BoardIndex($id) {
		global $my, $dbase;
		/**
		* Alt forumları alalım
		* 
		* @var mixed
		*/
		$query = "SELECT b.ID_BOARD, b.name, b.aciklama, b.numTopics, b.numPosts, "
		. "\n mem.username AS posterName, m.posterTime, m.subject, m.ID_MSG, m.ID_TOPIC, "
		. "\n mem.name AS realName, "
		. "\n (IFNULL(lb.ID_MSG, 0) >= b.ID_MSG_UPDATED) AS isRead, "
		. "\n IFNULL(mem.id, 0) AS ID_MEMBER "
		. "\n FROM #__forum_boards AS b "
		. "\n LEFT JOIN #__forum_messages AS m ON (m.ID_MSG = b.ID_LAST_MSG) "
		. "\n LEFT JOIN #__users AS mem ON (mem.id = m.ID_MEMBER) "
		. "\n LEFT JOIN #__forum_log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = ".$my->id.")"
		. "\n WHERE b.ID_PARENT = ".$id;
		
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
	
		if (count($rows) != 0) {
			
		$theboards = array();
		
		foreach ($rows as $row_board) {
			
			if (!isset($context['boards'][$row_board['ID_BOARD']])) {
				$theboards[] = $row_board['ID_BOARD'];

				// Make sure the subject isn't too long.
				$short_subject = mezunGlobalHelper::shortText($row_board['subject'], 24);

				$context['boards'][$row_board['ID_BOARD']] = array(
					'id' => $row_board['ID_BOARD'],
					'last_post' => array(
						'id' => $row_board['ID_MSG'],
						'time' => $row_board['posterTime'] > 0 ? mezunGlobalHelper::timeformat($row_board['posterTime']) : 'N/A',
						'timestamp' => mezunGlobalHelper::time_stamp($row_board['posterTime']),
						'subject' => $short_subject,
						'member' => array(
							'id' => $row_board['ID_MEMBER'],
							'username' => $row_board['posterName'] != '' ? $row_board['posterName'] : 'N/A',
							'name' => $row_board['realName'],
							'href' => !empty($row_board['ID_MEMBER']) ? 'index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER'] : '',
							'link' => $row_board['posterName'] != '' ? (!empty($row_board['ID_MEMBER']) ? '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER'] . '">' . $row_board['realName'] . '</a>' : $row_board['realName']) : 'N/A',
						),
						'start' => 'new',
						'topic' => $row_board['ID_TOPIC'],
						'href' => $row_board['subject'] != '' ? sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC'] . '#new') : '',
						
						'link' => $row_board['subject'] != '' ? '<a href="'.sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC'] . '#new').'" title="' . $row_board['subject'] . '">' . $short_subject . '</a>' : ''
					),
					'new' => empty($row_board['isRead']) && $row_board['posterName'] != '',
					'name' => $row_board['name'],
					'aciklama' => $row_board['aciklama'],
					'children' => array(),
					'link_children' => array(),
					'children_new' => false,
					'topics' => $row_board['numTopics'],
					'posts' => $row_board['numPosts'],
					'href' => sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']),
					'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD']).'">' . $row_board['name'] . '</a>'
				);
			}
		}

		// Load up the child boards.
		$query = "SELECT b.ID_BOARD, b.ID_PARENT, b.name, b.aciklama, b.numTopics, b.numPosts, "
		. "\n mem.username AS posterName, IFNULL(m.posterTime, 0) AS posterTime, m.subject, m.ID_MSG, "
		. "\n m.ID_TOPIC, mem.name AS realName, ID_PARENT, " 
		. "\n (IFNULL(lb.ID_MSG, 0) >= b.ID_MSG_UPDATED) AS isRead, "
		. "\n IFNULL(mem.id, 0) AS ID_MEMBER "
		. "\n FROM #__forum_boards AS b "
		. "\n LEFT JOIN #__forum_messages AS m ON (m.ID_MSG = b.ID_LAST_MSG) "
		. "\n LEFT JOIN #__users AS mem ON (mem.id = m.ID_MEMBER) "
		. "\n LEFT JOIN #__forum_log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = ".$my->id.")"
		;
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
		$parent_map = array();
		
		foreach ($rows as $row) {
			// We've got a child of a child, then... possibly.
			if (!in_array($row['ID_PARENT'], $theboards)) {
				if (!isset($parent_map[$row['ID_PARENT']]))
					continue;

				$parent_map[$row['ID_PARENT']][0]['posts'] += $row['numPosts'];
				$parent_map[$row['ID_PARENT']][0]['topics'] += $row['numTopics'];
				$parent_map[$row['ID_PARENT']][1]['posts'] += $row['numPosts'];
				$parent_map[$row['ID_PARENT']][1]['topics'] += $row['numTopics'];
				$parent_map[$row['ID_BOARD']] = $parent_map[$row['ID_PARENT']];

				continue;
			}

			if ($context['boards'][$row['ID_PARENT']]['last_post']['timestamp'] < mezunGlobalHelper::time_stamp($row['posterTime'])) {
				// Make sure the subject isn't too long.
				$short_subject = mezunGlobalHelper::shortText($row['subject'], 24);

				$context['boards'][$row['ID_PARENT']]['last_post'] = array(
					'id' => $row['ID_MSG'],
					'time' => $row['posterTime'] > 0 ? mezunGlobalHelper::timeformat($row['posterTime']) : 'N/A',
					'timestamp' => mezunGlobalHelper::time_stamp($row['posterTime']),
					'subject' => $short_subject,
					'member' => array(
						'username' => $row['posterName'] != '' ? $row['posterName'] : 'N/A',
						'name' => $row['realName'],
						'id' => $row['ID_MEMBER'],
						'href' => !empty($row['ID_MEMBER']) ? sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER']) : '',
						'link' => $row['posterName'] != '' ? (!empty($row['ID_MEMBER']) ? '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER']).'">' . $row['realName'] . '</a>' : $row['realName']) : 'N/A',
					),
					'start' => '#new',
					'topic' => $row['ID_TOPIC'],
					'href' => sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . '#new')
				);
				$context['boards'][$row['ID_PARENT']]['last_post']['link'] = $row['subject'] != '' ? '<a href="' . $context['boards'][$row['ID_PARENT']]['last_post']['href'] . '" title="' . $row['subject'] . '">' . $short_subject . '</a>' : 'N/A';
			}
			$context['boards'][$row['ID_PARENT']]['children'][$row['ID_BOARD']] = array(
				'id' => $row['ID_BOARD'],
				'name' => $row['name'],
				'aciklama' => $row['aciklama'],
				'new' => empty($row['isRead']) && $row['posterName'] != '',
				'topics' => $row['numTopics'],
				'posts' => $row['numPosts'],
				'href' => sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD']),
				'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD']).'">' . $row['name'] . '</a>'
			);
			$context['boards'][$row['ID_PARENT']]['link_children'][] = '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD']).'">' . $row['name'] . '</a>';
			$context['boards'][$row['ID_PARENT']]['children_new'] |= empty($row['isRead']) && $row['posterName'] != '';

			if (countChildPosts) {
				$context['boards'][$row['ID_PARENT']]['posts'] += $row['numPosts'];
				$context['boards'][$row['ID_PARENT']]['topics'] += $row['numTopics'];

				$parent_map[$row['ID_BOARD']] = array(&$context['boards'][$row['ID_PARENT']], &$context['boards'][$row['ID_PARENT']]['children'][$row['ID_BOARD']]);
			}
		}
		
		return $context['boards'];
		}
	}
	/**
	* Bir board içerisindeki topicleri alalım
	* 
	* @param mixed $id : board id
	* @param mixed $topicstart : topic başlangıcı
	* @param mixed $topiclimit : topic limiti
	* @param mixed $limitstart : sayfa başlangıcı
	* @param mixed $limit : sayfa limiti
	*/
	static function BoardTopics($id, $topicstart, $topiclimit, $limitstart, $limit) {
		global $my, $dbase;
		
		$query = "SELECT t.ID_TOPIC, t.numReplies, t.locked, t.icon, t.numViews, t.isSticky, "
		. "\n IFNULL(lt.ID_MSG, IFNULL(lmr.ID_MSG, -1)) + 1 AS new_from, "
		. "\n t.ID_LAST_MSG, ml.posterTime AS lastPosterTime, ml.ID_MSG_MODIFIED, "
		. "\n ml.subject AS lastSubject, meml.name AS lastMemberName, "
		. "\n ml.ID_MEMBER AS lastID_MEMBER, meml.name AS lastDisplayName, "
		. "\n t.ID_FIRST_MSG, mf.posterTime AS firstPosterTime, "
		. "\n mf.subject AS firstSubject, memf.name AS firstMemberName, "
		. "\n mf.ID_MEMBER AS firstID_MEMBER, memf.name AS firstDisplayName, "
		. "\n LEFT(ml.body, 384) AS lastBody, LEFT(mf.body, 384) AS firstBody "
		. "\n FROM (#__forum_topics AS t, #__forum_messages AS ml, #__forum_messages AS mf) "
		. "\n LEFT JOIN #__users AS meml ON (meml.id = ml.ID_MEMBER) "
		. "\n LEFT JOIN #__users AS memf ON (memf.id = mf.ID_MEMBER) " 
		. "\n LEFT JOIN #__forum_log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = ".$my->id.")"
		. "\n LEFT JOIN #__forum_log_mark_read AS lmr ON (lmr.ID_BOARD = ".$id." AND lmr.ID_MEMBER = ".$my->id.")"
		. "\n WHERE t.ID_BOARD = ".$id." AND ml.ID_MSG = t.ID_LAST_MSG AND mf.ID_MSG = t.ID_FIRST_MSG "
		. "\n ORDER BY t.isSticky DESC, ml.posterTime DESC";
		
		$dbase->setQuery($query, $limitstart, $limit);
		$rows = $dbase->loadAssocList();
		
		foreach ($rows as $row) {
		
		// Limit them to 128 characters - do this FIRST because it's a lot of wasted censoring otherwise.
			if (strlen($row['firstBody']) > 128)
				$row['firstBody'] = substr($row['firstBody'], 0, 128) . '...';

			if (strlen($row['lastBody']) > 128)
				$row['lastBody'] = substr($row['lastBody'], 0, 128) . '...';

			// Don't censor them twice!
			if ($row['ID_FIRST_MSG'] == $row['ID_LAST_MSG'])
			{
				$row['lastSubject'] = $row['firstSubject'];
				$row['lastBody'] = $row['firstBody'];
			}
			// 'Print' the topic info.
			$context['topics'][$row['ID_TOPIC']] = array(
				'id' => $row['ID_TOPIC'],
				'first_post' => array(
					'id' => $row['ID_FIRST_MSG'],
					'member' => array(
						'username' => $row['firstMemberName'],
						'name' => $row['firstDisplayName'],
						'id' => $row['firstID_MEMBER'],
						'href' => !empty($row['firstID_MEMBER']) ? sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['firstID_MEMBER']) : '',
						'link' => !empty($row['firstID_MEMBER']) ? '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['firstID_MEMBER']).'" title="' . $row['firstDisplayName'] . '">' . $row['firstDisplayName'] . '</a>' : $row['firstDisplayName']
					),
					'time' => mezunGlobalHelper::timeformat($row['firstPosterTime']),
					'timestamp' => mezunGlobalHelper::time_stamp($row['firstPosterTime']),
					'subject' => $row['firstSubject'],
					'preview' => $row['firstBody'],
					'href' => sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC']),
					'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC']).'">' . $row['firstSubject'] . '</a>'
				),
				'last_post' => array(
					'id' => $row['ID_LAST_MSG'],
					'member' => array(
						'username' => $row['lastMemberName'],
						'name' => $row['lastDisplayName'],
						'id' => $row['lastID_MEMBER'],
						'href' => !empty($row['lastID_MEMBER']) ? sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['lastID_MEMBER']) : '',
						'link' => !empty($row['lastID_MEMBER']) ? '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['lastID_MEMBER']).'">' . $row['lastDisplayName'] . '</a>' : $row['lastDisplayName']
					),
					'time' => mezunGlobalHelper::timeformat($row['lastPosterTime']),
					'timestamp' => mezunGlobalHelper::time_stamp($row['lastPosterTime']),
					'subject' => $row['lastSubject'],
					'preview' => $row['lastBody'],
					//son mesaja link verebilmeyi yapalım
					'href' => sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new'),
					'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new').'">' . $row['lastSubject'] . '</a>'
				),
				'is_sticky' => !empty($row['isSticky']),
				'is_locked' => !empty($row['locked']),
				'is_hot' => $row['numReplies'] >= hotTopicPosts,
				'is_very_hot' => $row['numReplies'] >= hotTopicVeryPosts,
				'is_posted_in' => false,
				'icon' => $row['icon'] ? $row['icon'] : 'xx',
				'subject' => $row['firstSubject'],
				'new' => $row['new_from'] <= $row['ID_MSG_MODIFIED'],
				'new_from' => $row['new_from'],
				'newtime' => $row['new_from'],
				'new_href' => sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new'),
				'replies' => $row['numReplies'],
				'views' => $row['numViews'],
				'pages' => ($row['numReplies'] > $limit ? 'Sayfalar:'.mezunForumHelper::constructPageIndex('index.php?option=site&bolum=forum&task=topic&id='.$row['ID_TOPIC'], $row['numReplies'], $topicstart, $topiclimit) : '')
			);
		}
		
		if (isset($context['topics'])) {
			return $context['topics']; 
		} else {
			return false;
		}		
	}
	/**
	* Bir topic içerisindeki mesajları alalım
	* 
	* @param mixed $id : topic id
	* @param mixed $topicstart : topic başlangıcı
	* @param mixed $topiclimit : topic limiti
	*/
	static function TopicIndex($id, $topicstart, $topiclimit) {
		global $my, $dbase;
		
		$dbase->setQuery("SELECT m.*, t.ID_LAST_MSG, u.myili, u.lastvisit, u.image, u.name as posterName, u.cinsiyet, s.name AS sehirAdi 
		FROM #__forum_messages AS m 
		LEFT JOIN #__forum_topics AS t ON t.ID_TOPIC=m.ID_TOPIC
		LEFT JOIN #__users AS u ON u.id=m.ID_MEMBER 
		LEFT JOIN #__sehirler AS s ON s.id=u.sehir
		WHERE m.ID_TOPIC=".$dbase->Quote($id)." ORDER BY m.posterTime ASC", $topicstart, $topiclimit);
		$rows = $dbase->loadObjectList();
		
		foreach ($rows as $row) {
		if (!isset($context['topic'])) {
			$context['topic'] = array(
			'ID_TOPIC' => $row->ID_TOPIC,
			'ID_BOARD' => $row->ID_BOARD,
			'lastMsg' => $row->ID_LAST_MSG,
			'messages' => array()
			);    
		}    
		
		if (!isset($context['topic']['messages'][$row->ID_MSG])) {
			$context['topic']['messages'][$row->ID_MSG] = array (
			'id' => $row->ID_MSG,
			'time' => mezunGlobalHelper::timeformat($row->posterTime),
			'timestamp' => mezunGlobalHelper::time_stamp($row->posterTime),
			'member' => array(
				'id' => $row->ID_MEMBER,
				'name' => $row->posterName,
				'href' => sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER),
				'link' => '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id='.$row->ID_MEMBER).'">'.$row->posterName.'</a>',
				'cinsiyet' => $row->cinsiyet == 1 ? 'Erkek' : 'Bayan',
				'profilimage' => $row->image ? SITEURL.'/images/profil/'.$row->image : SITEURL.'/images/profil/noimage.png',
				'imagelink' => $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" width="100" height="100" />' : '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" width="100" height="100" />',
				'sehir' => $row->sehirAdi,
				'ip' => $row->posterIP,
				'lastvisit' => $row->lastvisit,
				'mezuniyet' => $row->myili
			),
			'subject' => $row->subject,
			'body' => $row->body
			);
		}
		
		} //end foreach
		
		return $context['topic'];
	}
	/**
	* Board hakkında bilgileri alalım
	* 
	* @param mixed $oid
	*/
	static function BoardInfo($id) {
		global $dbase;
		
		$dbase->setQuery("SELECT b.*, c.name AS catname FROM #__forum_boards AS b "
		. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT=b.ID_CAT "
		. "\n WHERE b.ID_BOARD=".$dbase->Quote($id));
		
		$dbase->loadObject($board_info);
		
		$board_info->parent_boards = mezunForumHelper::getBoardParents($board_info->ID_BOARD);
	
		return $board_info;
	}
	/**
	* Topic hakkında bilgileri alalım
	* 
	* @param mixed $id
	*/
	static function TopicInfo($id) {
		global $my, $dbase;
		
		$dbase->setQuery("SELECT t.ID_TOPIC, t.ID_BOARD, t.icon, t.numReplies, t.numViews, t.locked, ms.subject, t.isSticky, t.ID_FIRST_MSG, t.ID_LAST_MSG, IFNULL(lt.ID_MSG, -1) + 1 AS new_from
	FROM (#__forum_topics AS t, #__forum_messages AS ms)
	LEFT JOIN #__forum_log_topics AS lt ON (lt.ID_TOPIC = ".$dbase->Quote($id)." AND lt.ID_MEMBER = ".$dbase->Quote($my->id).")
	WHERE t.ID_TOPIC = ".$dbase->Quote($id)." AND ms.ID_MSG = t.ID_FIRST_MSG LIMIT 1");
	
		$dbase->loadObject($topic_info);
		
		return $topic_info;
	}
	/**
	* Mesajlar içerisindeki en son mesaj id değerini alalım
	* 
	*/
	static function maxMsgID() {
		global $dbase;
		
		$dbase->setQuery("SELECT MAX(ID_MSG) FROM #__forum_messages");
		return $dbase->loadResult();
	}
	/**
	* En son gönderilen mesajları alalım
	* 
	* @param mixed $showlatestcount : Kaç tane mesaj gösterileceği
	* @param mixed $limit : sayfalandırma için limit değeri
	*/
	static function latestMessages($showlatestcount=5, $limit) {
		global $dbase;
		
	$query = "
		SELECT
			m.posterTime, m.subject, m.ID_TOPIC, m.ID_MEMBER, m.ID_MSG,
			mem.name AS posterName, t.ID_BOARD, b.name AS bName,
			LEFT(m.body, 384) AS body, t.numReplies
		FROM (#__forum_messages AS m, #__forum_topics AS t, #__forum_boards AS b)
			LEFT JOIN #__users AS mem ON (mem.id = m.ID_MEMBER)
		WHERE m.ID_MSG >= " . max(0, mezunForum::maxMsgID() - 20 * $showlatestcount) . "
			AND t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
		ORDER BY m.ID_MSG DESC
		LIMIT ".$showlatestcount;
		$dbase->setQuery($query);
		$rows = $dbase->loadAssocList();
		
	$posts = array();
	
	foreach ($rows as $row) {
		if (strlen($row['body']) > 128)
			$row['body'] = substr($row['body'], 0, 128) . '...';

		// Build the array.
		$posts[] = array(
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['bName'],
				'href' => sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD']),
				'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD']).'">' . $row['bName'] . '</a>'
			),
			'topic' => $row['ID_TOPIC'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER']),
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="'.sefLink('index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER']).'">' . $row['posterName'] . '</a>'
			),
			'subject' => $row['subject'],
			'short_subject' => mezunGlobalHelper::shortText($row['subject'], 24),
			'preview' => $row['body'],
			'time' => mezunGlobalHelper::timeformat($row['posterTime']),
			'timestamp' => mezunGlobalHelper::time_stamp($row['posterTime']),
			'raw_timestamp' => $row['posterTime'],
			'href' => sefLink('index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC']. ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new'),
			'link' => '<a href="'.sefLink('index.php?option=site&bolum=forum&task=topic&id='.$row['ID_TOPIC']. ($row['numReplies'] > $limit ? '&limit='.$limit.'&limitstart='.((floor($row['numReplies']/ $limit)) * $limit) : '') . '#new').'">' . $row['subject'] . '</a>'
		);
	}

	return $posts;
	}
}
