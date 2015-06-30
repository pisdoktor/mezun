<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

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
	<h1>Acquisition Behavior : <?php echo $ref; ?></h1>
<table class="grid">
	<?php foreach($data as $idx => $item){ ?>
		<tr>
			<td width="20"><?php echo ($idx+1); ?></td>
			<td>
				<div><a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>"><?php echo $item->uri; ?></a></div>
				<div><span><?php echo $item->referer; ?></span></div>
			</td>
			<td><?php echo $item->remote_add; ?></td>
			<td width="130"><?php echo $item->date_time; ?></td>
		</tr>
	<?php } ?>
</table>
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
		<h1>Acquisition</h1>
<table class="grid">
	<?php foreach($data as $idx => $item){ ?>
		<tr>
			<td width="20"><?php echo ($idx+1); ?></td>
			<td><a href="index.php?option=admin&bolum=stats&task=acq_details&ref=<?php echo base64_encode($item->referer_host); ?>"><?php echo str_replace("www.", "", $item->referer_host); ?></a></td>
			<td><?php echo $item->count; ?></td>
		</tr>
	<?php } ?>
</table>
<?php
}

function Dashboard() {
	global $dbase;
	
	$query = "SELECT * FROM (SELECT * FROM #__stats ORDER BY date_time DESC LIMIT 20) t ORDER BY date_time DESC";
	$dbase->setQuery($query);
	
	$data = $dbase->loadObjectList();
	
	?>

<h1>Dashboard</h1>
<table class="grid">
	<?php foreach($data as $idx => $item){ ?>
		<tr>
			<td width="20"><?php echo ($idx+1); ?></td>
			<td>
				<div><a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>"><?php echo $item->uri; ?></a></div>
				<div><span><?php echo $item->agent; ?></span></div>
			</td>
			<td><?php echo $item->referer_host; ?></td>
			<td><a href="index.php?option=admin&bolum=stats&task=ip_behavior&ip=<?php echo base64_encode($item->remote_add); ?>"><?php echo $item->remote_add; ?></a></td>
			<td><?php echo $item->date_time; ?></td>
		</tr>
	<?php } ?>
</table>
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
		<h1>Request Details</h1>
<table class="grid">
	<tr>
		<td width="150">URL</td>
		<td><label><?php echo $item->uri; ?></label></td>
	</tr>
	
	<?php if($item->referer){ ?>
		<tr>
			<td>Referer</td>
			<td><?php echo $item->referer; ?> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->referer_host); ?>">Block</a> ]</td>
		</tr>
	<?php } ?>
		
	<tr>
		<td>IP</td>
		<td><a href="index.php?option=admin&bolum=stats&task=ip_behavior&ip=<?php echo base64_encode($item->remote_add); ?>"><?php echo $item->remote_add; ?></a> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->remote_add); ?>">Block</a> ]</td>
	</tr>
	
		<tr>
			<td>Domain</td>
			<td><?php echo $item->domain; ?> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->domain); ?>">Block</a> ]</td>
		</tr>
	
	<?php if($item->agent){ ?>
		<tr>
			<td>User Agent</td>
			<td><?php echo $item->agent; ?> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->agent); ?>">Block</a> ]</td>
		</tr>
		
		<tr>
			<td>User Browser</td>
			<td><?php echo $item->browser; ?> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->browser); ?>">Block</a> ]</td>
		</tr>
		
		<tr>
			<td>User OS</td>
			<td><?php echo $item->os; ?> [ <a href="index.php?option=admin&bolum=stats&task=block&item=<?php echo base64_encode($item->os); ?>">Block</a> ]</td>
		</tr>
	<?php } ?>
		
	<tr>
		<td>Name Servers</td>
		<td>
			<?php foreach($records as $rec){ ?>
				<?php echo $rec['target']; ?><br/>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td>Date Time</td>
		<td><?php echo $item->date_time; ?></td>
	</tr>
</table>
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
	<h1>IP Behavior : <?php echo $ip; ?></h1>
<table class="grid">
	<?php foreach($data as $idx => $item){ ?>
		<tr>
			<td width="20"><?php echo ($idx+1); ?></td>
			<td>
				<div><a href="index.php?option=admin&bolum=stats&task=request&id=<?php echo base64_encode($item->id); ?>"><?php echo $item->uri; ?></a></div>
				<div><span><?php echo $item->referer; ?></span></div>
			</td>
			<td><?php echo $item->remote_add; ?></td>
			<td width="130"><?php echo $item->date_time; ?></td>
		</tr>
	<?php } ?>
</table>
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
	<h1>Block List</h1>
<table class="grid">
	<?php foreach($data as $idx => $item){ ?>
		<tr>
			<td width="20"><?php echo ($idx+1); ?></td>
			<td>
				<div><label><?php echo $item->block; ?></label></div>
			</td>
			<td width="50"><a href="index.php?option=admin&bolum=stats&task=unblock&item=<?php echo base64_encode($item->id); ?>">Unblock</a></td>
		</tr>
	<?php } ?>
</table>
<?php
	
}
