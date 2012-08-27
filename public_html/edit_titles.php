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
	$titles = isset($_POST['title'])?$_POST['title']:array();
	$descriptions = isset($_POST['description'])?$_POST['description']:array();
	$delete = isset($_POST['delete'])?$_POST['delete']:array();
	$ids = isset($_POST['id'])?$_POST['id']:array();
	if(can_edit_titles($logged_userid))
	{
		/* edit */
		for($i=0;$i<count($ids);++$i)
		{
			if(isset($titles[$i]) && isset($descriptions[$i]) && isset($ids[$i]))
			{
				$title = $titles[$i];
				$desc = $descriptions[$i];
				$id = $ids[$i];
				if(!(($e = name_validation($title)) || ($e = xml_validation($desc))))
				{
					$query = "UPDATE titles
							SET title = '$title',
							description = '".mysql_real_escape_string(sanitize_html($desc))."'
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
			$query = "DELETE FROM titles WHERE FIND_IN_SET(id,'$delete')";;
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
		$redirect = "edit_titles/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>