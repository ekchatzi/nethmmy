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
	$urgent = isset($_POST['urgent'])?$_POST['urgent']:'0';
	$class = 0;
	
	if($text!='')
	{	
		//you have to use php_tidy extension to get it to work
		$text = tidy_repair_string($text, array('show-body-only' => true, 'doctype' => '-//W3C//DTD XHTML 1.0 Transitional//EN', 'output-xhtml' => true));
	}
	
	/* check if input is valid */
	if(!(($e = announcement_id_validation($aid)) || ($e = xml_validation($text)) || ($e = name_validation($title))))
	{	
		if($urgent)
		{
			//email the users of this class//
			$query = "SELECT title FROM classes WHERE id='$class'";
			$ret = mysql_query($query);
			if(!($ret && mysql_num_rows($ret) && ($class_title = mysql_result($ret,0,0))))
			{
				$class_title = _('ethmmy class');
			}
			$query = "SELECT email FROM users WHERE FIND_IN_SET($class, classes) AND email_urgent = '1'";
			$ret = mysql_query($query);
			if($ret && mysql_numrows($ret))
			{
				while($row = mysql_fetch_array($ret))
				{
					$to = $row['email'];
					$subject =_('[ethmmy] Urgent announcement from ').$class_title;
					$message = $title.'\n'.$text;
					$headers = _('From: ').$NOTIFY_EMAIL_ADDRESS.'\n';
					
					if(!mail($to, $subject, $message, $headers))
					{
						$error .= _("Urgent delivery failed. Please try again by editing your last announcement.");
					}
				}
			}		
		}
		
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
						update_time = '".time()."',
						is_urgent = $urgent
						WHERE id='$aid' LIMIT 1";
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
