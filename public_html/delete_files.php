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
							mysql_query($query) || ($error .= mysql_error());
						}
						$query = "DELETE FROM files WHERE id='$fid' LIMIT 1";
						mysql_query($query) || ($error .= mysql_error());
					}
					else
					{
						$error .= _('Access denied.');
					}
				}
			}
			else
			{
				$error . $e;				
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
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);


		$redirect = ($folder)?"files/$folder/":$_COOKIE['last_view'];
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
