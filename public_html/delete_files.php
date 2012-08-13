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
	$delete = isset($_POST['fid'])?$_POST['fid']:array();
	$folder = isset($_POST['folder'])?$_POST['folder']:'';
	if(!($e = id_list_validation($delete)))
	{
		/* delete */
		foreach($delete as $fid)
		{
			$query = "SELECT full_path FROM files WHERE id='$fid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$full_path = mysql_result($ret,0,0);
				if(can_edit_file($fid) && unlink($full_path))
				{
					$query = "DELETE FROM files WHERE id='$fid' LIMIT 1";
					mysql_query($query) || ($error .= mysql_error());
				}
				else
				{
					$error .= _('Access denied.');
				}
			}
		}
	}
	else
	{
		$error . $e;
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
		$redirect = ($folder)?"files/$folder/":"home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
