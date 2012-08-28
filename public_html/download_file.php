<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../lib/stats.php");

        if(!isset($error))
                $error = array();

	/*Get data from get*/
	$fid = isset($_GET['fid'])?$_GET['fid']:'';

	/* check input */	
	if(!($e = file_id_validation($fid)))
	{
		if(can_download_file($logged_userid,$fid))
		{
			$query = "SELECT full_path FROM files
					WHERE id='$fid' LIMIT 1";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$filepath = mysql_result($ret,0,0);

				if(file_exists($filepath))
				{
					file_download_stat_log($fid);
					$file_extension = strtolower(substr(strrchr($filepath,"."),1));
					$ctype = (isset($FILE_CONTENT_TYPES))?$FILE_CONTENT_TYPES[$file_extension]:"application/force-download";
					header("Pragma: public"); // required
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private",false); // required for certain browsers
					header("Content-Type: ".$ctype."");
					header("Content-Disposition: attachment; filename=\"".basename($filepath)."\";" );
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".filesize($filepath));
					readfile($filepath);
				}
				else
				{
					$error[] = _('File does not exist.');
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
		$error[] = $e;
	}

	if(isset($error) && count($error))
	{
		setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);
		$redirect = isset($_COOKIE['ref'])?$_COOKIE['ref']:"home/";
		include('redirect.php');
	}
?>	
