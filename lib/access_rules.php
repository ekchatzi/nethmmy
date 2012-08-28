<?php
	include_once('../config/security.php');
	include_once('../lib/login.php');
	
	/* HELPER FUNCTIONS */
	/*
		0 => 'g' for guest
		1 => 's' for student
		2 => 'p' for professor
		3 => 'a' for admin
	*/
	function user_type($user)
	{
		global $USER_TYPES;
		$i = 0;
		$query = "SELECT user_type FROM users WHERE id='".mysql_real_escape_string($user)."'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
			$i = mysql_result($ret,0,0);
		return $USER_TYPES[$i];
	}
	/*
		true if account is activated
		false if account is not activated
	*/
	function is_active($user)
	{
		$query = "SELECT is_active FROM users WHERE id='".mysql_real_escape_string($user)."'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
			return (bool)mysql_result($ret,0,0);
		return false;	
	}

	/* RULES */
	function can_create_class($user)
	{
		return is_active($user) && (user_type($user) == 'a');
	}
	function can_change_user_type($user,$target_user)
	{
		return is_active($user) && (user_type($user) == 'a');
	}
	function can_change_active_status($user,$target_user)
	{
		return is_active($user) && (user_type($user) == 'a');
	}
	function can_view_profile($user,$target_user)
	{
		return is_active($user) || (($user == $target_user) && user_type($target_user) != 'g');
	}
	function can_view_contact_information($user,$target_user)
	{
		return (is_active($user) && ((user_type($user) == 'p') || (user_type($user) == 'a'))) || (($user == $target_user) && (user_type($target_user) != 'g'));
	}
	function can_view_account_information($user,$target_user)
	{
		return (is_active($user) && (user_type($user) == 'a')) || (($user == $target_user) && (user_type($target_user) != 'g'));
	}
	function can_edit_account($user,$target_user)
	{
		return (is_active($user) && (user_type($user) == 'a')) || (($user == $target_user) && (user_type($target_user) != 'g'));
	}
	function can_delete_account($user,$target_user)
	{
		return (is_active($user) && (user_type($user) == 'a'));
	}
	function can_edit_title($user,$target_user)
	{
		return is_active($user) && (user_type($user) == 'a');
	}
	function can_edit_aem($user,$target_user)
	{
		return (is_active($user) && (user_type($user) == 'a')) || (($user == $target_user) && (user_type($target_user) != 'g'));
	}
	function can_view_class($user,$class)
	{
		return (is_active($user) && (user_type($user) != 'g'));
	}
	function can_view_class_directories($user, $class)
	{
		return (is_active($user) && (user_type($user) != 'g'));
	}
	function can_edit_class($user,$class)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
					WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$permissions = mysql_result($ret,0,0);
				if(preg_match('~\bmanage_info\b~',$permissions))
					return true;
			}
		}
		return false;
	}
	function can_delete_class($user,$target_user)
	{
		return (is_active($user) && (user_type($user) == 'a'));
	}


	function can_change_class_subscriptions($user,$target_user)
	{
		return ($user == $target_user) && (is_active($target_user) && (user_type($target_user) != 'g'));
	}
	function can_request_password_reset($user,$target_user)
	{
		return ($user == $target_user) && (is_active($target_user))  && (user_type($target_user) != 'g');
	}



	function can_view_professor_list($user)
	{
		return true;
	}
	function can_view_classes_list($user)
	{
		return true;
	}
	function can_view_user_associations($user,$target_user)
	{
		return true;
	}
	function can_edit_class_association_types($user)
	{
		return (is_active($user) && (user_type($user) == 'a'));
	}
	function can_edit_class_associations($user,$class)
	{
		return (is_active($user) && (user_type($user) == 'a'));
	}
	function can_edit_titles($user)
	{
		return (is_active($user) && (user_type($user) == 'a'));
	}

	function can_view_announcements($user,$class)
	{
		return (is_active($user) && (user_type($user) != 'g'));
	}
	function can_post_announcement($user,$class)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
					WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$permissions = mysql_result($ret,0,0);
				if(preg_match('~\announce\b~',$permissions))
					return true;
			}
		}
		return false;
	}
	/* edit means can also delete */
	function can_edit_announcement($user,$announcement)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class,poster FROM announcements WHERE id='$announcement'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$poster = $result['poster'];
				if($poster == $user)
					return true;

				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\manage_announcements\b~',$permissions))
						return true;
				}
			}

		}
		return false;
	}




	function can_read_folder($user,$folder)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class,public FROM file_folders WHERE id='$folder'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$public = $result['public'];
				if($public)
					return true;

				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;

					$query = "SELECT COUNT(*) FROM file_folders,labs WHERE labs.folder = file_folders.id AND labs.class = file_folders.class";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						if(mysql_result($ret,0,0) && preg_match('~\blab_evaluation\b~',$permissions))
							return true;
					}
				}
			}
		}
		return false;
	}
	function can_upload_file($user,$folder)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM file_folders WHERE id='$folder'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bupload\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	function can_download_file($user,$file)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT file_folders.class AS class,
					 file_folders.public AS public 
					FROM file_folders,files WHERE file_folders.id=files.folder AND files.id='$file'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$public = $result['public'];
				if($public)
					return true;

				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;

					$query = "SELECT COUNT(*) FROM file_folders,labs WHERE labs.folder = file_folders.id AND labs.class = file_folders.class";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						if(mysql_result($ret,0,0) && preg_match('~\blab_evaluation\b~',$permissions))
							return true;
					}
				}
			}
		}
		return false;
	}
	function can_edit_folder($user,$folder)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM file_folders WHERE id='$folder'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	function can_delete_folder($user,$folder)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM file_folders WHERE id='$folder'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	function can_create_folder($user,$class)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM file_folders WHERE id='$folder'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	/* edit means can also delete */
	function can_edit_file($user,$file)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT files.uploader AS uploader,file_folders.class AS class FROM file_folders,files WHERE file_folders.id=files.folder AND files.id='$file'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);

				$uploader = $result['uploader'];
				if($uploader == $user)
					return true;

				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}




	function can_create_lab($user,$class)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
					WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$permissions = mysql_result($ret,0,0);
				if(preg_match('~\create_labs\b~',$permissions))
					return true;
			}
		}
		return false;
	}
	function can_upload_lab_team_file($user,$team)
	{
		if(is_active($user) && user_type($user) == 's')
		{
			$query = "SELECT lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$students = $result['students'];
				if(preg_match("~\b$user\b~",$students))
					return true;
			}
		}
		return false;
	}
	function can_view_lab_team_files($user,$team)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT labs.class AS class,lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$students = $result['students'];
				if(preg_match("~\b$user\b~",$students))
					return true;

				$class= $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\lab_evaluation\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	/* Must be in registration,team limit */
	function can_create_lab_team($user,$lab)
	{
		if(is_active($user) && user_type($user) == 's')
		{
			$query = "SELECT team_limit,register_expire,can_make_new_teams FROM labs WHERE id='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$team_limit = $result['team_limit'];
				$register_expire = $result['register_expire'];
				$can_make_new_teams = $result['can_make_new_teams'];

				if(time() < $register_expire && $can_make_new_teams)
				{
					$query = "SELECT id FROM lab_teams WHERE lab='$lab'";
					$ret = mysql_query($query);
					if($ret && (mysql_num_rows($ret) < $team_limit))
					{
						$query = "SELECT COUNT(*) FROM lab_teams WHERE lab='$lab' AND FIND_IN_SET('$user',students)";
						$ret = mysql_query($query);
						if($ret && mysql_num_rows($ret))
						{
							$count = mysql_result($ret,0,0);
							if($count == 0)
								return true;
						}
					}
				}
			}
		}
		return false;
	}
	function can_create_lab_teams_bulk($user,$lab)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class,team_limit FROM labs WHERE id='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$class = $result['class'];
				$team_limit = $result['team_limit'];

				$query = "SELECT id FROM lab_teams WHERE lab='$lab'";
				if($ret && (mysql_num_rows($ret) < $team_limit))
				{
					$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
							WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						$permissions = mysql_result($ret,0,0);
						if(preg_match('~\manage_labs\b~',$permissions))
							return true;
					}
				}
			}
		}
		return false;
	}
	function can_view_lab_teams($user,$lab)
	{
		return is_active($user) && user_type($user) != 'g';
	}
	function can_view_lab_team($user,$team)
	{
		return can_view_lab_teams($user,$team);
	}
	function can_edit_lab_team($user,$team)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT labs.class AS class,lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$students = $result['students'];
				if(preg_match("~\b$user\b~",$students))
					return true;

				$class= $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\manage_labs\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	function can_delete_lab_team($user,$team)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT labs.class AS class,lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$students = $result['students'];
				if(preg_match("~^$user$~",$students))//only student
					return true;

				$class= $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\manage_labs\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	/* Must be in registration time,team size limit */
	function can_join_lab_team($user,$team)
	{
		if(is_active($user) && user_type($user) == 's')
		{
			$query = "SELECT lab_teams.is_locked AS is_locked,labs.id AS lab,labs.team_limit AS team_limit,labs.register_expire AS register_expire,lab_teams.students AS students,lab.users_per_team_limit AS users_per_team_limit FROM labs,lab_teams WHERE lab_teams.id='$team' AND labs.id = lab_teams.lab";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$team_limit = $result['team_limit'];
				$register_expire = $result['register_expire'];
				$lab = $result['lab'];
				$students = $result['students'];
				$users_per_team_limit = $result['users_per_team_limit'];
				$is_locked = $result['is_locked'];
				if(time() < $register_expire && (preg_match_all('~\b[0-9]*\b~',$students,$m) < $users_per_team_limit) && !$is_locked)
				{
					$query = "SELECT id FROM lab_teams WHERE lab='$lab'";
					$ret = mysql_query($query);
					if($ret && (mysql_num_rows($ret) < $team_limit))
					{
						$query = "SELECT COUNT(*) FROM lab_teams WHERE lab='$lab' AND FIND_IN_SET('$user',students)";
						$ret = mysql_query($query);
						if($ret && mysql_num_rows($ret))
						{
							$count = mysql_result($ret,0,0);
							if($count == 0)
								return true;
						}
					}
				}
			}
		}
		return false;
	}
	/* Must be joined */
	function can_leave_lab_team($user,$team)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			$query = "SELECT lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$students = $result['students'];
				if(preg_match("~\b$user\b~",$students))
					return true;
			}
		}
		return false;
	}
	function can_kick_from_lab_team($user,$target_user,$team)
	{
		return true;
	}
	function can_edit_lab($user,$lab)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM labs WHERE id='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$class = mysql_result($ret,0,0);
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\manage_labs\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	function can_view_lab($user,$lab)
	{
		return is_active($user) && user_type($user) != 'g';
	}
	function can_delete_lab($user,$lab)
	{
		if(is_active($user) && user_type($user) != 'g')
		{
			if(user_type($user) == 'a')
				return true;

			$query = "SELECT class FROM labs WHERE id='$lab'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$class = mysql_result($ret,0,0);
				$query = "SELECT class_association_types.permissions AS permissions, FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\manage_labs\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	
	function can_send_validation_email($user,$target_user)
	{
		if(is_active($user))
		{
			$query = "SELECT is_email_validated AS valid FROM users WHERE id='$target_user'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$valid = mysql_result($ret,0,0);
				if(!$valid && (user_type($user) == 'a' || ($target_user == $user)))
					return true;
			}
		}
		return false;
	}
	function can_view_inactive_accounts($user)
	{
		return is_active($user) && user_type($user) == 'a';
	}
	function can_view_statistics($user)
	{
		return is_active($user) && user_type($user) == 'a';
	}
?>
