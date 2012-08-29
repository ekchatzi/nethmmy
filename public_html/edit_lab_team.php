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

	$lab = isset($_POST['lab'])?$_POST['lab']:0;
	$lab_team = 0;
	/*Get data from form*/
	$name = isset($_POST['name'])?$_POST['name']:'';
	$team = isset($_POST['tid'])?$_POST['tid']:'';
	$lock = isset($_POST['lock'])?'1':'0';
	/* check input */	
	if(!(($e = name_validation($name)) || ($e = lab_team_id_validation($team)) || ($e = boolean_int_validation($lock))))
	{
		/* select class for redirection later */
		$query = "SELECT lab FROM lab_teams WHERE id='$team'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$lab = mysql_result($ret,0,0);
			$lab_team = $team;
			if(can_edit_lab_team($logged_userid,$team))
			{
				$query = "UPDATE lab_teams SET
						title='".mysql_real_escape_string($name)."',
						is_locked='$lock'
						WHERE id='$team' LIMIT 1";
				mysql_query($query) || ($error[] = mysql_error());
				$message[] = _('Lab team was updated successfully.');
			}
			else
			{
				$error[] = _('Access denied.');
			}
		}
		else
		{
			$error[] = mysql_error();
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

		$redirect = "lab/$lab/".(($lab_team)?"#labTeamContainer$lab_team":"");;
		include('redirect.php');
	}
?>	
