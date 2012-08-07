<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$error = login($username,$password);

	if(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = ($error)?"register/":"login/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>
