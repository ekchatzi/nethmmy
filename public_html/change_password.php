<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/token.php");
	include_once("../config/security.php");
	include_once("../lib/localization.php"); 

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$uid = 0;
	$token = isset($_POST['token'])?$_POST['token']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';
	$password_again = isset($_POST['password_again'])?$_POST['password_again']:'';
	if(verify_token($token,'change_password'))//if token is valid
	{
		if($password == $password_again)
		{
			if(!($e = password_validation($password)))
			{
				$query = "SELECT data FROM tokens WHERE code='$token'";
				($ret = mysql_query($query)) || ($error = $query . "||" .mysql_error());
				if($ret && mysql_numrows($ret))
				{
					$uid = mysql_result($ret,0,0);//data == uid
					$salt = bin2hex(mcrypt_create_iv(32));
					$password_hash = hash($HASH_ALGORITHM,$password.$salt);//generate hash of salted password
					$query = "UPDATE users SET password = '$password_hash',
								salt='$salt'
							WHERE id='$uid' LIMIT 1";
					mysql_query($query) || ($error[] = mysql_error());
					delete_token($token);
					$message[] = _('Password was changed successfully.');
				}
				else
				{
					$error[] = _('Token is no longer avainable.');
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
	}
	else
	{
		$error[] = _('Token is invalid.');
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.implode($MESSAGE_SEPERATOR,$error).'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "home/";
		include('redirect.php');
	}
?>
