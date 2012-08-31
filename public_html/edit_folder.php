<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once('../lib/log.php');

        if(!isset($error))
                $error = array();

	if(!isset($message))
		$message = array();

	/*Get data from form*/
	$name = isset($_POST['name'])?$_POST['name']:'';
	$public =  isset($_POST['public'])?$_POST['public']:'0';
	$fid = isset($_POST['fid'])?$_POST['fid']:'';
	$class = '';
	/* check input */	
	if(!(($e = name_validation($name)) || ($e = folder_id_validation($fid)) || ($e = boolean_int_validation($public))))
	{
		/* select class for redirection later */
		$query = "SELECT class FROM file_folders WHERE id='$fid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_edit_folder($logged_userid,$fid))
			{

				$query = "UPDATE file_folders SET
						name='".mysql_real_escape_string($name)."',
						public='".mysql_real_escape_string($public)."'
						WHERE id='$fid' LIMIT 1";
				if(mysql_query($query))
				{
					folder_edit_log($logged_userid,$fid);
					$message[] = _("Folder information were updated successfully.");
				}
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

		$redirect = "class_files/$class/";
		include('redirect.php');
	}
?>	
