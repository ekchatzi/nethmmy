<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

        if(!isset($error))
                $error = '';

	/*Get data from form*/
	$password = isset($_POST['password'])?$_POST['password']:'';
	$password_again = isset($_POST['password_again'])?$_POST['password_again']:'';
	$username = isset($_POST['username'])?$_POST['username']:'';
	$first_name = isset($_POST['first_name'])?$_POST['first_name']:'';
	$last_name = isset($_POST['last_name'])?$_POST['last_name']:'';
	$aem = isset($_POST['aem'])?$_POST['aem']:'';
	$email = isset($_POST['email'])?$_POST['email']:'';
	$semester = isset($_POST['semester'])?$_POST['semester']:'';

	if($password == $password_again)
	{
		if(!(($e = name_validation($first_name)) || ($e = name_validation($last_name)) || ($e = new_account_username_validation($username))
		   || ($e = email_validation($email)) || ($e = new_account_aem_validation($aem) || ($e = semester_validation($semester)))))
		{
			$salt = bin2hex(mcrypt_create_iv(32));
			$password_hash = hash($HASH_ALGORITHM,$password.$salt);//generate hash of salted password	

			/* add user to database */
			$query = "INSERT INTO users 
			(username,password,salt,email,first_name,last_name,aem,user_type,title,registration_time,registration_semester,is_active)
		 VALUES ('$username','$password_hash','$salt','$email','$first_name','$last_name','$aem','1','1',".time().",'$semester','1')";
			mysql_query($query) || ($error .= mysql_error());
		}
		else
		{
			$error .= $e;
		}
	}
	else
	{
		$error .= _('Passwords don\'t match.');
	}


	if(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(!isset($message))
			$message = '';
		//Hide warnings
		$warning = '';
		$redirect = ($error)?"index.php?v=register":"index.php?v=home";
		if(strlen($error))
			setcookie('notify',$error,time()+3600);
		include('redirect.php');
	}
?>	
