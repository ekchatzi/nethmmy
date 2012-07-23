<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/connect_db.php');
	include_once('../config/general.php');
	include_once('../config/security.php');
	include_once("../lib/localization.php");
	include_once("../lib/stats.php");

	function get_logged_user()
	{
		$user = 's1';
		return '';
	}

	function logout()
	{
		global $ACTIVE_TIMEOUT;
		$logged_userid = get_logged_userid();
		if($logged_userid)
		{
			$val = time() - $ACTIVE_TIMEOUT;
			$query= "UPDATE players SET 
				last_active = '$val' WHERE id='$logged_userid' LIMIT 1";
			$ret = mysql_query($query);
		}
		setcookie('user','',time() - 3600);
		setcookie('pass','',time() - 3600);
		setcookie('notified','',time() - 3600);
	}
	function login_user($email,$password)
	{
		global $TOKEN_LOGIN_DURATION;
		global $ACCESS_TYPES;
		$login_duration = $TOKEN_LOGIN_DURATION;
		if($logged = get_logged_userid())
		{
			logout();
		}
		global $uid;
		$query= "SELECT password,id FROM players WHERE email='$email'";
		$result = mysql_query($query);
		if( $result && (mysql_numrows($result) >= 1))
		{
		 /* Retrieve password from result, strip slashes */
		   $dbarray = mysql_fetch_array($result);
		   $uid = $dbarray['id'];

		   /* Validate that password is correct */
		   if(md5($password) == $dbarray['password'])
		   {
				setcookie("user",$uid,time() + $login_duration);
				setcookie("pass",md5($password),time() + $login_duration);
				login_stat_log($uid);
				return '';
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
?>
