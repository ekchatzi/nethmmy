<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../config/security.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$lab_team = '';
	/* Data */
	$lab = isset($_POST['lid'])?$_POST['lid']:0;
	/* check if input is valid */
	if(!($e = lab_id_validation($lab)))
	{
		if(can_create_lab_team($logged_userid,$lab))
		{
			$query = "SELECT last_no FROM labs WHERE id='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$last_no = $result['last_no'] + 1;
				$team_name = "No. $last_no";
				$students = "$logged_userid";
				$is_locked = $DEFAULT_LAB_TEAM_LOCK_STATE?'1':'0';
				$folder = mysql_insert_id();
				$files = '';	
				$time = time();
				$query = "INSERT INTO lab_teams
						(lab,students,title,creation_time,update_time,files,is_locked)
						VALUES
						('$lab','$students','".mysql_real_escape_string($team_name)."','$time','$time','$files','$is_locked')";
				mysql_query($query) || ($error[] = mysql_error());
				$lab_team = mysql_insert_id();
				if($lab_team)
				{				
					$query = "UPDATE labs SET last_no = last_no + 1 WHERE id='$lab'";
					mysql_query($query) || ($error[] = mysql_error());	
					$message[] = sprintf(_('Lab team `%s`was created successfully.'),$team_name);			
				}
			}
			else
			{
				$error[] = mysql_error();
			}
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
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').'],
			"message" : ['.(count($message)?('"'.implode('","',$message).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "lab/$lab/".(($lab_team)?"#labTeamContainer$lab_team":"");
		include('redirect.php');
	}
?>
