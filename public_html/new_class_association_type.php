<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	/* Data */
	$title = isset($_POST['title'])?$_POST['title']:'';
	$priority = isset($_POST['priority'])?$_POST['priority']:'';
	$permissions = isset($_POST['permissions'])?$_POST['permissions']:'';
	/* check if input is valid */
	if(!(($e = name_validation($title)) || ($e = association_type_permissions_validation($permissions)) || ($e = association_type_priority_validation($priority))))
	{
		if(can_edit_class_association_types($logged_userid))
		{
			$query = "INSERT INTO class_association_types (title,priority,permissions)
					VALUES('$title','$priority','".mysql_real_escape_string(($permissions))."')";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _('New association type was created successfully.');
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
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = ($error)?"edit_class_association_types/":"class_association_types/";
		include('redirect.php');
	}
?>
