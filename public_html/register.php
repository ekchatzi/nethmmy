<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");

        if(!isset($error))
                $error = '';

	/*Get data from form*/
	$password = $_POST['password'];
	$password_again = $_POST['password_again'];
	$username = addslashes(substr($_POST['nickname'],0,$MAX_USERNAME_LENGTH));
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$aem = $POST['aem'];
	$email = $_POST['email'];
/*
	include_once('../lib/recaptcha.php');
	include_once('../config/recaptcha.php');
	$resp = recaptcha_check_answer ($recaptcha_privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
	if (!$recaptcha_enabled || $resp->is_valid) 
	{
*/
	if($password == $password_again)
	{
		/*Regular expression check on email validity*/
		$is_valid =preg_match('~^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$~i',$email);
		if($is_valid)
		{
			/*checking avainability by looking in db for users with
			the same username*/
			$query = "SELECT username FROM users WHERE username='$username' LIMIT 1";
			$ret = mysql_query($query);
			if(!($ret && mysql_numrows($ret)))//if it is avainable
			{
				$query = "SELECT aem FROM users WHERE aem='$aem' LIMIT 1";
				$ret = mysql_query($query);
				if(!($ret && mysql_numrows($ret)))//if it is avainable
				{
					$salt = mcrypt_create_iv(32);
					$password_hash = hash($HASH_ALGORITHM,$password.$salt);//generate hash of salted password	

					/* add user to database */
					$query = "INSERT INTO users 
					(username,password,salt,email,first_name,last_name,aem,user_type,title,registration_time,is_active)
				 VALUES ('$username','$password_hash','$salt','$email','$first_name','$last_name','$aem','1','1',".time().",'1')";
					mysql_query($query) || ($error .= mysql_error());
				}
				else
				{
					$error .= _('AEM exists.');
				}
			}
			else
			{
				$error .= _('Username is taken.');
			}
		}
		else
		{
			$error .= _('Email is invalid.');
		}
	}
	else
	{
		$error .= _('Passwords don\'t match.');
	}
/*
	}
	else
	{
		$error .= _('Wrong CAPTCHA!');
	}
*/
	if(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = ($error)?"index.php?v=register":"index.php?v=login";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
?>	
