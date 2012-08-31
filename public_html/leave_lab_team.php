<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../config/security.php");
	include_once('../lib/log.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$lab_team = '';
	$lab = '';
	/* Data */
	$tid = isset($_POST['tid'])?$_POST['tid']:'';
	if(!$tid)
		$tid = isset($_GET['tid'])?$_GET['tid']:'';
	/* check if input is valid */
	if(!(($e = lab_team_id_validation($tid))))
	{
		if(can_leave_lab_team($logged_userid,$tid))
		{
			$uid = $logged_userid;
			$query = "SELECT lab,students,title FROM lab_teams WHERE id='$tid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$lab = $result['lab'];
				$students = $result['students'];
				$lab_team = $tid;
				$team_title = $result['title'];
				$c = preg_match_all('~\b[0-9]\b~',$students,$m);
				$students = ($c > 0)?explode(',',$students):array();
				unset($students[array_search($uid,$students)]);

				$students = implode(',',$students);
				$time = time();
				$query = "UPDATE lab_teams SET 
						students='".mysql_real_escape_string($students)."',
						update_time='$time'
						WHERE id='$tid'";
				if(mysql_query($query))
				{
					lab_team_leave_log($logged_userid,$tid);
					$message[] = sprintf(_("You left lab team `%s`."),$team_title); 
				}
				if($c-1 == 0)
				{
					$query = "SELECT can_make_new_teams FROM labs WHERE id='$lab'";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						if(mysql_result($ret,0,0))
						{
							$query = "DELETE FROM lab_teams WHERE id='$tid'";
							mysql_query($query) || ($error[] = mysql_error());
							$message[] = sprintf(_("Lab team `%s` was deleted."),$team_title);
						}
					}
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
