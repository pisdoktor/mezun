<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

$id = intval(getParam($_REQUEST, 'id'));
$limit = intval(getParam($_REQUEST, 'limit', 10));
$limitstart = intval(getParam($_REQUEST, 'limitstart', 0));

include(dirname(__FILE__). '/html.php');

switch($task) {
	default:
	Templates();
	break;
	
	case 'editcss':
	editTemplateCSS($id);
	break;
	
	case 'delete':
	deleteTemplate($id);
	break;
	
	case 'new':
	newTemplate();
	break;
	
	case 'save':
	saveTemplate();
	break;
	
	case 'savecss':
	saveCssFile();
	break;
}

function saveCssFile() {
	
}

function editTemplateCSS($id) {
	
}

function deleteTemplate($id) {
	global $dbase;
	
}

function saveTemplate() {
	global $dbase;
	
	$temp_type = getParam($_POST, 'temp_type');
	
	$tempfile = getParam($_FILES, 'tempfile');
	
	$dest = ABSPATH.'/'.$temp_type.'/templates/';
	
	$foldername = substr($tempfile['name'], 0, strpos($tempfile['name'], '.'));
	
	mimport('helpers.file.file');
	mimport('helpers.file.archive');
	
	if (mezunFile::move($tempfile['tmp_name'], $dest.'/'.$tempfile['name'])) {
		
		if (mezunArchive::extract($dest.'/'.$tempfile['name'], $dest)) {
			
			mezunFile::delete($dest.'/'.$tempfile['name']);
			
			$dbase->setQuery("INSERT INTO #__templates (name, temp_type) VALUES (".$dbase->Quote($foldername).", ".$dbase->Quote($temp_type).")");
			$dbase->query();
			
		}
		
	}
	
	Redirect('index.php?option=admin&bolum=templates');
}

function newTemplate() {
	global $dbase;
	
	$type = array();
	$type[] = mezunHTML::makeOption('site', 'Site Teması');
	$type[] = mezunHTML::makeOption('admin', 'Yönetim Teması');
	
	$lists['temp_type'] = mezunHTML::selectList($type, 'temp_type', 'size="2" id="temp_type"', 'value', 'text');
	
	
	adminTemplatesHTML::newTemplate($lists);
}

function Templates() {
	global $dbase, $limitstart, $limit;
	
	$dbase->setQuery("SELECT * FROM #__templates ORDER BY temp_type");
	$rows = $dbase->loadObjectList();
	
	$total = count($rows);
	
	$pageNav = new mezunPagenation($total, $limitstart, $limit);
	
	$list = array_slice($rows, $limitstart, $limit);
	
	adminTemplatesHTML::Templates($list, $pageNav);
}

