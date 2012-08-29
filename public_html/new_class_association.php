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
	$user = isset($_POST['user'])?$_POST['user']:'';
	$type = isset($_POST['type'])?$_POST['type']:'';
	$class = isset($_POST['class'])?$_POST['class']:'';
	/* check if input is valid */
	if(!(($e = class_id_validation($class)) || ($e = user_id_validation($user)) || ($e = association_type_id_validation($type))))
	{
		if(can_edit_class_associations($logged_userid,$class))
		{

			$query = "INSERT INTO class_associations (class,user,type)
					VALUES('$class','$user','$type')";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _('Association was created successfully.');
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

		$redirect = "edit_class/$class/";
		include('redirect.php');
	}
?>
