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
				mysql_query($query) || ($error .= mysql_error());
			}
			else
			{
				$error .= _('Access denied.');
			}	
		}
		else
		{
			$error .= mysql_error();
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
		$redirect = "announcements/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
