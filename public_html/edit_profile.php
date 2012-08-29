<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

        if(!isset($error))
                $error = array();

	if(!isset($message))
		$message = array();

	/*Get data from form*/
	$first_name = isset($_POST['first_name'])?$_POST['first_name']:'';
	$last_name = isset($_POST['last_name'])?$_POST['last_name']:'';
	$email = isset($_POST['email'])?$_POST['email']:'';
	$semester = isset($_POST['semester'])?$_POST['semester']:'';
	$website = isset($_POST['website'])?$_POST['website']:'';
	$telephone = isset($_POST['telephone'])?$_POST['telephone']:'';
	$title = isset($_POST['title'])?$_POST['title']:'';
	$bio =  isset($_POST['bio'])?$_POST['bio']:'';
	$uid =  isset($_POST['uid'])?$_POST['uid']:'0';
	$email_urgent = isset($_POST['send_urgent'])?$_POST['send_urgent']:'0';
	/* check input */
	if(!(($e = name_validation($first_name)) || ($e = name_validation($last_name))
	   || ($e = email_validation($email)) || ($e = website_validation($website))
	   || ($e = telephone_validation($telephone)) || ($e = xml_validation($bio))
	   || ($e = user_id_validation($uid)) || ($e = title_id_validation($title))
	   || ($e = semester_validation($semester))))
	{
		if(can_edit_account($logged_userid,$uid))
		{
			/*check email validation*/
			$query = "SELECT email, is_email_validated FROM users WHERE id = '$uid' LIMIT 1";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				if($result['email'] != mysql_real_escape_string($email) && $result['is_email_validated']==1)
				{	
					$query = "UPDATE users SET
							is_email_validated='0'
							WHERE id='$uid' LIMIT 1";
					mysql_query($query) || ($error[] = mysql_error());
					$message[] = _('Email address is no longer validated.');
				}
			}
			
			/* basic info */
			$query = "UPDATE users SET
					email='".mysql_real_escape_string($email)."',
					first_name='".mysql_real_escape_string($first_name)."',
					last_name='".mysql_real_escape_string($last_name)."',".
					((strlen($title) && can_edit_title($logged_userid,$uid))?"title='$title',":'').
					"bio='".mysql_real_escape_string(sanitize_html($bio))."',
					website='".mysql_real_escape_string($website)."',
					telephone='".mysql_real_escape_string($telephone)."',
					semester='$semester',
					semester_update_time='".time()."',
					email_urgent='$email_urgent'
					WHERE id='$uid' LIMIT 1";
			mysql_query($query) || ($error[] = mysql_error());
			$message[] = _('Basic information were updated successfully.');

			/* aem */
			if(isset($_POST['aem']))
			{
				if(can_edit_aem($logged_userid,$uid))
				{
					$aem = $_POST['aem'];
					$query = "SELECT aem FROM users WHERE id='$uid'";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						$old_aem = mysql_result($ret,0,0);
						if($old_aem != $aem && !($e = new_account_aem_validation($aem)))
						{
							$query = "UPDATE users SET
								aem='$aem'
								WHERE id='$uid'";
							mysql_query($query) || ($error[] = mysql_error());
							$message[] = _('AEM was updated successfully.');
						}
						else
						{
							$error[] = $e;
						}
					}
				}
				else
				{
					$error[] = _('Access denied.');
				}
			}
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
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = ($error)?"edit_profile/$uid/":"profile/$uid/";
		include('redirect.php');
	}
?>	
