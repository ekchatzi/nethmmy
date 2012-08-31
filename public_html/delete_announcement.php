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
	$aid = isset($_POST['aid'])?$_POST['aid']:'';
	$class = 0;
	if(!($e = announcement_id_validation($aid)))
	{
		/* select class for redirection later */
		$query = "SELECT class FROM announcements WHERE id='$aid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_edit_announcement($logged_userid,$aid))
			{
				$query = "DELETE FROM announcements WHERE id='$aid' LIMIT 1";
				if(mysql_query($query)
				{
					announcement_deletion_log($logged_userid,$class,$aid);
					$message[] = _('Announcement was deleted successfully.');
				}
			}
			else
			{
				$error[] = _('Access denied.');
			}	
		}
		else
		{
			$error[] = mysql_error();
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

		$redirect = "announcements/$class/";
		include('redirect.php');
	}
?>
