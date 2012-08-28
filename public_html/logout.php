<?php
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';

	if($logged_user)
	{
		$ret = logout();
	}
	else
	{
		$error .= _("You are not logged in.");
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.$error.'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);

		$redirect = "home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>
