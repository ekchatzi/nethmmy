<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once('../lib/log.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	/* Data */
	$cid = isset($_POST['cid'])?$_POST['cid']:'';
	$class = '';
	if(!($e = class_id_validation($cid)))
	{
		$class = $cid;
		if(can_delete_class($logged_userid,$cid))
		{
			$query = "DELETE FROM classes WHERE id='$cid' LIMIT 1";
			if(mysql_query($query))
			{
				$message[] = _('Class was deleted successfully.');
				class_deletion_log($logged_userid,$class);			
			}	
		}
		else
		{
			$error[] = _('Access denied.');
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

		$redirect = ($class && $error)?"class/$class/":"home/";
		include('redirect.php');
	}
?>
