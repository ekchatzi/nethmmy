<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../public_html/validate_email.php");
	include_once("../config/general.php");
	
	if(!isset($error)) 
                $error = '';
				
	$uid = isset($_POST['uid'])?$_POST['uid']:'';
	if(!($e=user_id_validation($uid)))
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
				$subject = '[ethmmy] Validate your email';
				
				//Work to be done		
				$message = '<placeholder>';

				$headers = 'From: '.$NOTIFY_EMAIL_ADDRESS.'\n';
				if(mail($to, $subject, $message, $headers))
				{
					echo false;
				} 
				else 
				{
					$error .= _("Message delivery failed. Please try again.");
				}
			}
			else
			{
				$error .= _("Your email is already valid");
			}
		}
	}
	else 
	{
		$error . $e;
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
		$redirect = ($folder)?"files/$folder/":"home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>