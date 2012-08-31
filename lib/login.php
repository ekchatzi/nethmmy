<?php
	include_once('../lib/connect_db.php');
	include_once('../config/security.php');
	include_once('../config/general.php');
	include_once("../lib/localization.php");
	include_once('../lib/log.php');

	/*
		Get logged user identification data.
		returns array with 'type' => user_type and 'id' => id , of logged in user,
			false if noone is logged in;
	*/
	function get_logged_user()
	{
		global $USER_TYPES,$INDEX_ROOT;
		$token = isset($_COOKIE['login_token'])?$_COOKIE['login_token']:'';
		if($token)
		{		
			$query= "SELECT id,user_type,last_remote_adress FROM users
				 WHERE login_token = '$token' ";
			$ret = mysql_query($query);
			if( $ret && (mysql_numrows($ret) >= 1))
			{
				$ip = $_SERVER['REMOTE_ADDR'];
				$result = mysql_fetch_array($ret);
				if($ip == $result['last_remote_adress'])
				{
					return array('type' => $USER_TYPES[ $result['user_type'] ] , 'id' => $result['id']);
				}
				else
				{
					setcookie('notify',_('Remote adress has been changed since last login'),time()+3600,$INDEX_ROOT);
				}			
			}
		}
		return false;
	}

	/* 
		Logs out user, by clearing db entry and client stored cookie.
		returns nothing;
	*/
	function logout()
	{
		global $INDEX_ROOT;
		$logged_user = get_logged_user();
		if($logged_user)
		{
			$query= "UPDATE users SET 
				login_token = NULL WHERE id='".$logged_user['id']."' LIMIT 1";
			if($ret = mysql_query($query))
			{
				logout_log($logged_user['id']);
			}
		}
		setcookie('login_token','',time() - 3600,$INDEX_ROOT);
		setcookie('remember','',time() - 3600,$INDEX_ROOT);
	}

	/*
		Logs in user with username and password.
		returns error message on error;
	*/
	function login($username,$password,$remember)
	{
		global $LOGIN_DURATION;
		global $HASH_ALGORITHM;
		$login_duration = $LOGIN_DURATION;
		if(!get_logged_user())
		{
			$query= "SELECT password,salt,id FROM users WHERE username='$username'";
			$ret = mysql_query($query);
			if( $ret && (mysql_numrows($ret) >= 1))
			{
				$dbarray = mysql_fetch_array($ret);
				$uid = $dbarray['id'];

				/* Validate that password is correct */
				if(hash($HASH_ALGORITHM,$password.$dbarray['salt']) == $dbarray['password'])
				{
					/* save the login_token on client and db */
					$ip = $_SERVER['REMOTE_ADDR'];
					$login_token = md5(time().$uid.$ip);
					setcookie("login_token",$login_token,time() + $login_duration);
					if($remember)
						setcookie("remember",1,time() + $login_duration);
					$query = "UPDATE users 
							SET login_token = '$login_token',
								last_login = '".time()."',
								last_remote_adress = '$ip'
							WHERE id = '$uid' LIMIT 1";
					if(mysql_query($query))
					{
						login_log($uid,$ip);
						return false;
					}
					return mysql_error();
				}
				else
				{
					return _('User not found or wrong password.');
				}
			}
			else
			{
				return _('User not found or wrong password.');
			}
		}
		else
		{
			return _('Someone is logged in.');
		}
	}


	/* Get logged user identification data */
	$user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
		setcookie('login_token',$_COOKIE['login_token'],(isset($_COOKIE['remember']) && $_COOKIE['remember'])?(time() + $LOGIN_DURATION):0,$INDEX_ROOT);
	}
	else
	{
		setcookie('login_token','',time() - 3600,$INDEX_ROOT);
		setcookie('remember','',time() - 3600,$INDEX_ROOT);
	}
?>
