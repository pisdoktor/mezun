<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

if (!STATS) {
	return;
}

$id = getParam($_REQUEST, 'id');
$ip = getParam($_REQUEST, 'ip');
$item = getParam($_REQUEST , 'item');
$ref = getParam($_REQUEST, 'ref');

switch($task) {
	default:
	Dashboard();
	break;
	
	case 'request':
	Request($id);
	break;
	
	case 'ip_behavior':
	IPBehavior($ip);
	break;
	
	case 'block':
	BlockItem($item, 1, $id);
	break;
	
	case 'unblock':
	BlockItem($item, 0, $id);
	break;
	
	case 'blocklist':
	BlockList();
	break;
	
	case 'acq':
	Acquisition();
	break;
	
	case 'acq_details':
	AcqDetails($ref);
	break;
	
	case 'counts':
	StatsCounts();
	break;
}

function StatsCounts() {
	global $dbase;
	
	//browserlar
	$query = "SELECT agent as browser, hits FROM #__stats_counts WHERE type=0 ORDER BY hits DESC";
	$dbase->setQuery($query);
	$browsers = $dbase->loadObjectList();
	
	//işletim sistemleri
	$query = "SELECT agent as os, hits FROM #__stats_counts WHERE type=1 ORDER BY hits DESC";
	$dbase->setQuery($query);
	$oss = $dbase->loadObjectList();
	
	//domainler
	$query = "SELECT agent as domain, hits FROM #__stats_counts WHERE type=2 ORDER BY hits DESC";
	$dbase->setQuery($query);
	$domains = $dbase->loadObjectList();
	
	?>
	<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Sayaçlar</h4>
	</div>
	<div class="panel-body">
	<div class="row">
	<div class="col-sm-4">
			<div class="row">
			<div class="col-sm-6">
			BROWSER ADI
			</div>
			<div class="col-sm-6">
			SAYI
			</div>
			</div>
	<?php
		foreach ($browsers as $browser) {
			?>
			<div class="row">
			<div class="col-sm-6">
			<?php echo $browser->browser;?>
			</div>
			<div class="col-sm-6">
			<?php echo $browser->hits;?>
			</div>
			</div>
			<?php
		}
	?>
	</div>
	<div class="col-sm-4">
	<div class="row">
			<div class="col-sm-6">
			İŞLETİM SİSTEMİ
			</div>
			<div class="col-sm-6">
			SAYI
			</div>
			</div>
	<?php
		foreach ($oss as $os) {
			?>
			<div class="row">
			<div class="col-sm-6">
			<?php echo $os->os;?>
			</div>
			<div class="col-sm-6">
			<?php echo $os->hits;?>
			</div>
			</div>
			<?php
		} 
	?>
	</div>
	<div class="col-sm-4">
	<div class="row">
			<div class="col-sm-6">
			DOMAİN
			</div>
			<div class="col-sm-6">
			SAYI
			</div>
			</div>
	<?php
		foreach ($domains as $domain) {
			?>
			<div class="row">
			<div class="col-sm-6">
			<?php echo $domain->domain;?>
			</div>
			<div class="col-sm-6">
			<?php echo $domain->hits;?>
			</div>
			</div>
			<?php
		}
	?>
	</div>
	</div>
	</div>
	</div>
	<?php
}

function AcqDetails($ref) {
	global $dbase;
	
	$ref = base64_decode($ref);
	
	$fromdate = date('Y-m-d 00:00:00');
	$todate = date ('Y-m-d 23:59:59');
	
	$query = "SELECT * FROM #__stats WHERE referer_host=".$dbase->Quote($ref)." AND date_time BETWEEN ".$dbase->Quote($fromdate)." AND ".$dbase->Quote($todate)." ORDER BY date_time DESC ";
	$dbase->setQuery($query);
	
	$data = $dbase->loadObjectList();
	?>
	<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Toplama Detayları: <?php echo $ref; ?></h4>
	</div>
	<div class="panel-body">
	<div class="row">
	<div class="col-sm-1">
	<strong>SIRA</strong>
	</div>
	<div class="col-sm-7">
	<strong>URL ADRESİ / ÖNEREN</strong>
	</div>
	<div class="col-sm-2">
	<strong>IP ADRESİ</strong>
	</div>
	<div class="col-sm-2">
	<strong>ZAMAN</strong>
	</div>
	</div>
	<hr>
	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1"><?php echo ($idx+1); ?></div>
	<div class="col-sm-7"><div><a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>"><?php echo $item->uri; ?></a></div>
				<div><span><?php echo $item->referer; ?></span></div>
				</div>
	<div class="col-sm-2"><?php echo $item->remote_add; ?></div>
	<div class="col-sm-2"><?php echo $item->date_time; ?></div>
	</div>

	<?php 
	if ($i <= count($data)-1) {
		echo '<hr>';
	} 
	$i++;
	} ?>


</div>
</div>
<?php
	
}

function Acquisition() {
	global $dbase;
	
	$fromdate = date('Y-m-d 00:00:00');
	$todate = date ('Y-m-d 23:59:59');
	
	$query = "SELECT referer_host, COUNT(*) AS count FROM #__stats WHERE referer_host !='' 
		AND date_time BETWEEN ".$dbase->Quote($fromdate)." AND ".$dbase->Quote($todate)." GROUP BY referer_host ORDER BY count DESC";
		$dbase->setQuery($query);
		
		$data = $dbase->loadObjectList();
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Toplama</h4>
	</div>
	<div class="panel-body">
	<div class="row">
	<div class="col-sm-1">
	<strong>SIRA</strong>
	</div>
	<div class="col-sm-8">
	<strong>ÖNEREN HOST ADRESİ</strong>
	</div>
	<div class="col-sm-3">
	<strong>SAYI</strong>
	</div>
	</div>
	<hr>
	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1"><?php echo ($idx+1); ?></div>
	<div class="col-sm-8"><a href="index.php?option=admin&bolum=stats&task=acq_details&ref=<?php echo base64_encode($item->referer_host); ?>"><?php echo str_replace("www.", "", $item->referer_host); ?></a></div>
	<div class="col-sm-3"><?php echo $item->count; ?></div>
	</div>

	<?php 
	if ($i <= count($data)-1) {
		echo '<hr>';
	} 
	$i++;
	} ?>


</div>
</div>
<?php
}

function Dashboard() {
	global $dbase;
	
	$query = "SELECT * FROM (SELECT * FROM #__stats ORDER BY date_time DESC LIMIT 20) t ORDER BY date_time DESC";
	$dbase->setQuery($query);
	
	$data = $dbase->loadObjectList();
	
	?>
<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Kontrol Paneli</h4>
	</div>
	<div class="panel-body">
	
	<div class="row">
	<div class="col-sm-1">
	<strong>SIRA</strong>
	</div>
	<div class="col-sm-5">
	<strong>İSTEK ADRESİ / AJAN</strong>
	</div>
	<div class="col-sm-2">
	<strong>ÖNEREN</strong>
	</div>
	<div class="col-sm-2">
	<strong>IP ADRESİ</strong>
	</div>
	<div class="col-sm-2">
	<strong>ZAMAN</strong>
	</div>
	</div>
	<hr>
	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1">
	<?php echo ($idx+1); ?>
	</div>
	<div class="col-sm-5">
	<div>
	<a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>" title="İstek Detayları">
	<?php echo $item->uri; ?>
	</a>
	</div>
	<div><span><?php echo $item->agent; ?></span></div>
	</div>
	<div class="col-sm-2">
	<?php echo $item->referer_host; ?>
	</div>
	<div class="col-sm-2">
	<a href="index.php?option=admin&bolum=stats&task=ip_behavior&ip=<?php echo base64_encode($item->remote_add); ?>" title="IP Hareketleri"><?php echo $item->remote_add; ?></a>
	</div>
	<div class="col-sm-2">
	<?php echo $item->date_time; ?>
	</div>
	</div>
	<?php
	if ($i <= count($data)-1) {
		echo '<hr>';
	} 
	$i++;
	} ?>


</div>
</div>
<?php
}

function Request($id) {
	global $dbase;
	
	$xid = base64_decode($id);
	
	$records = array();
	
	$query = "SELECT * FROM #__stats WHERE id=".$xid;
	$dbase->setQuery($query);
	$dbase->loadObject($item);
		
		if($item->referer_host){
			$records = dns_get_record($item->referer_host, DNS_NS);
		}
		
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - İstek Detayları</h4>
	</div>
	<div class="panel-body">
	
	<div class="row">
	<div class="col-sm-2">İSTEK ADRESİ</div>
	<div class="col-sm-10"><?php echo $item->uri; ?></div>
	</div>
	<hr>
	<?php if($item->referer){
	$link_referer = isBlocked($item->referer_host) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->referer_host).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->referer_host).'&id='.$id.'">Blokla</a>';
	 ?>
	<div class="row">
	<div class="col-sm-2">ÖNEREN</div>
	<div class="col-sm-8"><?php echo $item->referer; ?></div>
	<div class="col-sm-2"><?php echo $link_referer;?></div>
	</div>
	<hr>
	<?php } ?>
	
	<?php
	$link_ip = isBlocked($item->remote_add) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->remote_add).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->remote_add).'&id='.$id.'">Blokla</a>';    
	?>
	<div class="row">
	<div class="col-sm-2">IP ADRESİ</div>
	<div class="col-sm-8"><a href="index.php?option=admin&bolum=stats&task=ip_behavior&ip=<?php echo base64_encode($item->remote_add); ?>"><?php echo $item->remote_add; ?></a></div>
	<div class="col-sm-2"><?php echo $link_ip;?></div>
	</div>
	<hr>
	
	<?php
	$link_domain = isBlocked($item->domain) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->domain).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->domain).'&id='.$id.'">Blokla</a>';
	?>
	<div class="row">
	<div class="col-sm-2">DOMAİN</div>
	<div class="col-sm-8"><?php echo $item->domain; ?></div>
	<div class="col-sm-2"><?php echo $link_domain;?></div>
	</div>
	<hr>
	
	<?php if($item->agent){
	$link_agent = isBlocked($item->agent) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->agent).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->agent).'&id='.$id.'">Blokla</a>';
	 ?>
	<div class="row">
	<div class="col-sm-2">AJAN</div>
	<div class="col-sm-8"><?php echo $item->agent; ?></div>
	<div class="col-sm-2"><?php echo $link_agent;?></div>
	</div>
	<hr>
	<?php
	$link_browser = isBlocked($item->browser) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->browser).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->browser).'&id='.$id.'">Blokla</a>';
	?>
	<div class="row">
	<div class="col-sm-2">BROWSER</div>
	<div class="col-sm-8"><?php echo $item->browser; ?></div>
	<div class="col-sm-2"><?php echo $link_browser;?></div>
	</div>
	<hr>
	<?php
	$link_os = isBlocked($item->os) ? '<a href="index.php?option=admin&bolum=stats&task=unblock&item='.base64_encode($item->os).'&id='.$id.'">Blok Kaldır</a>':'<a href="index.php?option=admin&bolum=stats&task=block&item='.base64_encode($item->os).'&id='.$id.'">Blokla</a>';
	?>
	<div class="row">
	<div class="col-sm-2">İŞLETİM SİSTEMİ</div>
	<div class="col-sm-8"><?php echo $item->os; ?></div>
	<div class="col-sm-2"><?php echo $link_os;?></div>
	</div>
	<hr>
	<?php } ?>
	
	<div class="row">
	<div class="col-sm-2">NAME SERVERLAR</div>
	<div class="col-sm-10">
	<?php foreach($records as $rec){ ?>
	<?php echo $rec['target']; ?><br/>
	<?php } ?></div>
	</div>
	<hr>
	<div class="row">
	<div class="col-sm-2">ZAMAN</div>
	<div class="col-sm-10"><?php echo $item->date_time; ?></div>
	</div>

</div>
</div>
<?php
	
}

function IPBehavior($ip) {
	global $dbase;
	
	$ip = base64_decode($ip);
	
	$fromdate = date('Y-m-d 00:00:00');
	$todate = date ('Y-m-d 23:59:59');
	
	$query = "SELECT * FROM #__stats WHERE remote_add=".$dbase->Quote($ip)." AND date_time BETWEEN ".$dbase->Quote($fromdate)." AND ".$dbase->Quote($todate)." ";
	$dbase->setQuery($query);
	
	$data = $dbase->loadObjectList();

	?>
	
	<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - IP Hareketleri: <?php echo $ip; ?></h4>
	</div>
	<div class="panel-body">
	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1"><?php echo ($idx+1); ?></div>
	<div class="col-sm-7"><div><a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>"><?php echo $item->uri; ?></a></div>
				<div><span><?php echo $item->referer; ?></span></div>
				</div>
	<div class="col-sm-2"><?php echo $item->remote_add; ?></div>
	<div class="col-sm-2"><?php echo $item->date_time; ?></div>
	</div>
	<?php 
	if ($i <= count($data)-1) {
		echo '<hr>';
	} 
	$i++;
	} ?>

</div>
</div>
<?php
}

function BlockItem($item, $status, $id) {
	global $dbase;
	
	$item = base64_decode($item);
	
	if ($status == 1) {
	$dbase->setQuery("INSERT INTO #__stats_blocklist (block) VALUES (".$dbase->Quote($item).")");
	$dbase->query();
	} else {
	$dbase->setQuery("DELETE FROM #__stats_blocklist WHERE block=".$dbase->Quote($item)."");
	$dbase->query();    
	}
	
	if ($id) {
	Redirect('index.php?option=admin&bolum=stats&task=request&id='.$id);
	} else {
		Redirect('index.php?option=admin&bolum=stats&task=blocklist');
	}
	
}

function BlockList() {
	global $dbase;
	
	$dbase->setQuery("SELECT * FROM #__stats_blocklist");
	$data = $dbase->loadObjectList();
	?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Blok Listesi</h4>
	</div>
	<div class="panel-body">

	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1"><?php echo ($idx+1); ?></div>
	<div class="col-sm-7"><?php echo $item->block; ?></div>
	<div class="col-sm-4"><a href="index.php?option=admin&bolum=stats&task=unblock&item=<?php echo base64_encode($item->block); ?>">Blok Kaldır</a></div>
	</div>
	<?php 
	if ($i <= count($data)-1) {
		echo '<hr>';
	} 
	$i++;
	} ?>
</div>
</div>
<?php
	
}

/**
	* Bloklu olup olmadığına bakalım eğer blokluysa true döndürelim
	* 
	* @param mixed $item
	*/
	function isBlocked($item) {
		global $dbase;
		
		$dbase->setQuery("SELECT id FROM #__stats_blocklist WHERE block=".$dbase->Quote($item));
		$result = $dbase->loadResult();
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
