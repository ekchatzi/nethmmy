<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

        if(!isset($error))
                $error = '';

	/*Get user type*/
	$user_type = isset($_POST['user_type'])?$_POST['user_type']:false;
	$uid = isset($_POST['uid'])?$_POST['uid']:0;

	/* Get logged user identification data */
	$logged_user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$logged_user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
	}
	/* User type */
	if(!(($e = user_type_validation($user_type)) || ($uid_is_valid = $e = user_id_validation($uid))))
	{
		if(can_change_user_type($logged_userid,$uid,$user_type))
		{
			/* update database */
			$query = "UPDATE users 
					SET user_type = '$user_type'
					WHERE id='$uid' LIMIT 1";
			mysql_query($query) || ($error .= mysql_error());
		}
		else
		{
			$error .= _('Access denied.');
		}
	}
	else
	{
		$error .= $e;
	}

	/* Activate/deactivate */
	$activate = isset($_POST['active_status'])?$_POST['active_status']:'-1';
	if($uid_is_valid)
	{
		if($activate == '0' || $activate == '1')
		{
			if(can_change_active_status($logged_userid,$uid))
			{
				/* update database */
				$query = "UPDATE users 
						SET is_active = '$activate'
						WHERE id='$uid' LIMIT 1";
				mysql_query($query) || ($error .= mysql_error());
			}
			else
			{
				$error .= _('Access denied.');
			}
		}
		elseif($activate !== '-1')
		{
			$error .= _('Invalid active status.');
		}
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
		$redirect = "index.php?v=profile&id=$uid";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
?>	
