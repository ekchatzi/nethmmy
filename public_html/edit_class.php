<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");


        if(!isset($error))
                $error = array();

	if(!isset($message))
		$message = array();

	/*Get data from form*/
	$title = isset($_POST['title'])?$_POST['title']:'';
	$description =  isset($_POST['description'])?$_POST['description']:'';
	$cid = isset($_POST['cid'])?$_POST['cid']:'';
	$semesters = isset($_POST['semesters'])?$_POST['semesters']:'';
	/* check input */	
	if(!(($e = name_validation($title)) || ($e = class_id_validation($cid)) || ($e = semester_list_validation($semesters)) || ($e = xml_validation($description))))
	{
		if(can_edit_class($logged_userid,$cid))
		{

			$query = "UPDATE classes SET
					title='".mysql_real_escape_string($title)."',
					description='".mysql_real_escape_string(sanitize_html($description))."',
					semesters='$semesters'
					WHERE id='$cid' LIMIT 1";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _("Class updated successfully.");
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
		echo '{ "error" : "'.implode($MESSAGE_SEPERATOR,$error).'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = ($error)?"edit_class/$cid/":"class/$cid/";
		include('redirect.php');
	}
?>	
