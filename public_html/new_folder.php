<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';

	$classid = '';

	/* Data */
	$name = isset($_POST['name'])?$_POST['name']:'';
	$class = isset($_POST['class'])?$_POST['class']:'';
	$public = isset($_POST['public'])?$_POST['public']:'0';
	/* check if input is valid */
	if(!(($e = name_validation($name)) || ($e = class_id_validation($class)) || ($e = boolean_validation($public))))
	{
		if(can_create_folder($logged_userid,$class))//if user can add city
		{

				$query = "INSERT INTO file_folders (name,class,public)
						VALUES('".mysql_real_escape_string($name)."','$class','$public')";
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
		$redirect = "class_files/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
