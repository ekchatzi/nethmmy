<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../config/security.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	/* Data */
	$title = isset($_POST['title'])?$_POST['title']:'';
	$description = isset($_POST['description'])?$_POST['description']:'';
	$lab = isset($_POST['lab'])?$_POST['lab']:0;
	$team_limit = isset($_POST['team_limit'])?$_POST['team_limit']:0;
	$users_per_team_limit = isset($_POST['users_per_team_limit'])?$_POST['users_per_team_limit']:0;
	$registration_expire = isset($_POST['register_expire'])?$_POST['register_expire']:0;
	$upload_limit = isset($_POST['upload_limit'])?$_POST['upload_limit']:0;
	$upload_expire = isset($_POST['upload_expire'])?$_POST['upload_expire']:time();
	$can_free_join =  isset($_POST['can_free_join'])?$_POST['can_free_join']:0;
	$can_make_new_teams =  isset($_POST['can_make_new_teams'])?$_POST['can_make_new_teams']:0;
	$can_lock_teams =  isset($_POST['can_lock_teams'])?$_POST['can_lock_teams']:0;
	$can_upload =  isset($_POST['can_upload'])?$_POST['can_upload']:0;
	/* check if input is valid */
	if(!(($e = name_validation($title)) || ($e = lab_id_validation($lab)) || ($e = xml_validation($description))
	   ||($e = lab_team_limit_validation($team_limit)) || ($e = lab_team_size_limit_validation($users_per_team_limit))
	   ||($e = lab_upload_limit_validation($upload_limit)) || ($e = deadline_validation($registration_expire))
	   ||($e = deadline_validation($upload_expire)) || ($e = boolean_int_validation($can_free_join))
	   ||($e = boolean_int_validation($can_make_new_teams)) || ($e = boolean_int_validation($can_lock_teams))))
	{
		if(can_edit_lab($logged_userid,$lab))
		{
			//make folder if needed
			$folder = 0;
			if($can_upload)
			{
				$query = "SELECT folder,class FROM labs WHERE id='$lab'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$result = mysql_fetch_array($ret);
					$folder = $result['folder'];
					$class = $result['class'];
				}
				if($folder)
				{
					$query = "SELECT COUNT(*) FROM file_folders WHERE id='$folder'";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret) && mysql_result($ret,0,0) == 0)
					{
						$query = "INSERT INTO file_folders (id,name,class,public)
							VALUES('$folder','".mysql_real_escape_string($title)."','$class','0')";
						mysql_query($query) || ($error[] = mysql_error());
						$message[] = _('File folder was created successfully.');
					}
				}
				else
				{
					$query = "INSERT INTO file_folders (name,class,public)
						VALUES('".mysql_real_escape_string($title)."','$class','0')";
					mysql_query($query) || ($error[] = mysql_error());
					$folder = mysql_insert_id();
					$message[]  = _('File folder was created successfully.');
				}
			}
			$time = time();
			$query = "UPDATE labs SET  
					title = '".mysql_real_escape_string($title)."',
					description = '".mysql_real_escape_string(sanitize_html($description))."',
					team_limit = '$team_limit',users_per_team_limit = '$users_per_team_limit',
					register_expire = '$registration_expire',
					upload_limit = '$upload_limit',upload_expire = '$upload_expire',
					can_free_join = '$can_free_join',can_make_new_teams = '$can_make_new_teams',
					can_lock_teams = '$can_lock_teams',folder = '$folder',
					update_time = '$time'
					WHERE id='$lab' LIMIT 1";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _("Lab information were updated successfully.");
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

		$redirect = ($error)?"edit_lab/$lab/":"lab/$lab/";
		include('redirect.php');
	}
	
?>
