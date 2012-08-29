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

	$uid = isset($_POST['uid'])?$_POST['uid']:'';
	if(!($e = user_id_validation($uid)))
	{	
		$query = "SELECT * FROM users WHERE id='$uid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
			$email = $result['email'];
			$is_validated = $result['is_email_validated'];
			if (!$is_validated)
			{
				$to = $email;
				$subject = _('[ethmmy] Validate your email');
				$token = get_token('validation',$uid);	
				$link = "$INDEX_ROOT/validate_email.php?token=$token";
				$message_body = sprintf(_("Please follow this link %s to validate your email address."),"<a href='$link'>$link</a>");
				$headers = _('From: ').$NOTIFY_EMAIL_ADDRESS.'\n';
				if(mail($to, $subject, $message_body, $headers))
				{
					$message[] = (_('Email validation email was sent successfully to email address `%s`.'),$email);
				} 
				else 
				{
					$error[] = _("Message delivery failed. Please try again.");
				}
			}
			else
			{
				$error[] = _("Your email is already validated");
			}
		}
	}
	else 
	{
		$error . $e;
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

		$redirect = "profile/$uid/";
		include('redirect.php');
	}
?>
