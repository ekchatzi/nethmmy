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
				$query = "SELECT COUNT(*) FROM files WHERE folder='$fid'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$count = mysql_result($ret,0,0);
					if($count == 0)
					{
						$query = "DELETE FROM file_folders WHERE id='$fid' LIMIT 1";
						mysql_query($query) || ($error[] = mysql_error());
						$message[] = _('Folder was deleted successfully.');
					}				
					else
					{
						$error[] = _('Folder is not empty.');
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
			$error[] = mysql_error();
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

		$redirect = "class_files/$class/";
		include('redirect.php');
	}	
?>
