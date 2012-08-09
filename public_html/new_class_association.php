<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

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
	$user = isset($_POST['user'])?$_POST['user']:'';
	$type = isset($_POST['type'])?$_POST['type']:'';
	$class = isset($_POST['class'])?$_POST['class']:'';
	if(can_edit_class_associations($logged_userid,$class))
	{
		/* check if input is valid */
		if(!(($e = class_id_validation($class)) || ($e = user_id_validation($user)) || ($e = association_type_id_validation($type))))
		{
			$query = "INSERT INTO class_associations (class,user,type)
					VALUES('$class','$user','$type')";
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
		$redirect = "edit_class/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
