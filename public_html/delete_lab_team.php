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

	$lab = isset($_POST['lab'])?$_POST['lab']:0;
	/* Data */
	$tid = isset($_POST['tid'])?$_POST['tid']:'';
	if(!$tid)
		$tid = isset($_GET['tid'])?$_GET['tid']:'';
	/* check if input is valid */
	if(!(($e = lab_team_id_validation($tid))))
	{
		if(can_delete_lab_team($logged_userid,$tid))
		{
			$uid = $logged_userid;
			$query = "SELECT lab FROM lab_teams WHERE id='$tid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$lab = $result['lab'];

				$query = "DELETE FROM lab_teams WHERE id='$tid'";
				mysql_query($query) || ($error[] = mysql_error());
				$message[] = _('Lab team was deleted successfully.');
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
		echo '{ "error" : "'.implode($MESSAGE_SEPERATOR,$error).'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "lab/$lab/";
		include('redirect.php');
	}
?>
