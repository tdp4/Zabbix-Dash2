<?php
if ((include('config.ph)) == FALSE) {
	header("location: no_config.php");
	exit;
}

if (isset($_POST['config'])) {
	if (isset($_POST['headername'])) {
		$header = $_POST['headername'];
	} else {
		$header = '';
	}

	$list = array();

	foreach($_POST as $key => $value) {
		if (preg_match('/^hg_/', $key)) {
			$list[] = preg_replace('/^hg_/', '', $key);
		}
	}

	$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $context . "/dash.php?name=" . urlencode($header) . "&gid=" . implode(',', $list);
}

// load the Zabbix Php API which is included in this build (tested on Zabbix v2.2.2)
require 'lib/php/ZabbixApiAbstract.class.php';
require 'lib/php/ZabbixApi.class.php';

// connect to Zabbix Json API
$api = new ZabbixApi($api_url, $api_user, base64_decode($api_pass));

// Set Defaults
$api->setDefaultParams(array(
        'output' => 'extend',
	));
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>Zabbix Dashboard</title>
        <!-- Let's reset the default style properties -->
		<link rel="stylesheet" type="text/css" href="<?php print $context; ?>/style/jquery.mobile-1.4.5.css" />
        <link rel="stylesheet" type="text/css" href="<?php print $context; ?>/style/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php print $context; ?>/style/theme-alt.css" />
        <!-- added the jQuery library for reloading the page and future features -->
        <script src="<?php print $context; ?>/lib/js/jquery-2.1.1.min.js"></script>
		<!-- added jquery-mobile for forms and other features -->
		<script src="<?php print $context; ?>/lib/js/jquery.mobile-1.4.5.min.js"></script>
        <!-- added the masonry js so all blocks are better alligned -->
        <script src="<?php print $context; ?>/lib/js/masonry.pkgd.min.js"></script>
</head>
<!-- Second piece of js to gracefully reload the page (value in ms) -->
<?php if ($reload_enabled == true) { ?>
<script>
	function ReloadPage() {
	   location.reload();
	};
	$(document).ready(function() {
		setTimeout("ReloadPage()", <?php print $reload_time; ?>);
	});
</script>
<?php } ?>
<body id="bg-one">

<!-- START GET RENDER DATE - Which will show date and time of generating this file -->
<div data-role="header">
	<div id="timestamp">
		<div id="date"><?php echo date("d F Y", time()); ?></div>
		<div id="time"><?php echo date("H:i", time()); ?></div>
	</div>
	<!-- END GET RENDER DATE -->
	<!-- We could use the Zabbix HostGroup name here, but would not work in a nice way when using a dozen of hostgroups, yet! So we hardcoded it here. -->
	<div id="sheetname"><?php print "Configuration"; ?></div>
</div>
<div data-role="page" data-theme="a">
	<div class="groupbox">
		<div class="group-title">CONFIGURE YOUR SCREEN</div>

		<?php if (isset($url)) { ?>
		<div data-role="content" data-theme="a">
			<center>Screen: <a href="<?php print $url; ?>" target="_blank"><?php print $header; ?></a></center>
		</div>
		<?php } ?>

		<div class="config">
			<div data-role="content" data-theme="b">
			<?php

$groups = $api->hostgroupGet(array(
       'output' => array('name'),
       'selectHosts' => array(
               'flags',
               'hostid',
               'name',
               'maintenance_status'),
       'real_hosts ' => 1,
       'sortfield' => 'name'
    ));

			?>
			<form method="post">
				<label for="headername">Header Title (top right corner):</label>
				<input type="text" name="headername" id="headername" value="" placeholder="Name for your Dashboard" />
				<input type="hidden" name="config" id="config" value="x" />
				<fieldset data-role="controlgroup">
					<legend>Select Host Groups to Display:</legend>
					<?php
$hgcount = 0;
$disable_submit = false;
foreach($groups as $group) {
	if (isset($host_group_filter) && preg_match($host_group_filter, $group->name)) {
		print "<input type=\"checkbox\" name=\"hg_" . $group->groupid . "\" id=\"hg_" . $group->groupid . "\"><label for=\"hg_" . $group->groupid . "\">" . $group->name . "</label>";
		$hgcount ++;
	}
}

if ($hgcount == 0) {
		print "<div data-role=\"content\" data-theme=\"a\">No Hostgroups match filter of '" . $host_group_filter . "'.  Please create a hostgroup to match this filter or change the filter in your config file.</div>";
		$disable_submit = true;
}
	
					?>
				</fieldset>
				<button class="ui-shadow ui-btn ui-corner-all" type="submit" id="submit" <?php if ($disable_submit) { echo "disabled"; }?>>Submit</button>
			</form>
		</div>
		<div data-role="footer" data-position="fixed" data-theme="b">
			<h1>Zabbix-Dash2 - <a href="https://github.com/tdp4/Zabbix-Dash2">https://github.com/tdp4/Zabbix-Dash2</a></h1>
		</div>
	</div>
</div>
</body>
</html>
