<?php
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	if($logged_user)
	{
		$ret = logout();
	}
	else
	{
		$error[] = _("You are not logged in.");
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.implode($MESSAGE_SEPERATOR,$error).'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "home/";
		include('redirect.php');
	}
?>
