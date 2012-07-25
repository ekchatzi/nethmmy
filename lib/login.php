<?php
	include_once('../lib/connect_db.php');
	include_once('../config/security.php');
	include_once("../lib/localization.php");

	$USER_TYPES = array(0 => 'g', 1 => 's', 2 => 'p', 3 => 'a');

	/*
		Get logged user identification data.
		returns array with 'type' => user_type and 'id' => id , of logged in user,
			false if noone is logged in;
	*/
	function get_logged_user()
	{
		global $USER_TYPES;
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
					setcookie('notify',_('Remote adress has been changed since last login'),time()+3600);
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
		$logged_user = get_logged_user();
		if($logged_user)
		{
			$query= "UPDATE users SET 
				login_token = NULL WHERE id='".$logged_user['id']."' LIMIT 1";
			$ret = mysql_query($query);
		}
		setcookie('login_token','',time() - 3600);
	}

	/*
		Logs in user with username and password.
		returns error message on error;
	*/
	function login($username,$password)
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
					$query = "UPDATE users 
							SET login_token = '$login_token',
								last_login = '".time()."',
								last_remote_adress = '$ip'
							WHERE id = '$uid' LIMIT 1";
					mysql_query($query);
					return mysql_error();//return mysql error if any;
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
?>
