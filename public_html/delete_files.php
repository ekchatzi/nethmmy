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
	$delete = isset($_POST['fid'])?$_POST['fid']:array();
	$folder = isset($_POST['folder'])?$_POST['folder']:'';
	if(!($e = id_list_validation($delete)))
	{
		/* delete */
		$deleted = 0;
		foreach($delete as $fid)
		{
			if(!($e = file_id_validation($fid)))
			{
				$query = "SELECT full_path FROM files WHERE id='$fid'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$full_path = mysql_result($ret,0,0);
					if(can_edit_file($logged_userid,$fid) && unlink($full_path))
					{
						//unlink if file is linked to lab team
						$query = "SELECT id,files FROM lab_teams WHERE FIND_IN_SET('$fid',files) LIMIT 1";
						$ret = mysql_query($query);
						if($ret && mysql_num_rows($ret))
						{
							$result = mysql_fetch_array($ret);
							$team = $result['id'];
							$files = explode(',',$result['files']);
							unset($files[array_search($fid,$files)]);
							$files = implode(',',array_values($files));

							$query = "UPDATE lab_teams SET
									files='".mysql_real_escape_string($files)."'
									WHERE id='$team' LIMIT 1";
							$ret = mysql_query($query) || ($error[] = mysql_error());
						}
						$query = "DELETE FROM files WHERE id='$fid' LIMIT 1";
						mysql_query($query) || ($error[] = mysql_error());
						$deleted++;					
					}
					else
					{
						$error[] = _('Access denied.');
					}
				}
			}
			else
			{
				$error[] = $e;				
			}
		}
		if($deleted)
			$message[] = sprintf(_("%s out of %s files were deleted successfully."),$deleted,count($delete)); 
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

		$redirect = ($folder)?"files/$folder/":$_COOKIE['last_view'];
		include('redirect.php');
	}
?>
