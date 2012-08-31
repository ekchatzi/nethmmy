<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../lib/token.php");

	if(!isset($error)) 
                $error = array();
				
	if(!isset($message))
		$message = array();
		
	$uid = isset($_POST['uid'])?$_POST['uid']:'';
	if(!($e = user_id_validation($uid)))
	{
		if(can_request_password_reset($logged_userid,$uid))
		{
			$token = get_token('password_reset',$uid);
		}
		else
		{
			$error[] = _('Access Denied.');
		}
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

		$redirect = "change_password/".$token."/";
		include('redirect.php');
	}
?>
		