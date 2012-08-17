<?php
	include_once('../config/security.php');
	include_once('../lib/login.php');
	
	function can_create_class($user)
	{
		return true;
	}
	function can_change_user_type($user,$target_user)
	{
		return true;
	}
	function can_change_active_status($user,$target_user)
	{
		return true;
	}
	function can_view_profile($user,$target_user)
	{
		return true;
	}
	function can_view_contact_information($user,$target_user)
	{
		return true;
	}
	function can_view_account_information($user,$target_user)
	{
		return true;
	}
	function can_edit_account($user,$target_user)
	{
		return true;
	}
	function can_edit_title($user,$target_user)
	{
		return true;
	}
	function can_edit_aem($user,$target_user)
	{
		return true;
	}
	function can_view_class($user,$target_user)
	{
		return true;
	}
	function can_edit_class($user,$class_id)
	{
		return true;
	}
	function can_delete_class($user,$target_user)
	{
		return true;
	}


	function can_change_class_subscriptions($user,$target_user)
	{
		return true;
	}
	function can_request_password_reset($user,$target_user)
	{
		return true;
	}



	function can_view_professor_list($user)
	{
		return true;
	}
	function can_view_classes_list($user)
	{
		return true;
	}
	function can_view_class_association_types($user)
	{
		return true;
	}
	function can_view_user_associations($user,$target_user)
	{
		return true;
	}
	function can_edit_class_association_types($user)
	{
		return true;
	}
	function can_edit_class_associations($user,$class)
	{
		return true;
	}


	function can_view_announcements($user,$class)
	{
		return true;
	}
	function can_post_announcement($user,$class)
	{
		global $logged_userid;
		return $logged_userid;
	}
	/* edit means can also delete */
	function can_edit_announcement($user,$announcement)
	{
		return true;
	}




	function can_read_folder($user,$folder)
	{
		return true;
	}
	function can_upload_file($user,$folder)
	{
		return true;
	}
	function can_download_file($user,$file)
	{
		return true;
	}
	function can_edit_folder($user,$folder)
	{
		return true;
	}
	function can_delete_folder($user,$folder)
	{
		return true;
	}
	function can_create_folder($user,$class)
	{
		return true;
	}
	/* edit means can also delete */
	function can_edit_file($user,$file)
	{
		return true;
	}




	function can_create_lab($user,$class)
	{
		return true;
	}
	function can_upload_lab_file($user,$lab)
	{
		return true;
	}
	function can_create_lab_team($user,$lab)
	{
		return true;
	}
	function can_view_lab_team($user,$team)
	{
		return true;
	}
	function can_view_lab_teams($user,$lab)
	{
		return true;
	}
	function can_edit_lab_team($user,$team)
	{
		return true;
	}
	function can_delete_lab_team($user,$team)
	{
		return true;
	}
	function can_join_lab_team($user,$team)
	{
		return true;
	}
	function can_edit_lab($user,$lab)
	{
		return true;
	}
	function can_view_lab($user,$lab)
	{
		return true;
	}
	function can_delete_lab($user,$lab)
	{
		return true;
	}
?>
