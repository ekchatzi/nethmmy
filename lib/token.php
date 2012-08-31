<?php
	include_once('../config/security.php');

	function get_token($context,$data)
	{
		global $ALLOWED_TOKEN_CONTEXTS;
		if(in_array($context,$ALLOWED_TOKEN_CONTEXTS))
		{
			//check for duplicate token
			$query = "SELECT code FROM tokens WHERE context='$context' AND data='$data'";
			$ret = mysql_query($query);
			if($ret && mysql_numrows($ret))//if it exists
			{//return the old one
				return mysql_result($ret,0);
			}
			else
			{//create new token
				$token = uniqid();	
				$query = "INSERT INTO tokens (code,context,data)
					  VALUES ('$token','$context','$data')";
				$ret = mysql_query($query);
				if($ret)
					return $token;
			}
		}
		return false;
	}
	function verify_token($token,$context)
	{
		$query = "SELECT data FROM tokens WHERE code='$token' AND context='$context'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			return true;
		}		
		return false; 
	}
	function delete_token($token)
	{
		$query = "DELETE FROM tokens WHERE code='$token'";
		$ret = mysql_query($query);
		return $ret;
	}
?>
