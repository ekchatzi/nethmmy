<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

	$username = isset($_POST['username'])?$_POST['username']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';
	$remember = isset($_POST['remember'])?1:0;
	$error = login($username,$password,$remember);

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
