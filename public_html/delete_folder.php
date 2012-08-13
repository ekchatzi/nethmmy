<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';

	/* Data */
	$fid = isset($_POST['fid'])?$_POST['fid']:'';
	$class = '';
	if(!($e = folder_id_validation($fid)))
	{
		$query = "SELECT class FROM file_folders WHERE id='$fid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$class = mysql_result($ret,0,0);
			if(can_delete_folder($logged_userid,$fid))
			{
				$query = "DELETE FROM file_folders WHERE id='$fid' LIMIT 1";
				mysql_query($query) || ($error .= mysql_error());
			}
			else
			{
				$error .= _('Access denied.');
			}	
		}
		else
		{
			$error .= mysql_error();
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
		$redirect = "class_files/$class/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
