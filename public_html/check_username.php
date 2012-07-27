<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");

	$username = trim(strtolower($_POST['username']));
	$username = mysql_escape_string($username);

	$query = "SELECT username FROM users WHERE username = '$username' LIMIT 1";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);

	echo $num;
	//echo "1";
	