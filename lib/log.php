<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/validate.php');

	function file_download_log($file)
	{
		if(!file_id_validation($file))
		{
			$query = "UPDATE files SET download_count = download_count +1 WHERE id='$file'";
			mysql_query($query);
	
			$query = "UPDATE global_stats SET value = value + 1 where name= 'files_downloaded'";
			mysql_query($query);
		}
	}
	function file_upload_log($file)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'files_uploaded'";
		mysql_query($query);
	}
	function lab_file_upload_log($file)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'lab_files_uploaded'";
		mysql_query($query);
	}
	function email_address_validation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'email_addresses_validated'";
		mysql_query($query);
	}
	function announcement_log($announcement)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'announcements_made'";
		mysql_query($query);
	}
	function email_notification_log($user,$announcement)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'email_notifications'";
		mysql_query($query);
	}
	function folder_creation_log($folder)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'file_folders_created'";
		mysql_query($query);
	}
	function lab_creation_log($lab)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'labs_created'";
		mysql_query($query);
	}
	function lab_team_creation_log($lab_team)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'labs_teams_created'";
		mysql_query($query);
	}
	function lab_team_creation_bulk_log($lab,$count)
	{
		if(is_numeric($count))
		{
			$query = "UPDATE global_stats SET value = value + $count where name= 'labs_teams_created'";
			mysql_query($query);
		}	
	}
	function password_change_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'passwords_changed'";
		mysql_query($query);
	}
	function user_account_creation_log($user)
	{
		$query = "UPDATE global_stats SET value = value + 1 where name= 'users_accounts_created'";
		mysql_query($query);
	}
?>
