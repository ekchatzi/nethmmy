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
	$titles = isset($_POST['title'])?$_POST['title']:array();
	$priorities = isset($_POST['priority'])?$_POST['priority']:array();
	$permissions = isset($_POST['permissions'])?$_POST['permissions']:array();
	$delete = isset($_POST['delete'])?$_POST['delete']:array();
	$ids = isset($_POST['id'])?$_POST['id']:array();

		
	if(can_edit_class_association_types($logged_userid))
	{
		/* edit */
		$updated = 0;
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
					mysql_query($query) || ($error[] = mysql_error());
					$updated++;
				}
				else
				{
					$error[] = $e;
				}
			}
			else
			{
				$error[] = _('Values count do not match id count');
				break;
			}

		}
		if($updated) 
			$message[] = sprintf(_("%s out of %s class associations were updated successfully."),$updated,count($ids)); 	
		/* delete */
		$delete = implode(',',$delete);
		if(!($e = id_list_validation($delete)))
		{
			$query = "DELETE FROM class_association_types WHERE FIND_IN_SET(id,'$delete')";
			mysql_query($query) || ($error[] = mysql_error());
		}
		else
		{
			$error . $e;
		}
	}
	else
	{
		$error[] = _('Access denied.');
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

		$redirect = "edit_class_association_types/";
		include('redirect.php');
	}
	
?>
