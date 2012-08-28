<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	/* Data */
	$lid = isset($_POST['lid'])?$_POST['lid']:'';
	$class = '';
	if(!($e = lab_id_validation($lid)))
	{
		$query = "SELECT class FROM labs WHERE id='$lid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_delete_lab($logged_userid,$lid))
			{
				$query = "DELETE FROM labs WHERE id='$lid' LIMIT 1";
				mysql_query($query) || ($error[] = mysql_error());

				$query = "DELETE FROM lab_teams WHERE lab='$lid'";
				mysql_query($query) || ($error[] = mysql_error());
				$message[] = _("Lab was deleted successfully.");
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
		echo '{ "error" : ["'.implode('","',$error).'"]}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "class/$class/";
		include('redirect.php');
	}
?>
