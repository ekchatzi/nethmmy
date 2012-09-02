<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");
	include_once("../lib/token.php");

	if(!isset($error)) 
                $error = array();
				
	if(!isset($message))
		$message = array();

	$email = isset($_POST['email'])?$_POST['email']:'';
	if(!($e = email_validation($email)))
	{	
		$query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
			$uid = $result['id'];
			$is_validated = $result['is_email_validated'];
			if ($is_validated)
			{
				$to = $email;
				$subject = _('[ethmmy] Validate your email');
				$token = get_token('password_reset',$uid);	
				$link = "$INDEX_ROOT/change_password/$token/";
				$message_body = sprintf(_("Please follow this link %s to change your password, or you can paste the following code to the code input field in this page: %s .\n Code: %s"),"<a href='$link'>$link</a>","<a href='$INDEX_ROOT/change_password/'>$INDEX_ROOT/change_password/</a>","$token");
				$headers = 'From: '.$NOTIFY_EMAIL_ADDRESS.'\n';
				if(mail($to, $subject, $message_body, $headers))
				{
					$message[] = sprintf(_('Email for password reset was sent successfully to email address `%s`.'),$email);
				} 
				else 
				{
					$error[] = _("Message delivery failed. Please try again.");
				}
			}
			else
			{
				$error[] = _("Your email is not validated");
			}
		}
		else
		{
			$error[] = _("Email was not found in our database");
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

		$redirect = "profile/$uid/";
		include('redirect.php');
	}
?>
