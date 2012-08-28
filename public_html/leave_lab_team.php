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
			$query = "SELECT lab,students FROM lab_teams WHERE id='$tid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$lab = $result['lab'];
				$students = $result['students'];
				$lab_team = $tid;
				$c = preg_match_all('~\b[0-9]\b~',$students,$m);
				$students = ($c > 0)?explode(',',$students):array();
				unset($students[array_search($uid,$students)]);

				$students = implode(',',$students);
				$time = time();
				$query = "UPDATE lab_teams SET 
						students='".mysql_real_escape_string($students)."',
						update_time='$time'
						WHERE id='$tid'";
				mysql_query($query) || ($error .= mysql_error());

				if($c-1 == 0)
				{
					$query = "SELECT can_make_new_teams FROM labs WHERE id='$lab'";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						if(mysql_result($ret,0,0))
						{
							$query = "DELETE FROM lab_teams WHERE id='$tid'";
							mysql_query($query) || ($error .= mysql_error());
						}
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
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);


		$redirect = "lab/$lab/".(($lab_team)?"#labTeamContainer$lab_team":"");
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>
