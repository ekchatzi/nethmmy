<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';
	
	/* Get logged user identification data */
	$user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
	}

	/* Data */
	$titles = isset($_POST['title'])?$_POST['title']:array();
	$priorities = isset($_POST['priority'])?$_POST['priority']:array();
	$permissions = isset($_POST['permissions'])?$_POST['permissions']:array();
	$delete = isset($_POST['delete'])?$_POST['delete']:array();
	$ids = isset($_POST['id'])?$_POST['id']:array();

		
	if(can_edit_class_association_types($logged_userid))
	{
		/* edit */
		for($i=0;$i<count($ids);++$i)
		{
			if(isset($titles[$i]) && isset($permissions[$i]) && isset($priorities[$i]) && isset($ids[$i]))
			{
				$title = $titles[$i];
				$perms = $permissions[$i];
				$priority = $priorities[$i];
				$id = $ids[$i];
				if(!(($e = name_validation($title)) || ($e = association_type_permissions_validation($perms)) || ($e = association_type_priority_validation($priority)) || ($e = association_type_id_validation($id))))
				{
					$query = "UPDATE class_association_types 
							SET title = '$title',
							priority = '$priority',
							permissions = '".mysql_real_escape_string(($perms))."'
							WHERE id= '$id'
							LIMIT 1";
					mysql_query($query) || ($error .= mysql_error());
				}
				else
				{
					$error .= $e;
				}
			}
			else
			{
				$error .= _('Values count do not match id count');
			}	
		}

		/* delete */
		$delete = implode(',',$delete);
		if(!($e = id_list_validation($delete)))
		{
			$query = "DELETE FROM class_association_types WHERE FIND_IN_SET(id,'$delete')";
			mysql_query($query) || ($error .= mysql_error());
		}
		else
		{
			$error . $e;
		}
	}
	else
	{
		$error .= _('Access denied.');
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
		$redirect = ($error)?"edit_class_association_types/":"class_association_types/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
