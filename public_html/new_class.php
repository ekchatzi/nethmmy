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
	$title = isset($_POST['title'])?$_POST['title']:'';
	$description = isset($_POST['description'])?$_POST['description']:'';
	$semesters = isset($_POST['semesters'])?$_POST['semesters']:'0';
	/* check if input is valid */
	if(!(($e = name_validation($title)) || ($e = semester_list_validation($semesters)) || ($e = xml_validation($description))))
	{
		if(can_create_class($logged_userid))
		{
			$query = "INSERT INTO classes (title,description,semesters)
					VALUES('$title','".mysql_real_escape_string(sanitize_html($description))."','$semesters')";
			mysql_query($query) || ($error[] = mysql_error());
			$classid = mysql_insert_id();
			$message[] = _('Class was created successfully.');
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

		$redirect = ($error)?"new_class/":"class/$classid/";
		include('redirect.php');
	}
?>
