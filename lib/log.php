<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/localization.php');
	include_once('../lib/validate.php');
	include_once('../config/security.php');

	function clean_log($user,$to_keep)
	{
		global $MIN_LOG_MESSAGES_KEPT,$MIN_AGE_LOG_MESSAGES_KEPT;
		$to_keep = max($MIN_LOG_MESSAGES_KEPT,$to_keep);
		$query = 'SELECT COUNT(*) FROM log WHERE 1';
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$since = time() - $MIN_AGE_LOG_MESSAGES_KEPT;
			$count = mysql_result($ret,0,0);
			$to_delete = max(0,$count - $to_keep);
			if($to_delete > 0)
			{
				$query = "DELETE FROM log WHERE time > $since ORDER BY time ASC LIMIT $to_delete";
				if(mysql_query($query))
				{
					clean_log_log($user,$to_delete);
					return $to_delete;
				}
			}
		}
		return 0;
	}
	function log_entry($type,$data,$user,$class)
	{
		global $ALLOWED_LOG_TYPES;
		if(in_array($type,$ALLOWED_LOG_TYPES))
		{
			$query = "INSERT INTO log (time,type,data,user,class) VALUES ('".time()."','$type','".mysql_real_escape_string($data)."','".mysql_real_escape_string($user)."','".mysql_real_escape_string($class)."')";
			mysql_query($query);
		}
	}
	function parse_log_message($type,$data)
	{
		$d = explode(',',$data);
		switch($type)
		{
			case 100:
				return sprintf(_('User #%s downloaded file #%s from %s.'),$d[0],$d[1],$d[2]);
			case 101:
				return sprintf(_('User #%s uploaded file #%s from %s.'),$d[0],$d[1],$d[2]);		
			case 102:
				return sprintf(_('User #%s uploaded lab file #%s from %s.'),$d[0],$d[1],$d[2]);
			case 103:
				return sprintf(_('User #%s validated his email address.'),$d[0]);
			case 104:
				return sprintf(_('User #%s posted announcement #%s.'),$d[0],$d[1]);
			case 105:
				return sprintf(_('User #%s created file folder #%s.'),$d[0],$d[1]);
			case 106:
				return sprintf(_('User #%s created lab #%s.'),$d[0],$d[1]);
			case 107:
				return sprintf(_('User #%s created lab team #%s.'),$d[0],$d[1]);
			case 108:
				return sprintf(_('User #%s created %s team(s) for lab #%s.'),$d[0],$d[2],$d[1]);
			case 109:
				return sprintf(_('User #%s changed his password.'),$d[0]);
			case 110:
				return sprintf(_('New user #%s.'),$d[0]);
			case 111:
				return sprintf(_('User #%s created class #%s.'),$d[0],$d[1]);
			case 112:
				return sprintf(_('User #%s deleted class #%s.'),$d[0],$d[1]);
			case 113:
				return sprintf(_('User #%s edited class #%s.'),$d[0],$d[1]);
			case 114:
				return sprintf(_('User #%s deleted lab #%s.'),$d[0],$d[1]);
			case 115:
				return sprintf(_('User #%s edited lab #%s.'),$d[0],$d[1]);
			case 116:
				return sprintf(_('User #%s deleted lab team #%s.'),$d[0],$d[1]);
			case 117:
				return sprintf(_('User #%s edited lab team #%s.'),$d[0],$d[1]);
			case 118:
				return sprintf(_('User #%s deleted announcement #%s.'),$d[0],$d[1]);
			case 119:
				return sprintf(_('User #%s edited announcement #%s.'),$d[0],$d[1]);
			case 120:
				return sprintf(_('User #%s deleted file #%s.'),$d[0],$d[1]);
			case 121:
				return sprintf(_('User #%s edited file #%s.'),$d[0],$d[1]);
			case 122:
				return sprintf(_('User #%s deleted folder #%s.'),$d[0],$d[1]);
			case 123:
				return sprintf(_('User #%s edited folder #%s.'),$d[0],$d[1]);
			case 124:
				return sprintf(_('User #%s deleted user account #%s.'),$d[0],$d[1]);
			case 125:
				return sprintf(_('User #%s edited user account #%s.'),$d[0],$d[1]);
			case 126:
				return sprintf(_('User #%s joined lab team #%s.'),$d[0],$d[1]);
			case 127:
				return sprintf(_('User #%s left lab team #%s.'),$d[0],$d[1]);
			case 128:
				return sprintf(_('User #%s kicked from lab team #%s user #%s.'),$d[0],$d[1],$d[2]);
			case 129:
				return sprintf(_('User #%s deleted titles #(%s).'),$d[0],$d[1]);
			case 130:
				return sprintf(_('User #%s edited title #%s.'),$d[0],$d[1]);
			case 131:
				return sprintf(_('User #%s deleted association type #%s.'),$d[0],$d[1]);
			case 132:
				return sprintf(_('User #%s edited association type #%s.'),$d[0],$d[1]);
			case 133:
				return sprintf(_('User #%s deleted association #%s.'),$d[0],$d[1]);
			case 134:
				return sprintf(_('User #%s created association #%s.'),$d[0],$d[1]);
			case 135:
				return sprintf(_('User #%s edited user\'s #%s AEM.'),$d[0],$d[1]);
			case 136:
				return sprintf(_('User #%s edited user\'s #%s user type.'),$d[0],$d[1]);
			case 137:
				return sprintf(_('User #%s edited user\'s #%s active status.'),$d[0],$d[1]);
			case 138:
				return sprintf(_('User #%s logged in from %s.'),$d[0],$d[1]);
			case 139:
				return sprintf(_('User #%s logged out.'),$d[0]);
			case 200:
				return sprintf(_('User #%s cleaned %s log messages.'),$d[0],$d[1]);
			default:
				return sprintf(_('Log message type `%s` with data `%s`'),$type,$data);	
		}
	}




	function file_download_log($user,$file,$ip)
	{
		if(!file_id_validation($file))
		{
			$query = "UPDATE files SET download_count = download_count +1 WHERE id='$file'";
			mysql_query($query);
	
			$query = "UPDATE global_stats SET value = value + 1 where name= 'files_downloaded'";
			mysql_query($query);
			
			log_entry(100,implode(',',array($user,$file,$ip)),$user,0);
		}
	}
	function file_upload_log($user,$file,$ip)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'files_uploaded'";
		mysql_query($query);
		log_entry(101,implode(',',array($user,$file,$ip)),$user,0);
	}
	function lab_file_upload_log($user,$file,$ip)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'lab_files_uploaded'";
		mysql_query($query);
		log_entry(102,implode(',',array($file,$ip)),$user,0);
	}
	function email_address_validation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'email_addresses_validated'";
		mysql_query($query);
		log_entry(103,implode(',',array($user)),$user,0);
	}
	function announcement_creation_log($user,$announcement)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'announcements_made'";
		mysql_query($query);
		log_entry(104,implode(',',array($announcement)),$user,0);
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
		log_entry(105,implode(',',array($user,$folder)),$user,0);
	}
	function lab_creation_log($user,$lab)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'labs_created'";
		mysql_query($query);
		log_entry(106,implode(',',array($user,$lab)),$user,0);
	}
	function lab_team_creation_log($user,$lab_team)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'lab_teams_created'";
		mysql_query($query);
		log_entry(107,implode(',',array($lab_team)),$user,0);
	}
	function lab_team_creation_bulk_log($user,$lab,$count)
	{
		if(is_numeric($count))
		{
			$query = "UPDATE global_stats SET value = value + $count where name= 'lab_teams_created'";
			mysql_query($query);
			log_entry(108,implode(',',array($user,$lab,$count)),$user,0);
		}	
	}
	function password_change_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'passwords_changed'";
		mysql_query($query);
		log_entry(109,implode(',',array($user)),$user,0);
	}
	function user_account_creation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'users_accounts_created'";
		mysql_query($query);
		log_entry(110,implode(',',array($user)),$user,0);
	}
	function class_creation_log($user,$class)
	{
		log_entry(111,implode(',',array($user,$class)),$user,$class);
	}
	function class_deletion_log($user,$class)
	{
		log_entry(112,implode(',',array($user,$class)),$user,$class);
	}
	function class_edit_log($user,$class)
	{
		log_entry(113,implode(',',array($user,$class)),$user,$class);
	}
	function lab_deletion_log($user,$class,$lab)
	{
		log_entry(114,implode(',',array($user,$lab)),$user,$class);
	}
	function lab_edit_log($user,$lab)
	{
		log_entry(115,implode(',',array($user,$lab)),$user,0);
	}
	function lab_team_deletion_log($user,$class,$team)
	{
		log_entry(116,implode(',',array($user,$team)),$user,$class);
	}
	function lab_team_edit_log($user,$team)
	{
		log_entry(117,implode(',',array($user,$team)),$user,0);
	}
	function announcement_deletion_log($user,$class,$an)
	{
		log_entry(118,implode(',',array($user,$an)),$user,$class);
	}
	function announcement_edit_log($user,$an)
	{
		log_entry(119,implode(',',array($user,$an)),$user,0);
	}
	function file_deletion_log($user,$class,$file)
	{
		log_entry(120,implode(',',array($user,$file)),$user,$class);
	}
	function file_edit_log($user,$file)
	{
		log_entry(121,implode(',',array($user,$file)),$user,0);
	}
	function folder_deletion_log($user,$class,$folder)
	{
		log_entry(122,implode(',',array($user,$folder)),$user,$class);
	}
	function folder_edit_log($user,$folder)
	{
		log_entry(123,implode(',',array($user,$folder)),$user,0);
	}
	function user_deletion_log($user,$target_user)
	{
		log_entry(124,implode(',',array($user,$target_user)),$user,0);
	}
	function user_edit_log($user,$target_user)
	{
		log_entry(125,implode(',',array($user,$target_user)),$user,0);
	}
	function lab_team_join_log($user,$team)
	{
		log_entry(126,implode(',',array($user,$team)),$user,0);
	}
	function lab_team_leave_log($user,$team)
	{
		log_entry(127,implode(',',array($user,$team)),$user,0);
	}
	function lab_team_kick_log($user,$team,$target_user)
	{
		log_entry(128,implode(',',array($user,$team,$target_user)),$user,0);
	}
	function titles_deletion_log($user,$titles)
	{
		log_entry(129,implode(',',array($user,$titles)),$user,0);
	}
	function title_edit_log($user,$title)
	{
		log_entry(130,implode(',',array($user,$title)),$user,0);
	}
	function association_types_deletion_log($user,$type)
	{
		log_entry(131,implode(',',array($user,$type)),$user,0);
	}
	function association_type_edit_log($user,$type)
	{
		log_entry(132,implode(',',array($user,$type)),$user,0);
	}
	function association_deletion_log($user,$class,$assoc)
	{
		log_entry(133,implode(',',array($user,$assoc)),$user,0);
	}
	function association_creation_log($user,$assoc)
	{
		log_entry(134,implode(',',array($user,$assoc)),$user,0);
	}
	function user_edit_aem_log($user,$target_user)
	{
		log_entry(135,implode(',',array($user,$target_user)),$user,0);
	}
	function user_type_edit_log($user,$target_user)
	{
		log_entry(136,implode(',',array($user,$target_user)),$user,0);
	}
	function user_active_status_edit_log($user,$target_user)
	{
		log_entry(137,implode(',',array($user,$target_user)),$user,0);
	}
	function login_log($user,$ip)
	{
		log_entry(138,implode(',',array($user,$ip)),$user,0);
	}
	function logout_log($user)
	{
		log_entry(139,implode(',',array($user)),$user,0);
	}
	function clean_log_log($user,$count)
	{
		log_entry(200,implode(',',array($user,$count)),$user,0);
	}
?>
