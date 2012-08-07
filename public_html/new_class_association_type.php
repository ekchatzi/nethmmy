<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

        if(!isset($error)) 
                $error = '';

	/* Get logged user identification data */
	$user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
	}

	/* Data */
	$title = isset($_POST['title'])?$_POST['title']:'';
	$priority = isset($_POST['priority'])?$_POST['priority']:'';
	$permissions = isset($_POST['permissions'])?$_POST['permissions']:'';

	if(can_edit_class_association_types($logged_userid))
	{
		/* check if input is valid */
		if(!(($e = name_validation($title)) || ($e = association_type_permissions_validation($permissions)) || ($e = association_type_priority_validation($priority))))
		{
			$query = "INSERT INTO class_association_types (title,priority,permissions)
					VALUES('$title','$priority','".mysql_real_escape_string(($permissions))."')";
			mysql_query($query) || ($error .= mysql_error());
		}
		else
		{
			$error .= $e;
		}	
	}
	else
	{
		$error .= _('Access denied.');
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
		$redirect = ($error)?"edit_class_association_types/":"class_association_types/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
	
?>
