<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$error = login($username,$password);

	if(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = ($error)?"index.php?v=register":"index.php?v=login";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
?>
