<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/localization.php');
	include_once('../lib/validate.php');
	include_once('../config/security.php');

	function clean_log($message,$to_keep)
	{
		global $MIN_LOG_MESSAGES_KEPT;
		$to_keep = max($MIN_LOG_MESSAGES_KEPT,$to_keep);
		$query = 'SELECT COUNT(*) FROM log WHERE 1';
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			$to_delete = max(0,$count - $to_keep);
			if($to_delete > 0)
			{
				$query = "DELETE FROM log ORDER BY time ASC LIMIT $to_delete";
				mysql_query($query);
			}
			return $to_delete;
		}
	}
	function log_entry($type,$data)
	{
		global $ALLOWED_LOG_TYPES;
		if(in_array($type,$ALLOWED_LOG_TYPES))
		{
			$query = "INSERT INTO log (time,type,data) VALUES ('".time()."','$type','".mysql_real_escape_string($data)."')";
			mysql_query($query);
		}
	}
	function parse_log_message($type,$data)
	{
		$d = explode(',',$data);
		switch($type)
		{
			case 100:
				return sprintf(_('User #%s downloaded file #%s from ip address %s.'),$d[0],$d[1],$d[2]);
			case 101:
				return sprintf(_('File #%s was uploaded from ip address %s.'),$d[0],$d[1]);		
			case 102:
				return sprintf(_('Lab file #%s was uploaded from ip address %s.'),$d[0],$d[1]);
			case 103:
				return sprintf(_('User #%s validated his email address.'),$d[0]);
			case 104:
				return sprintf(_('Announcement #%s was posted.'),$d[0]);
			case 105:
				return sprintf(_('Folder #%s was created by user #%s.'),$d[0],$d[1]);
			case 106:
				return sprintf(_('Lab #%s was created by user #%s.'),$d[0],$d[1]);
			case 107:
				return sprintf(_('Lab team #%s was created.'),$d[0]);
			case 108:
				return sprintf(_('User #%s created %s team(s) for lab #%s.'),$d[0],$d[2],$d[1]);
			case 109:
				return sprintf(_('User #%s changed his password.'),$d[0]);
			case 110:
				return sprintf(_('New user #%s.'),$d[0]);
			default:
				return sprintf(_('Log message type `%s` with data `%s`'),$type,$data);	
		}
	}


	function file_download_log($file,$downloader,$ip)
	{
		if(!file_id_validation($file))
		{
			$query = "UPDATE files SET download_count = download_count +1 WHERE id='$file'";
			mysql_query($query);
	
			$query = "UPDATE global_stats SET value = value + 1 where name= 'files_downloaded'";
			mysql_query($query);
			
			log_entry(100,implode(',',array($file,$downloader,$ip)));
		}
	}
	function file_upload_log($file,$ip)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'files_uploaded'";
		mysql_query($query);
		log_entry(101,implode(',',array($file,$ip)));
	}
	function lab_file_upload_log($file,$ip)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'lab_files_uploaded'";
		mysql_query($query);
		log_entry(102,implode(',',array($file,$ip)));
	}
	function email_address_validation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'email_addresses_validated'";
		mysql_query($query);
		log_entry(103,implode(',',$user));
	}
	function announcement_log($announcement)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'announcements_made'";
		mysql_query($query);
		log_entry(104,implode(',',$announcement));
	}
	function email_notification_log($user,$announcement)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'email_notifications'";
		mysql_query($query);
	}
	function folder_creation_log($user,$folder)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'file_folders_created'";
		mysql_query($query);
		log_entry(105,implode(',',$user,$folder));
	}
	function lab_creation_log($user,$lab)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'labs_created'";
		mysql_query($query);
		log_entry(106,implode(',',$user,$lab));
	}
	function lab_team_creation_log($lab_team)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'labs_teams_created'";
		mysql_query($query);
		log_entry(107,implode(',',$lab_team));
	}
	function lab_team_creation_bulk_log($user,$lab,$count)
	{
		if(is_numeric($count))
		{
			$query = "UPDATE global_stats SET value = value + $count where name= 'labs_teams_created'";
			mysql_query($query);
			log_entry(108,implode(',',$user,$lab,$count));
		}	
	}
	function password_change_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'passwords_changed'";
		mysql_query($query);
		log_entry(109,implode(',',$user));
	}
	function user_account_creation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'users_accounts_created'";
		mysql_query($query);
		log_entry(110,implode(',',$user));
	}
?>
