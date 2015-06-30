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
	BlockItem($item, 1);
	break;
	
	case 'unblock':
	BlockItem($item, 0);
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
	<?php 
	$i = 1;
	foreach($data as $idx => $item){ ?>
	<div class="row">
	<div class="col-sm-1"><?php echo ($idx+1); ?></div>
	<div class="col-sm-5"><a href="index.php?option=admin&bolum=stats&task=acq_details&ref=<?php echo base64_encode($item->referer_host); ?>"><?php echo str_replace("www.", "", $item->referer_host); ?></a></div>
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
	
	$query = "SELECT * FROM (SELECT * FROM #__stats ORDER BY date_time DESC LIMIT 15) t ORDER BY date_time DESC";
	$dbase->setQuery($query);
	
	$data = $dbase->loadObjectList();
	
	?>
<div class="panel panel-default">
	<div class="panel-heading"><h4>İstatistikler - Kontrol Paneli</h4>
	</div>
	<div class="panel-body">
	
	
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
	
	$id = base64_decode($id);
	
	$records = array();
	
	$query = "SELECT * FROM #__stats WHERE id=".$id;
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
	<div class="col-sm-2">URL</div>
	<div class="col-sm-10"><?php echo $item->uri; ?></div>
	</div>
	<hr>
	<?php if($item->referer){ ?>
	<div class="row">
	<div class="col-sm-2">Referer</div>
	<div class="col-sm-8"><?php echo $item->referer; ?></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->referer_host); ?>">Blokla</a></div>
	</div>
	<hr>
	<?php } ?>
	
	<div class="row">
	<div class="col-sm-2">IP</div>
	<div class="col-sm-8"><a href="index.php?option=admin&bolum=stats&task=ip_behavior&ip=<?php echo base64_encode($item->remote_add); ?>"><?php echo $item->remote_add; ?></a></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->remote_add); ?>">Blokla</a></div>
	</div>
	<hr>
	<div class="row">
	<div class="col-sm-2">Domain</div>
	<div class="col-sm-8"><?php echo $item->domain; ?></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->domain); ?>">Blokla</a></div>
	</div>
	<hr>
	<?php if($item->agent){ ?>
	<div class="row">
	<div class="col-sm-2">User Agent</div>
	<div class="col-sm-8"><?php echo $item->agent; ?></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->agent); ?>">Blokla</a></div>
	</div>
	<hr>
	<div class="row">
	<div class="col-sm-2">User Browser</div>
	<div class="col-sm-8"><?php echo $item->browser; ?></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->browser); ?>">Blokla</a></div>
	</div>
	<hr>
	<div class="row">
	<div class="col-sm-2">User OS</div>
	<div class="col-sm-8"><?php echo $item->os; ?></div>
	<div class="col-sm-2"><a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->os); ?>">Blokla</a></div>
	</div>
	<hr>
	<?php } ?>
	
	<div class="row">
	<div class="col-sm-2">Name Servers</div>
	<div class="col-sm-10">
	<?php foreach($records as $rec){ ?>
	<?php echo $rec['target']; ?><br/>
	<?php } ?></div>
	</div>
	<hr>
	<div class="row">
	<div class="col-sm-2">Date Time</div>
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

function BlockItem($item, $status) {
	global $dbase;
	
	$item = base64_decode($item);
	
	if ($status == 1) {
	$dbase->setQuery("INSERT INTO #__stats_blocklist (block) VALUES (".$dbase->Quote($item).")");
	$dbase->query();
	} else {
	$dbase->setQuery("DELETE FROM #__stats_blocklist WHERE id=".$dbase->Quote($item)."");
	$dbase->query();    
	}
	
	Redirect('index.php?option=admin&bolum=stats&task=blocklist');
	
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
	<div class="col-sm-4"><a href="index.php?option=admin&bolum=stats&task=unblock&item=<?php echo base64_encode($item->id); ?>">Blok Kaldır</a></div>
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
