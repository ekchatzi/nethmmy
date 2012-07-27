<?php
	/* Ta stoixeia tis vasis apo to hosting*/
	$mysql_host = "localhost";
	$mysql_database = "ethmmy_db";
	$mysql_user = "thmmy";
	$mysql_password = "oaR9bdXMeOM4rw9SYAx";
	
	/*kane tin syndesi kai 'epelexe' tin vasi*/
	$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password) or die("fed");
	mysql_select_db($mysql_database, $conn) or die("fed2");

	$query = "SET NAMES 'utf8'";
	mysql_query($query);
?>
