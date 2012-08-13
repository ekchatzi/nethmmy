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
	$file = isset($_POST['fid'])?$_POST['fid']:'';
	$folder = '';
	/* check input */	
	if(!(($e = name_validation($name)) || ($e = file_id_validation($file))))
	{
		/* select class for redirection later */
		$query = "SELECT folder FROM files WHERE id='$file'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$folder = mysql_result($ret,0,0);
			if(can_edit_file($logged_userid,$file))
			{

				$query = "UPDATE files SET
						name='".mysql_real_escape_string($name)."'
						WHERE id='$file' LIMIT 1";
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
		$redirect = "files/$folder/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>	
