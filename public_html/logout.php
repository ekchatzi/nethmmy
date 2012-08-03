<?php
	include_once("../lib/login.php");
	include_once("../lib/localization.php");

        if(!isset($error)) 
                $error = '';

	$logged_user = get_logged_user();	
	if($logged_user)
	{
		$ret = logout();
	}
	else
	{
		$error .= _("You are not logged in.");
	}

	if(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = "home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
?>
