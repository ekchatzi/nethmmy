<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");


        if(!isset($error))
                $error = '';

	/*Get data from form*/
	$title = isset($_POST['title'])?$_POST['title']:'';
	$description =  isset($_POST['description'])?$_POST['description']:'';
	$cid = isset($_POST['cid'])?$_POST['cid']:'';
	$semesters = isset($_POST['semesters'])?$_POST['semesters']:'';
	/* Get logged user identification data */
	$logged_user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$logged_user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
	}	
	if(!($e = class_id_validation($cid)))
	{
		if(can_edit_class($logged_userid,$cid))
		{
			if(!(($e = name_validation($title)) || ($e = semester_list_validation($semesters)) || ($e = xml_validation($description))))
			{
				$query = "UPDATE classes SET
						title='".mysql_real_escape_string($title)."',
						description='".mysql_real_escape_string(sanitize_html($description))."',
						semesters='$semesters'
						WHERE id='$cid' LIMIT 1";
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
		$redirect = ($error)?"edit_class/$cid/":"class/$cid/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>	
