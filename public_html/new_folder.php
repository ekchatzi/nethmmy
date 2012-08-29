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

	$classid = '';
	/* Data */
	$name = isset($_POST['name'])?$_POST['name']:'';
	$class = isset($_POST['class'])?$_POST['class']:'';
	$public = isset($_POST['public'])?$_POST['public']:'0';
	/* check if input is valid */
	if(!(($e = name_validation($name)) || ($e = class_id_validation($class)) || ($e = boolean_int_validation($public))))
	{
		if(can_create_folder($logged_userid,$class))//if user can add city
		{
			$query = "INSERT INTO file_folders (name,class,public)
					VALUES('".mysql_real_escape_string($name)."','$class','$public')";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _('Folder was created successfully.');
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

		$redirect = "class_files/$class/";
		include('redirect.php');
	}
?>
