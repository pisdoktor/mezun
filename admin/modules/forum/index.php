<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

$cid = mosGetParam($_REQUEST, 'cid');
$id = intval(mosGetParam($_REQUEST, 'id'));  
$limit = intval(mosGetParam($_REQUEST, 'limit', 30));
$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

switch($task) {
	default:
	getBoardList();
	break;
	
	case 'newboard':
	editBoard($id);
	break;
	
	case 'editboard':
	editBoard($id);
	break;
	
	case 'deleteboard':
	deleteBoard($cid);
	break;
	
	case 'saveboard':
	saveBoard();
	break;
}

function getBoardList() {
	global $dbase, $limit, $limitstart;
	
	$query = "SELECT * FROM #__forum";
	$dbase->setQuery($query);
	
	$rows = $dbase->loadObjectList();
	
	// establish the hierarchy of the menu
	$children = array();
	// first pass - collect children
	foreach ($rows as $v ) {
		$pt = $v->parent;
		$list = @$children[$pt] ? $children[$pt] : array();
		array_push( $list, $v );
		$children[$pt] = $list;
	}
	// second pass - get an indent list of the items
	$list = treeRecurse( 0, '', array(), $children);
	
	var_dump($list);
	
	$total = count( $list );

	$pageNav = new PageNav( $total, $limitstart, $limit  );

	// slice out elements based on limits
	$list = array_slice( $list, $limitstart, $limit );
}
