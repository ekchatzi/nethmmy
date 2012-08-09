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

		$sane = _('Invalid markup');
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
			$sane = strip_tags(preg_replace($strip_arr,array(''),$data_sxml->asXML()), $safe_tags);
		}

		// strip unallowed attributes and root tag
		return $sane;
	}

	/*
		Validates xml.
		returns false on ok,errors on error
	*/	
	function xml_validation($xml)
	{
		$data_sxml = simplexml_load_string('<root>'. $xml .'</root>', 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOXMLDECL);
		if(!$data_sxml)
			return _('Invalid markup');
		return false;
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
		if(isset($text) && strlen($text) > 0)
		{
			/* Must be a number */
			if(!is_numeric($text) || $text <= 0)
				return _('Semester must be positive integer.');
		}
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
		if(!preg_match('~^[\p{L}\d ]*$~u',$name))
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
			return _('Username must contain only latin alphanumeric characters.');
		
		return false;
	}

	/*
		Validates new AEMs.
		returns false on ok,errors on error
	*/
	function new_account_aem_validation($aem)
	{
		if(isset($aem) && strlen($aem) > 0)
		{
			/* Must not already exist on database */
			$query = "SELECT aem FROM users WHERE aem='$aem' LIMIT 1";
			$ret = mysql_query($query);
			if($ret && mysql_numrows($ret))
				return _('AEM already exists.');
		
			/* Must be numeric */
			if(!is_numeric($aem) || $aem <= 0) 
				return _('AEM must be positive integer.');
		}
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

	/*
		Validates user types.
		returns false on ok,errors on error
	*/
	function user_type_validation($type)
	{
		global $USER_TYPES;

		if(!isset($USER_TYPES[$type]))
			return _('Invalid user type.');
		
		return false;
	}
	
	/*
		Validates user ids.
		returns false on ok,errors on error
	*/
	function user_id_validation($id)
	{
		if(!is_numeric($id) || $id <=0)
			return _('User ids must be positive integers.');

		$query = "SELECT COUNT(*) FROM users WHERE id='$id'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			if($count < 1)
				return _('User id does not exist.');
		}
		else
			return _("Database Error.");

		return false;
	}

	/*
		Validates class ids.
		returns false on ok,errors on error
	*/
	function class_id_validation($id)
	{
		if(!is_numeric($id) || $id <=0)
			return _('Class ids must be positive integers.');

		$query = "SELECT COUNT(*) FROM classes WHERE id='$id'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			if($count < 1)
				return _('Class id does not exist.');
		}
		else
			return _("Database Error.");

		return false;
	}
	/*
		Validate title ids.
		returns false on ok, errors on error
	*/
	function title_id_validation($id)
	{
		if(!is_numeric($id) || $id <=0)
			return _('Title ids must be positive integers.');

		$query = "SELECT COUNT(*) FROM titles WHERE id='$id'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			if($count < 1)
				return _('Title id does not exist.');
		}
		else
			return _("Database Error.");

		return false;		
	}

	/* 
		Validates telephone numbers.
		returns false on ok, errors on error
	*/
	function telephone_validation($tel)
	{
		global $MIN_TELEPHONE_NUMBER_LENGTH,$MAX_TELEPHONE_NUMBER_LENGTH;
		if(!((strlen($tel) == 0) || (strlen($tel) >= $MIN_TELEPHONE_NUMBER_LENGTH) && (strlen($tel) <= $MAX_TELEPHONE_NUMBER_LENGTH)))
			return sprintf(_("Telephone numbers must be between %s and %s characters long or empty."),$MIN_TELEPHONE_NUMBER_LENGTH,$MAX_TELEPHONE_NUMBER_LENGTH);

		if(!preg_match('~^\+?[0-9]*$~',$tel))
			return _('Invalid characters for telephone number.');			
		
		return false;
	}

	/* 
		Validates websites.
		returns false on ok, errors on error
	*/
	function website_validation($url)
	{
		return false;
	}

	/* 
		Validates association type priority.
		returns false on ok, errors on error
	*/
	function association_type_priority_validation($priority)
	{
		/* Must be a number */
		if(!is_numeric($priority) || $priority <= 0)
			return _('Priority must be positive integer.');
		return false;
	}


	/* 
		Validates id lists in comma seperated values.
		returns false on ok, errors on error
	*/
	function id_list_validation($text)
	{
		/* Must be a number */
		$ids = explode(',',$text);
		for($i = 0; $i < count($ids); ++$i)
		{
			if(!is_numeric($ids[$i]))
				return _("Invalid id list.");		
		}

		return false;
	}

	/* 
		Validates association type id.
		returns false on ok, errors on error
	*/
	function association_type_id_validation($id)
	{
		/* Must be a number */
		if(!is_numeric($id) || $id <= 0)
			return _('Association type ids must be positive integers.');

		$query = "SELECT COUNT(*) FROM class_association_types WHERE id='$id'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			if($count < 1)
				return _('Association type id does not exist.');
		}
		else
			return _("Database Error.");

		return false;
	}

	/* 
		Validates association ids.
		returns false on ok, errors on error
	*/
	function association_id_validation($id)
	{
		/* Must be a number */
		if(!is_numeric($id) || $id <= 0)
			return _('Association ids must be positive integers.');

		$query = "SELECT COUNT(*) FROM class_associations WHERE id='$id'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$count = mysql_result($ret,0,0);
			if($count < 1)
				return _('Association id does not exist.');
		}
		else
			return _("Database Error.");

		return false;
	}
	/* 
		Validates association type permission comma seperated lists.
		returns false on ok, errors on error
	*/
	function association_type_permissions_validation($permissions)
	{
		global $CLASS_PERMISSIONS;
		$permissions = explode(',',$permissions);
		for($i=0;$i<count($permissions);++$i)
		{
			$per = $permissions[$i];
			if(!in_array($per,$CLASS_PERMISSIONS))
				return sprintf(_('Invalid permission "%s".'),$per);
		}
		return false;
	}
?>
