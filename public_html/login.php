<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

	$username = isset($_POST['username'])?$_POST['username']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';
	$remember = isset($_POST['remember'])?1:0;
	if(!($error = login($username,$password,$remember)))
	{
		$message = sprintf(_('Welcome to Nethmmy'));
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.$error.'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);

		$redirect = $_SERVER['HTTP_REFERER'];

		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>
