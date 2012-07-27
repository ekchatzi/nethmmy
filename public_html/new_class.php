<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate_input.php");

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

	/*Data*/
	$title = isset($_POST['title'])?$_POST['title']:'';
	$description = isset($_POST['description'])?$_POST['description']:'';
	$semester = isset($_POST['semester'])?$_POST['semester']:'0';
	if(can_create_class($logged_userid))//if user can add city
	{
		//check if city is valid
		if($name && ($name == addslashes($name)))
		{
			$query = "INSERT INTO classes (title,description,semester)
					VALUES('$name')";
			mysql_query($query) || ($error .= mysql_error());	
		}
		else
		{
			$error .= _('City name is not valid.');
		}	
	}
	else
	{
		$error .= _('Access denied.');
	}
	echo '{ "error" : "'.$error.'"}';
?>
