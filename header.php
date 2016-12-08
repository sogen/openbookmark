<?php
define ("ABSOLUTE_PATH", dirname (__FILE__) . "/");

/*
if (extension_loaded ('zlib')) {
    ob_start ('ob_gzhandler');
}
*/
require_once (ABSOLUTE_PATH . "lib/webstart.php");
if (! is_file (ABSOLUTE_PATH . "config/config.php")) {
	die ('You have to <a href="./install.php">install</a> OpenBookmark.');
}
else {
	require_once (ABSOLUTE_PATH . "config/config.php");
}
require_once (ABSOLUTE_PATH . "lib/mysql.php");
$mysql = new mysql;
require_once (ABSOLUTE_PATH . "lib/auth.php");
$auth = new Auth;
require_once (ABSOLUTE_PATH . "lib/lib.php");
require_once (ABSOLUTE_PATH . "lib/login.php");

?>
<!DOCTYPE html">
<html>
	<head>
		<title>Links</title>
		<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->

<!--
http://stackoverflow.com/questions/1341089/using-meta-tags-to-turn-off-caching-in-all-browsers
-->
		<meta http-equiv="Cache-Control" content="no-store" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />

		<meta charset=utf-8>
		<meta content="IE=edge" http-equiv=X-UA-Compatible>
		<meta content="width=device-width,initial-scale=1" name=viewport>
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"/> -->
		<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="./style2.css"/>
		<?php // echo (@$settings["theme"]!="") ? '<link rel="stylesheet" type="text/css" href="./style'.$settings["theme"].'.css" />' : ""; ?>
		<link rel="shortcut icon" href="favicon.ico"/>
		<script type="text/javascript" src="./lib/lib.js"></script>
		<script type="text/javascript" src="./js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="./js/jquery_ui/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
		<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/smoothness/jquery-ui.css" /> -->
		<link rel="stylesheet" type="text/css" href="./js/jquery_ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" />
		</head>
<body>

<?php

if (is_file (ABSOLUTE_PATH . "install.php")) {
	message ('Remove "install.php" before using OpenBookmark.');
}

if ($display_login_form) {
	$auth->display_login_form ();
	require_once (ABSOLUTE_PATH . "footer.php");
}

?>
