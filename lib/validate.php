<?php

	include_once('../config/security.php');
	include_once('../lib/localization.php');
	include_once('../lib/connect_db.php');

	/*
		Validates free text.
		returns false on ok,errors on error
	*/
	function freetext_validation($text)
	{
		return false;
	}

	/*
		Validates semester list.
		returns false on ok,errors on error
	*/
	function semester_list_validation($text)
	{
		return false;
	}

	/*
		Validates semester text.
		returns false on ok,errors on error
	*/
	function semester_validation($text)
	{
		if(!is_numeric($text))
			return _('Semester must be numeric.');
		
		if($text <= 0)
			return _('Semester must be positive.');

		return false;
	}
	/*
		Validates email adreess.
		returns false on ok,errors on error
	*/
	function email_validation($email)
	{
		if(!preg_match('~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$~',$email))
			return _('Email is invalid.');

		return false;
	}

	/*
		Validates first/last names and titles .
		returns false on ok,errors on error
	*/
	function name_validation($name)
	{
		global $MAX_NAME_LENGTH,$MIN_NAME_LENGTH;
		if(strlen($name) > $MAX_NAME_LENGTH || strlen($name) < $MIN_NAME_LENGTH)
			return sprintf(_("Names must be between %s and %s characters long"),$MIN_NAME_LENGTH,$MAX_NAME_LENGTH);

		if(!preg_match('~^[a-zA-Z0-9]*$~',$name))
			return _('Names must contain only alphanumeric characters.');

		return false;
	}

	/*
		Validates new usernames.
		returns false on ok,errors on error
	*/
	function new_account_username_validation($username)
	{
		global $MAX_USERNAME_LENGTH,$MIN_USERNAME_LENGTH;
		if(strlen($username) > $MAX_USERNAME_LENGTH || strlen($username) < $MIN_USERNAME_LENGTH)
			return sprintf(_("Username must be between %s and %s characters long"),$MIN_USERNAME_LENGTH,$MAX_USERNAME_LENGTH);
		
		$query = "SELECT username FROM users WHERE username='$username' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))//if it is not avainable
			return _('Username is taken.');

		if(!preg_match('~^[a-zA-Z0-9]*$~',$username))
			return _('Username must contain only alphanumeric characters.');
		
		return false;
	}

	/*
		Validates new AEMs.
		returns false on ok,errors on error
	*/
	function new_account_aem_validation($aem)
	{
		$query = "SELECT aem FROM users WHERE aem='$aem' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
			return _('AEM already exists.');
		
		if(!is_numeric($aem))
			return _('AEM must be numeric.');

		return false;
	}

?>
