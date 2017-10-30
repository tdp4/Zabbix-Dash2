<?php

include('config.php');

// load the Zabbix Php API which is included in this build (tested on Zabbix v2.2.2)
require 'lib/php/ZabbixApiAbstract.class.php';
require 'lib/php/ZabbixApi.class.php';


// connect to Zabbix Json API
$api = new ZabbixApi($api_url, $api_user, base64_decode($api_pass));

// Set Defaults
$api->setDefaultParams(array(
        'output' => 'extend',
	));

# parse passed items
if (isset($_GET['gid'])) {
	$groupids = explode(',', $_GET['gid']);
} else {
	$groupids = array('28','29','30','31');
}

if (isset($_GET['name'])) {
	$dashboard = $_GET['name'];
} else {
	$dashboard = "Dashboard";
}
?>
<!DOCTYPE html>
<?php

if (isset($_SERVER['CONTEXT_PREFIX'])) {
	$context = $_SERVER['CONTEXT_PREFIX'];
} else {
	$context = '';
}

?>
<html>
<head>
        <meta charset="UTF-8">
        <title>ZbxDash - <?php print $dashboard; ?></title>
        <!-- Let's reset the default style properties -->
        <link rel="stylesheet" type="text/css" href="<?php print $context; ?>/style/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php print $context; ?>/style/theme-alt.css" />
        <!-- added the jQuery library for reloading the page and future features -->
        <script src="<?php print $context; ?>/lib/js/jquery-2.1.1.min.js"></script>
        <!-- added the masonry js so all blocks are better alligned -->
        <script src="<?php print $context; ?>/lib/js/masonry.pkgd.min.js"></script>
        <!-- Removed this temporary because I disliked the look -->
        <!-- <body class="js-masonry"  data-masonry-options='{ "columnWidth": 250, "itemSelector": ".groupbox" }'> -->
</head>
<!-- Second piece of js to gracefully reload the page (value in ms) -->
<script>
	function ReloadPage() {
	   location.reload();
	};
	$(document).ready(function() {
		setTimeout("ReloadPage()", <?php print $reload_time; ?>);
	});
</script>
<body id="bg-one">

<!-- START GET RENDER DATE - Which will show date and time of generating this file -->
<div id="timestamp">
    <div id="date"><?php echo date("d F Y", time()); ?></div>
    <div id="time"><?php echo date("H:i", time()); ?></div>
</div>
<!-- END GET RENDER DATE -->
<!-- We could use the Zabbix HostGroup name here, but would not work in a nice way when using a dozen of hostgroups, yet! So we hardcoded it here. -->
<div id="sheetname"><?php print $dashboard; ?></div>

<?php

$groups = $api->hostgroupGet(array(
       'output' => array('name'),
       'selectHosts' => array(
               'flags',
               'hostid',
               'name',
               'maintenance_status'),
       'real_hosts ' => 1,
       'groupids' => $groupids,
#       'with_monitored_triggers' => 1,
       'sortfield' => 'name'
    ));

foreach($groups as $group) {
   $groupIds[] = $group->groupid;
}

$triggers = $api->triggerGet(array(
	   'output' => array(
		   'priority',
		   'description'),
	   'selectHosts' => array('hostid'),
		   'groupids' => $groupIds,
		   'expandDescription' => 1,
		   'only_true' => 1,
		   'monitored' => 1,
		   'withLastEventUnacknowledged' => 1,
		   'sortfield' => 'priority',
		   'sortorder' => 'DESC'
   ));

foreach($triggers as $trigger) {
   foreach($trigger->hosts as $host) {
	   $hostTriggers[$host->hostid][] = $trigger;
   }
}

// get all hosts from each groupid
	foreach($groups as $group) {
		$groupname = $group->name;
        $hosts = $group->hosts;

        usort($hosts, function ($a, $b) {
            if ($a->name == $b) return 0;
            return ($a->name < $b->name ? -1 : 1);
        });
		echo "<div class=\"groupbox\">"; // Again, we dont want to use the groupfunction yet
		echo "<div class=\"group-title\">" . strtoupper(preg_replace('/\//',' / ',$groupname)) . "</div>";
		echo "<div class=\"groupbox js-masonry\" data-masonry-options='{ \"itemSelector\": \".hostbox\" }'\">";

        if ($hosts) {

			// print all host IDs
			foreach($hosts as $host) {
				// Check if host is not disabled, we don't want them!
				if ($host->flags == "0") {

					$hostid = $host->hostid;
					$hostname = $host->name;
					$maintenance = $host->maintenance_status;

					if (array_key_exists($hostid, $hostTriggers)) {
						// Highest Priority error
						$hostboxprio = $hostTriggers[$hostid][0]->priority;
						//First filter the hosts that are in maintenance and assign the maintenance class if is true
						if ($maintenance != "0") {
							echo "<div class=\"hostbox maintenance\">";
						} else {
							// If hosts are not in maintenance, check for trigger(s) and assign the appropriate class to the box
							echo "<div class=\"hostbox nok" . $hostboxprio . "\">";
						}
						echo "<div class=\"title\">" . $hostname . "</div>";
						$count = "0";
						foreach ($hostTriggers[$hostid] as $event) {
							if ($count++ <= 2 ) {
								$priority = $event->priority;
								$description = $event->description;

								// Remove hostname or host.name in description
								$search = array('{HOSTNAME}', '{HOST.NAME}');
								$description = str_replace($search, "", $description);
								// View
								echo "<div class=\"description nok" . $priority ."\">" . $description . "</div>";
							} else {
								break;
							}
						}
						echo "</div>";
					} else {
						// If there are no trigger(s) for the host found, assign the "ok" class to the box
						echo "<div class=\"hostbox ok\">";
						echo "<div class=\"title\">" . $hostname . "</div>";
						echo "</div>";
					}
				}
			}
			echo "</div></div>";
		}
	}
	#$api->userLogout(); # commented out due to a bug in php and Zabbix

?>
</body>
</html>
