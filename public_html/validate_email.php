<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/token.php");
	include_once("../config/security.php");
	include_once("../lib/localization.php"); 

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$uid = 0;
	$token = isset($_GET['token'])?$_GET['token']:'';
	if(verify_token($token,'validate_email'))//if token is valid
	{
		$query = "SELECT data FROM tokens WHERE code='$token'";
		($ret = mysql_query($query)) || ($error = $query . "||" .mysql_error());
		if($ret && mysql_numrows($ret))
		{
			$uid = mysql_result($ret,0,0);//data == uid
			$query = "UPDATE users SET is_email_validated = '1'
					WHERE id='$uid' LIMIT 1";
			mysql_query($query) || ($error .= mysql_error());
			delete_token($token);
			$message[] = _('Email address is now validated.');
		}
		else
		{
			$error .= _('Token is no longer avainable.');
		}
	}
	else
	{
		$error .= _('Token is invalid.');
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
