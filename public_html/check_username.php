<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	
	if(!isset($error)) 
        $error = array();

	if(!isset($message))
		$message = array();

	$username = isset($_POST['username'])?$_POST['username']:'';
	if(!($e = new_account_username_validation($username)))
	{	
		$message[] = _('Username available');
	}
	else
	{
		$error[] = $e;
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

		$redirect = "profile/$uid/";
		include('redirect.php');
	}
?>
