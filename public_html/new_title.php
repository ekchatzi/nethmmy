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
	$description = isset($_POST['description'])?$_POST['description']:'';
	/* check if input is valid */
	if(!(($e = name_validation($title)) || ($e = xml_validation($description))))
	{
		if(can_edit_titles($logged_userid))
		{
				$query = "INSERT INTO titles (title,description)
						VALUES('$title','".mysql_real_escape_string(sanitize_html($description))."')";
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
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);


		$redirect = "edit_titles/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
