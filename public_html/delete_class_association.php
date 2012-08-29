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
	$tid = isset($_POST['tid'])?$_POST['tid']:'';
	$class = isset($_POST['class'])?$_POST['class']:'';
	if(!($e = association_id_validation($tid)))
	{
		/* We get class from associations table because we don't trust post input.
		We use post 'class' for redirection initialiazation purposes*/
		$query = "SELECT class FROM class_associations WHERE id='$tid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_edit_class_associations($logged_userid,$class))
			{
				$query = "DELETE FROM class_associations WHERE id='$tid' LIMIT 1";
				mysql_query($query) || ($error[] = mysql_error());
				$message = _('Class association was deleted successfully.');
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
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').'],
			"message" : ['.(count($message)?('"'.implode('","',$message).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "edit_class/$class/";
		include('redirect.php');
	}
?>
