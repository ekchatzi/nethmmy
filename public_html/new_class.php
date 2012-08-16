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
				mysql_query($query) || ($error .= mysql_error());
				$classid = mysql_insert_id();	

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
		$redirect = ($error)?"new_class/":"class/$classid/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
