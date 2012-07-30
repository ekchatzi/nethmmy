<?php
	/* Ta stoixeia tis vasis apo to hosting*/
	$mysql_host = "localhost";
	$mysql_database = "ethmmy_db";
	$mysql_user = "thmmy";
	$mysql_password = "oaR9bdXMeOM4rw9SYAxG";

	/*kane tin syndesi kai 'epelexe' tin vasi*/
	$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password) or die(mysql_error());
	mysql_select_db($mysql_database, $conn) or die(mysql_error());

	$query = "SET NAMES 'utf8'";
	mysql_query($query);
?>
