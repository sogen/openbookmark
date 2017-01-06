<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<?php
define ("ABSOLUTE_PATH", dirname (__FILE__) . "/");

/*
if (extension_loaded ('zlib')) {
    ob_start ('ob_gzhandler');
}
*/
require_once (ABSOLUTE_PATH . "lib/webstart.php");
if (! is_file (ABSOLUTE_PATH . "config/config.php")) {
	die ('<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.css"/>

<div class="container">
	<div class="jumbotron">
        <h1>Install required</h1>
        <p>You have to <a href="./install.php">install</a> OpenBookmark.</p>
      </div>
			</div>
');
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
<!DOCTYPE html>
<html>
	<head>
		<title>OpenBookmark</title>
		<meta content="width=device-width,initial-scale=1" name=viewport>
		<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="./style2.css"/>
		<link rel="stylesheet" type="text/css" href="./font-awesome-4.7.0/css/font-awesome.min.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="./js/jquery_ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" /> -->
		<script type="text/javascript" src="./lib/lib.js"></script>
		<script type="text/javascript" src="./js/jquery-1.11.1.min.js"></script>
		<!-- <script type="text/javascript" src="./js/jquery_ui/js/jquery-ui.min.js"></script> -->
		<script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico"/>
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
