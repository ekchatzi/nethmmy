<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once('../lib/log.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../config/security.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$to_keep = isset($_POST['count'])?$_POST['count']:$LOG_SIZE;
	if(can_clean_log($logged_userid))
	{
		$deleted = clean_log($logged_userid,$to_keep);
		if($deleted >= 0)
			$message[] = sprintf(_('Deleted %s log entries.'),$deleted);
	}
	else
	{
		$error[] = _('Access denied.');
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

		$redirect = "home/";
		include('redirect.php');
	}
?>
