<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class BoardCategories extends DBTable {
	
	var $ID_CAT = null;
	
	var $name = null;
	
	var $catOrder = null;
	
	function BoardCategories(&$db) {
		$this->DBTable( '#__forum_categories', 'ID_CAT', $db );
	}
	
	/**
	* Forum ana sayfası için sorgu
	*/
	function ForumIndex() {
		global $my;
		
		$most_recent_topic = array(
		'timestamp' => 0,
		'ref' => null
		);
		
		$query = "SELECT c.name AS catName, c.ID_CAT, b.ID_BOARD, b.name AS boardName, "
		. "\n b.aciklama, b.numPosts, b.numTopics, b.ID_PARENT, "
		. "\n IFNULL(m.posterTime, 0) AS posterTime, mem.username AS posterName, "
		. "\n m.subject, m.ID_TOPIC, mem.name AS realName, "
		. "\n (IFNULL(lb.ID_MSG, 0) >= b.ID_MSG_UPDATED) AS isRead, "
		. "\n (IFNULL(lb.ID_MSG, -1) + 1) AS new_from, IFNULL(mem.id, 0) AS ID_MEMBER, m.ID_MSG "
		. "\n FROM #__forum_boards AS b "
		. "\n LEFT JOIN #__forum_categories AS c ON c.ID_CAT = b.ID_CAT "
		. "\n LEFT JOIN #__forum_messages AS m ON m.ID_MSG = b.ID_LAST_MSG "
		. "\n LEFT JOIN #__users AS mem ON mem.id = m.ID_MEMBER "
		. "\n LEFT JOIN #__forum_log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = ".$my->id.")"
		. "\n ORDER BY c.catOrder ASC"
		;
	
		$this->_db->setQuery($query);
		$result_boards = $this->_db->query();
		
			
	// Run through the categories and boards....
	$context['categories'] = array();
	while ($row_board = mysql_fetch_assoc($result_boards)) {
		// Haven't set this category yet.
if (empty($context['categories'][$row_board['ID_CAT']])) {
	$context['categories'][$row_board['ID_CAT']] = array(
	'id' => $row_board['ID_CAT'],
	'name' => $row_board['catName'],
	'href' => 'index.php?option=site&bolum=forum#cat' . $row_board['ID_CAT'],
	'boards' => array(),
	'new' => false
	);
	$context['categories'][$row_board['ID_CAT']]['link'] = '<a name="' . $row_board['ID_CAT'] . '" href="' . $context['categories'][$row_board['ID_CAT']]['href'] . '">' . $row_board['catName'] . '</a>';
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
'href' => 'index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'],
'link' => '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'] . '">' . $row_board['boardName'] . '</a>'
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
'href' => 'index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'],
'link' => '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'] . '">' . $row_board['boardName'] . '</a>'
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
$row_board['short_subject'] = Forum::shorten_subject($row_board['subject'], 24);
$this_last_post = array(
'id' => $row_board['ID_MSG'],
'time' => $row_board['posterTime'] > 0 ? Forum::timeformat($row_board['posterTime']) : 'N/A',
'timestamp' => Forum::forum_time($row_board['posterTime']),
'subject' => $row_board['short_subject'],
'member' => array(
'id' => $row_board['ID_MEMBER'],
'username' => $row_board['posterName'] != '' ? $row_board['posterName'] : 'N/A',
'name' => $row_board['realName'],
'href' => $row_board['posterName'] != '' && !empty($row_board['ID_MEMBER']) ? 'index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER'] : '',
'link' => $row_board['posterName'] != '' ? (!empty($row_board['ID_MEMBER']) ? '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row_board['ID_MEMBER'] . '">' . $row_board['realName'] . '</a>' : $row_board['realName']) : 'N/A',
			),
'start' => '',
'topic' => $row_board['ID_TOPIC']
);

		// Provide the href and link.
if ($row_board['subject'] != '') {
$this_last_post['href'] = 'index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC'];
$this_last_post['link'] = '<a href="' . $this_last_post['href'] . '" title="' . $row_board['subject'] . '">' . $row_board['short_subject'] . '</a>';
} else {
$this_last_post['href'] = '';
$this_last_post['link'] = 'N/A';
}

		// Set the last post in the parent board.
if (empty($row_board['ID_PARENT']) || ($isChild && !empty($row_board['posterTime']) && $this_category[$row_board['ID_PARENT']]['last_post']['timestamp'] < Forum::forum_time($row_board['posterTime'])))
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
if (!empty($row_board['posterTime']) && Forum::forum_time($row_board['posterTime']) > $most_recent_topic['timestamp'])
$most_recent_topic = array(
'timestamp' => Forum::forum_time($row_board['posterTime']),
'ref' => &$this_category[$isChild ? $row_board['ID_PARENT'] : $row_board['ID_BOARD']]['last_post'],
);
}
mysql_free_result($result_boards);
	
return $context['categories'];
			
}
	/**
	* Bir board içerisine girince varsa alt kategorilerin sorgusu
	* 
	* @param mixed $id : board id değeri
	*/
	function Board($id, $limitstart, $limit) {
		global $my;
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
		
		$this->_db->setQuery($query);
		$result = $this->_db->query();
	
		if (mysql_num_rows($result) != 0) {
		$theboards = array();
		while ($row_board = mysql_fetch_assoc($result)) {
			
			if (!isset($context['boards'][$row_board['ID_BOARD']])) {
				$theboards[] = $row_board['ID_BOARD'];

				// Make sure the subject isn't too long.
				$short_subject = Forum::shorten_subject($row_board['subject'], 24);

				$context['boards'][$row_board['ID_BOARD']] = array(
					'id' => $row_board['ID_BOARD'],
					'last_post' => array(
						'id' => $row_board['ID_MSG'],
						'time' => $row_board['posterTime'] > 0 ? Forum::timeformat($row_board['posterTime']) : 'N/A',
						'timestamp' => Forum::forum_time($row_board['posterTime']),
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
						'href' => $row_board['subject'] != '' ? 'index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC'] . '#new' : '',
						
						'link' => $row_board['subject'] != '' ? '<a href="index.php?option=site&bolum=forum&task=topic&id=' . $row_board['ID_TOPIC'] . '#new" title="' . $row_board['subject'] . '">' . $short_subject . '</a>' : ''
					),
					'new' => empty($row_board['isRead']) && $row_board['posterName'] != '',
					'name' => $row_board['name'],
					'aciklama' => $row_board['aciklama'],
					'children' => array(),
					'link_children' => array(),
					'children_new' => false,
					'topics' => $row_board['numTopics'],
					'posts' => $row_board['numPosts'],
					'href' => 'index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'] . '',
					'link' => '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row_board['ID_BOARD'] . '">' . $row_board['name'] . '</a>'
				);
			}
		}
		mysql_free_result($result);

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
		$this->_db->setQuery($query);
		$result = $this->_db->query();
		
		$parent_map = array();
		while ($row = mysql_fetch_assoc($result)) {
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

			if ($context['boards'][$row['ID_PARENT']]['last_post']['timestamp'] < Forum::forum_time($row['posterTime'])) {
				// Make sure the subject isn't too long.
				$short_subject = Forum::shorten_subject($row['subject'], 24);

				$context['boards'][$row['ID_PARENT']]['last_post'] = array(
					'id' => $row['ID_MSG'],
					'time' => $row['posterTime'] > 0 ? Forum::timeformat($row['posterTime']) : 'N/A',
					'timestamp' => Forum::forum_time($row['posterTime']),
					'subject' => $short_subject,
					'member' => array(
						'username' => $row['posterName'] != '' ? $row['posterName'] : 'N/A',
						'name' => $row['realName'],
						'id' => $row['ID_MEMBER'],
						'href' => !empty($row['ID_MEMBER']) ? 'index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER'] : '',
						'link' => $row['posterName'] != '' ? (!empty($row['ID_MEMBER']) ? '<a href="index.php?option=site&bolum=profil&task=show&id=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>' : $row['realName']) : 'N/A',
					),
					'start' => 'new',
					'topic' => $row['ID_TOPIC'],
					'href' => 'index.php?option=site&bolum=forum&task=topic&id=' . $row['ID_TOPIC'] . '#new'
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
				'href' => 'index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD'],
				'link' => '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD'] . '">' . $row['name'] . '</a>'
			);
			$context['boards'][$row['ID_PARENT']]['link_children'][] = '<a href="index.php?option=site&bolum=forum&task=board&id=' . $row['ID_BOARD'] . '">' . $row['name'] . '</a>';
			$context['boards'][$row['ID_PARENT']]['children_new'] |= empty($row['isRead']) && $row['posterName'] != '';

			if (countChildPosts) {
				$context['boards'][$row['ID_PARENT']]['posts'] += $row['numPosts'];
				$context['boards'][$row['ID_PARENT']]['topics'] += $row['numTopics'];

				$parent_map[$row['ID_BOARD']] = array(&$context['boards'][$row['ID_PARENT']], &$context['boards'][$row['ID_PARENT']]['children'][$row['ID_BOARD']]);
			}
		}
		return $context['boards'];
	}
	mysql_free_result($result);
	
	}
}