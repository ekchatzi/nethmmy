<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';

	/* Data */
	$uid = isset($_POST['uid'])?$_POST['uid']:'';
	$user = '';
	if(!($e = user_id_validation($uid)))
	{
		$user = $uid;
		if(can_delete_user($logged_userid,$uid))
		{
			$query = "DELETE FROM users WHERE id='$uid' LIMIT 1";
			mysql_query($query) || ($error .= mysql_error());		
		}
		else
		{
			$error .= _('Access denied.');
		}	
	}
	else
	{
		$error .= $e;
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.$error.'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = ($class && $error)?"profile/$class/":"home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
