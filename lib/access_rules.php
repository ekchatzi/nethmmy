<?php
	include_once('../config/security.php');
	
	function can_create_class($user)
	{
		return true;
	}
	function can_create_professor_account($user)
	{
		return true;
	}
	function can_create_admin_account($user)
	{
		return true;
	}
	function can_activate_account($user,$target_user)
	{
		return true;
	}
	function can_deactivate_account($user,$target_user)
	{
		return true;
	}
	function can_view_profile($user,$target_user)
	{
		return true;
	}
	function can_view_class($user,$target_user)
	{
		return true;
	}
	function can_delete_class($user,$target_user)
	{
		return true;
	}
	function can_post_announcement($user,$class)
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
	function can_create_lab($user,$class)
	{
		return true;
	}
	function can_delete_lab($user,$lab)
	{
		return true;
	}
	function can_create_lab_team($user,$lab)
	{
		return true;
	}
	function can_delete_lab_team($user,$lab)
	{
		return true;
	}
	function can_edit_lab_team($user,$lab)
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
?>
