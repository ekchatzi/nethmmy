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
	$title = isset($_POST['title'])?$_POST['title']:'';
	$text = isset($_POST['text'])?$_POST['text']:'';
	$aid = isset($_POST['id'])?$_POST['id']:'';
	$class = 0;
	/* check if input is valid */
	if(!(($e = announcement_id_validation($aid)) || ($e = xml_validation($text)) || ($e = name_validation($title))))
	{
		/* select class for redirection later */
		$query = "SELECT class FROM announcements WHERE id='$aid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_edit_announcement($logged_userid,$aid))
			{
				$query = "UPDATE announcements SET
						title = '".mysql_real_escape_string($title)."',
						text = '".mysql_real_escape_string(sanitize_html($text))."',
						update_time = '".time()."' WHERE id='$aid' LIMIT 1";
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
		$redirect = ($error)?"edit_announcement/$aid/":"announcements/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
