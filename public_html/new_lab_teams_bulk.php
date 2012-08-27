<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../config/security.php");

        if(!isset($error)) 
                $error = '';

	$lab_team = '';
	/* Data */
	$lab = isset($_POST['lid'])?$_POST['lid']:0;
	$count = isset($_POST['count'])?$_POST['count']:0;
	/* check if input is valid */
	if(!($e = lab_id_validation($lab)))
	{
		if(can_create_lab_teams_bulk($logged_userid,$lab))
		{
			$query = "SELECT COUNT(*) FROM lab_teams WHERE lab='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$c = mysql_result($ret,0,0);
				$query = "SELECT last_no,team_limit FROM labs WHERE id='$lab'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$result = mysql_fetch_array($ret);
					$team_limit = $result['team_limit'];
					$last_no = $result['last_no'];
					$time = time();
					$new_teams_count = min($team_limit,$count + $c) - $c;
					$is_locked = $DEFAULT_LAB_TEAM_LOCK_STATE?'1':'0';
					if($new_teams_count < $count)
						$error .= _('Team limit has been reached.');
					
					if($new_teams_count > 0)
					{
						$values = array();
						for($i=0;$i<$new_teams_count;++$i)
						{
							$team_name = "No. ".($last_no + $i +1);
							$values[] = "('$lab','','".mysql_real_escape_string($team_name)."','$time','$time','','$is_locked')";
						}
						$values = implode(',',$values);
						$query = "INSERT INTO lab_teams
								(lab,students,title,creation_time,update_time,files,is_locked)
								VALUES
								$values";
						mysql_query($query) || ($error .= mysql_error());
						$lab_team = mysql_insert_id();
						if($lab_team)
						{				
							$query = "UPDATE labs SET last_no = last_no + $new_teams_count WHERE id='$lab'";
							mysql_query($query) || ($error .= mysql_error());				
						}
					}
				}
				else
				{
					$error .= mysql_error();
				}				
			}
			else
			{
				$error .= mysql_error();
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
		$redirect = "lab/$lab/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>
