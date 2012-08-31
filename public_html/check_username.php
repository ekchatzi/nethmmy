<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

	$username = isset($_POST['username'])?$_POST['username']:'';
	$e = new_account_username_validation($username);

	echo '{ "error" : "'.$e.'" }';
?>
