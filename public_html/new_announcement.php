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
	$class = isset($_POST['class'])?$_POST['class']:'';
	/* check if input is valid */
	if(!(($e = class_id_validation($class)) || ($e = xml_validation($text)) || ($e = name_validation($title))))
	{
		if(can_post_announcement($logged_userid,$class))
		{
			$query = "INSERT INTO announcements (class,poster,title,text,post_time,update_time)
					VALUES('$class','$logged_userid','"
					.mysql_real_escape_string($title)."','"
					.mysql_real_escape_string(sanitize_html($text))."','".time()."','".time()."')";
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
		$redirect = "announcements/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
