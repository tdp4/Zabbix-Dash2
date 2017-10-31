<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>Zabbix Dashboard</title>
        <!-- Let's reset the default style properties -->
		<link rel="stylesheet" type="text/css" href="style/jquery.mobile-1.4.5.css" />
        <link rel="stylesheet" type="text/css" href="style/reset.css" />
        <link rel="stylesheet" type="text/css" href="style/theme-alt.css" />
        <!-- added the jQuery library for reloading the page and future features -->
        <script src="lib/js/jquery-2.1.1.min.js"></script>
		<!-- added jquery-mobile for forms and other features -->
		<script src="lib/js/jquery.mobile-1.4.5.min.js"></script>
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
		<div class="group-title">Please Configure!</div>

		<div data-role="content" data-theme="a">
			<center>You have not configured your installation yet.
			<br>Please copy the config.php.template to config.php.
			<br>Then edit to match your installation of Zabbix.</center>
		</div>
		<div data-role="footer" data-position="fixed" data-theme="b">
			<h1>Zabbix-Dash2 - <a href="https://github.com/tdp4/Zabbix-Dash2">https://github.com/tdp4/Zabbix-Dash2</a></h1>
		</div>
	</div>
</div>
</body>
</html>
