<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

include(dirname(__FILE__). '/html.php');

mimport('helpers.modules.akis.helper');
mimport('tables.akis');

switch($task) {
	default:
	siteAkis();
	break;
	
	case 'next':
	nextAkis($limitstart, $limit);
	break;
	
	case 'send':
	sendAkisMsg();
	break;
}

/**
* Akışa yeni mesaj ekleme fonksiyonu
*/
function sendAkisMsg() {
	global $dbase, $my;
	
	$errors         = array();      // array to hold validation errors
	$data           = array();      // array to pass back data
	
	if (empty($_POST['text']))
		$errors['text'] = 'Mesaj girmemişsiniz.';
	
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['message']  = $errors;
	} else {
		
		$row = new mezunAkis($dbase);
		$row->text = strval(getParam($_POST, 'text'));
		$row->tarih = date('Y-m-d H:i:s');
		$row->userid = $my->id;
		
		$row->store();
		
		$image = $my->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$my->image.'" alt="'.$my->name.'" title="'.$my->name.'" width="50" height="50" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" alt="'.$my->name.'" title="'.$my->name.'" width="50" height="50" />';
		
		$veri = '';
		$veri.= '<div class="row">';
		
		$veri.= '<div class="col-sm-2">';
		$veri.= '<a href="index.php?option=site&bolum=profil&task=show&id='.$my->id.'">';
		$veri.= $image;
		$veri.= '</a>';
		$veri.= '</div>';
		
		$veri.= '<div class="col-sm-10">';
		
		$veri.= '<div class="row">';
		$veri.= mezunAkisHelper::getAkisTime($row->tarih);
		$veri.= '</div>';
		
		$veri.= '<div class="row">';
		$veri.= $my->name.': '.$row->text;
		$veri.= '</div>';
		
		$veri.= '</div>';
		
		$veri.= '</div>';
		$veri.= '<div align="right">';
		$veri.= '0 Beğeni';
		$veri.= '</div>';
		$veri.= '<hr>';
 
		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['message'] = $veri;
	}

	// return all our data to an AJAX call
	echo json_encode($veri);
	
}
/**
* Site akışını gösteren fonksiyon
*/
function siteAkis() {
	global $dbase, $my;
		
		$limit = 5;
		
		$dbase->setQuery("SELECT a.*, u.name, u.image, u.id as userid FROM #__akis AS a"
		. "\n LEFT JOIN #__users AS u ON u.id=a.userid ORDER BY a.tarih DESC LIMIT ".$limit);
		$rows = $dbase->loadObjectList();

mezunAkisHTML::siteAkis($rows, $my, $limit);
}
/**
* Sonraki akışı getiren fonksiyon
* 
* @param The $limitstart : başlangıç değeri
* @param mixed $limit : gösterilecek mesaj değeri
*/
function nextAkis($limitstart, $limit) {
	global $dbase;
	
	$dbase->setQuery("SELECT COUNT(*) FROM #__akis");
	$total = $dbase->loadResult();
	
	if ($limitstart>=$total){
		$limitstart = $total;
	}
	
	$query = "SELECT a.*, u.name, u.image FROM #__akis AS a"
		. "\n LEFT JOIN #__users AS u ON u.id=a.userid ORDER BY a.tarih DESC";
	$dbase->setQuery($query, $limitstart, $limit);
	$rows = $dbase->loadObjectList();
		
	foreach ($rows as $row) {
		$row->image = $row->image ? '<img class="img-thumbnail" src="'.SITEURL.'/images/profil/'.$row->image.'" alt="'.$row->name.'" title="'.$row->name.'" width="50" height="50" />':'<img class="img-thumbnail" src="'.SITEURL.'/images/profil/noimage.png" alt="'.$row->name.'" title="'.$row->name.'" width="50" height="50" />';
			  
			  mezunAkisHelper::getRow($row);
		  }
		  
		  if ($limitstart<$total){
		  ?>
		  <a href="index2.php?option=site&bolum=akis&task=next&limitstart=<?php echo $limitstart+$limit;?>&limit=<?php echo $limit;?>" class="next">Sonraki <?php echo $limit;?></a>
		  <?php
		  }
}


