<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../config/general.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../lib/log.php");

        if(!isset($error))
                $error = array();

	if(!isset($message))
		$message = array();

	/*Get data from form*/
	$password = isset($_POST['password'])?$_POST['password']:'';
	$password_again = isset($_POST['password_again'])?$_POST['password_again']:'';
	$username = isset($_POST['username'])?$_POST['username']:'';
	$first_name = isset($_POST['first_name'])?$_POST['first_name']:'';
	$last_name = isset($_POST['last_name'])?$_POST['last_name']:'';
	$aem = isset($_POST['aem'])?$_POST['aem']:'';
	$email = isset($_POST['email'])?$_POST['email']:'';
	$semester = isset($_POST['semester'])?$_POST['semester']:'';
	$user_type = isset($_POST['user_type'])?$_POST['user_type']:'';

	if($password == $password_again)
	{
		if(!(($e = name_validation($first_name)) || ($e = name_validation($last_name)) || ($e = new_account_username_validation($username))
		   || ($e = new_account_email_validation($email)) || ($e = new_account_aem_validation($aem)) || ($e = semester_validation($semester))
		   || ($e = password_validation($password)) || ($e = user_type_validation($user_type))))
		{
			$salt = bin2hex(uniqid());
			$password_hash = hash($HASH_ALGORITHM,$password.$salt);//generate hash of salted password	

			/* add user to database */
			$query = "INSERT INTO users 
	(username,password,salt,email,first_name,last_name,aem,user_type,title,registration_time,semester,is_active,semester_update_time)
		 VALUES ('$username','$password_hash','$salt','$email','$first_name','$last_name','$aem','$user_type','1',".time().",'$semester','$DEFAULT_ACCOUNT_ACTIVE_STATE','".time()."')";
			mysql_query($query) || ($error[] = mysql_error());
			if($user = mysql_insert_id())
			{
				$message[] = _('Registration success');
				user_account_creation_log($user);			
			}		
		}
		else
		{
			$error[] = $e;
		}
	}
	else
	{
		$error[] = _('Passwords don\'t match.');
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

		$redirect = ($error)?"register/":"home/";
		include('redirect.php');
	}
?>	
