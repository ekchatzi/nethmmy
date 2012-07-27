<?php

	include_once('../config/security.php');
	include_once('../lib/localization.php');
	include_once('../lib/connect_db.php');

	/*
		Sanitizes html text.
		returns sanitized html text
	*/
	function sanitize_html($html)
	{
		global $ALLOWED_HTML_TAGS,$ALLOWED_HTML_ATTRIBUTES;

		$safe_tags = $ALLOWED_HTML_TAGS;
		$safe_attributes = $ALLOWED_HTML_ATTRIBUTES;
		$url_attributes = array( 1 =>'href','src');

		/* strip collector */
		$strip_arr = array();

		/* load XHTML with SimpleXML */
		$data_sxml = simplexml_load_string('<root>'. $html .'</root>', 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOXMLDECL);

		if($data_sxml) 
		{
			/* loop all elements with an attribute */
			foreach($data_sxml->xpath('descendant::*[@*]') as $tag) 
			{
				/* loop attributes */
				foreach($tag->attributes() as $name=>$value) 
				{
					print_r($url_attributes);
					echo('  '.$tag->attributes()->$name);
					/* check for allowable attributes */
					if(!in_array($name, $safe_attributes)) 
					{
						// set attribute value to empty string
						$tag->attributes()->$name = '';
						// collect attribute patterns to be stripped
						$strip_arr[$name] = '/ '. $name .'=""/';
					}/* Check for unsafe url attributes */
					elseif(in_array($name, $url_attributes) && substr($tag->attributes()->$name, 0, 7) !== 'http://' && substr($tag->attributes()->$name, 0, 8) !== 'https://')
					{
						// set attribute value to empty string
						$tag->attributes()->$name = '';
					}
				}
		    	}
		}

		// strip unallowed attributes and root tag
		return strip_tags(preg_replace($strip_arr,array(''),$data_sxml->asXML()), $safe_tags);
	}

	/*
		Validates semester list.
		returns false on ok,errors on error
	*/
	function semester_list_validation($text)
	{
		$semesters = explode(',',$text);
		for($i = 0; $i < count($semesters); ++$i)
		{
			if(!is_numeric($semesters[$i]))
				return _("Invalid semester list.");		
		}
		return false;
	}

	/*
		Validates semester text.
		returns false on ok,errors on error
	*/
	function semester_validation($text)
	{
		/* Must be a number */
		if(!is_numeric($text))
			return _('Semester must be numeric.');
		
		/* Must be positive */
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
		/* Must be in email format */
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
		/* Must be apropiate size */ 
		if(strlen($name) > $MAX_NAME_LENGTH || strlen($name) < $MIN_NAME_LENGTH)
			return sprintf(_("Names must be between %s and %s characters long"),$MIN_NAME_LENGTH,$MAX_NAME_LENGTH);

		/* Must contain only alphanumeric characters */
		if(!preg_match('~^[a-zA-Z0-9 ]*$~',$name))
			return _('Names must contain only alphanumeric characters and whitespace.');

		return false;
	}

	/*
		Validates new usernames.
		returns false on ok,errors on error
	*/
	function new_account_username_validation($username)
	{
		global $MAX_USERNAME_LENGTH,$MIN_USERNAME_LENGTH;
		/* Must be apropiate size */
		if(strlen($username) > $MAX_USERNAME_LENGTH || strlen($username) < $MIN_USERNAME_LENGTH)
			return sprintf(_("Username must be between %s and %s characters long"),$MIN_USERNAME_LENGTH,$MAX_USERNAME_LENGTH);
		
		/* Must be unique */
		$query = "SELECT username FROM users WHERE username='$username' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
			return _('Username is taken.');

		/* Must contain only alphanumeric characters */
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
		/* Must not already exist on database */
		$query = "SELECT aem FROM users WHERE aem='$aem' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
			return _('AEM already exists.');
		
		/* Must be numeric */
		if(!is_numeric($aem))
			return _('AEM must be numeric.');

		return false;
	}

	/*
		Validates passwords.
		returns false on ok,errors on error
	*/
	function password_validation($password)
	{
		global $MIN_PASSWORD_LENGTH,$MAX_PASSWORD_LENGTH;
		/* Must be between set sizes */
		if(strlen($password) > $MAX_PASSWORD_LENGTH || strlen($password) < $MIN_PASSWORD_LENGTH)
			return sprintf(_("Password must be between %s and %s characters long"),$MIN_PASSWORD_LENGTH,$MAX_PASSWORD_LENGTH);

		/* Must contain at least 1 letter and 1 number */
		if(!preg_match('~[A-Za-z].*[0-9]|[0-9].*[A-Za-z]~',$password))
			return _("Password must contain letters AND numbers.");

		return false;
	}

?>
