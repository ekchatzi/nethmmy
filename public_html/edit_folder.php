<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");


        if(!isset($error))
                $error = '';

	/*Get data from form*/
	$name = isset($_POST['name'])?$_POST['name']:'';
	$public =  isset($_POST['public'])?$_POST['public']:'0';
	$fid = isset($_POST['fid'])?$_POST['fid']:'';
	$class = '';
	/* check input */	
	if(!(($e = name_validation($name)) || ($e = folder_id_validation($fid)) || ($e = boolean_validation($public))))
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
