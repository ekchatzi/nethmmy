<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$username = isset($_POST['username'])?$_POST['username']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';
	$remember = isset($_POST['remember'])?1:0;
	if(!($error[] = login($username,$password,$remember)))
	{
		$message[] = sprintf(_('Welcome to Nethmmy'));
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').'],
			"message" : ['.(count($message)?('"'.implode('","',$message).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = isset($_COOKIE['ref'])?$_COOKIE['ref']:"home/";
		include('redirect.php');
	}
?>
