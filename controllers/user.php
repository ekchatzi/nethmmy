class user_controller
{
	$userid;
	$user_type;
	$is_active;
	
	public function view()
	{
	
	}
	
	public function listing()
	{
	
	}
	
	public function change_password()
	{
	
	}
	
	public function subscriptions()
	{
	
	}
	
	public function delete()
	{
	
	}
	
	public function edit()
	{
	
	}
	
	public function edit_user_type()
	{
	
	}
	
	public function login()
	{
	
	}
	
	public function logout()
	{
	
	}
	
	public function register()
	{
	
	}
	
	public request_change_password()
	{
	
	}
	
	

	public function can_create_class()
	{
		return $is_active && ( $user_type == 'a' );
	}
	public function can_edit_user_type($target_user)
	{
		return $is_active && ( $user_type == 'a' );
	}
	public function can_edit_active_status($target_user)
	{
		return $is_active && ( $user_type == 'a' );
	}
	public function can_view_profile($target_user)
	{
		return $is_active || (($userid == $target_user->$userid) && $target_user->$user_type != 'g' );
	}
	public function can_view_contact_information($target_user)
	{
		return ($is_active && (( $user_type == 'p') || ( $user_type == 'a'))) || (( $userid == $target_user->$userid) && ($target_user->$user_type != 'g'));
	}
	public function can_view_account_information($target_user)
	{
		return ($is_active && ( $user_type == 'a')) || (( $userid == $target_user->$userid) && ($target_user->$user_type != 'g'));
	}
	public function can_edit_account($target_user)
	{
		return ($is_active && ( $user_type == 'a')) || (( $userid == $target_user->$userid) && ($target_user->$user_type != 'g'));
	}
	public function can_delete_account($target_user)
	{
		return ($is_active && ( $user_type == 'a'));
	}
	public function can_edit_title($target_user)
	{
		return $is_active && ( $user_type == 'a' );
	}
	public function can_edit_aem($target_user)
	{
		return ($is_active && ( $user_type == 'a')) || (( $userid == $target_user->$userid) && ($target_user->$user_type != 'g'));
	}
	public function can_view_class($class)
	{
		return ($is_active && ( $user_type != 'g'));
	}
	public function can_view_class_directories( $class)
	{
		return ($is_active && ( $user_type != 'g'));
	}
	public function can_edit_class($class)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_delete_class($target_user)
	{
		return ($is_active && ( $user_type == 'a'));
	}


	public function can_edit_class_subscriptions($target_user)
	{
		return ( $userid == $target_user->$userid) && (is_active($target_user) && ($target_user->$user_type != 'g'));
	}
	public function can_request_password_reset($target_user)
	{
		return ( $userid == $target_user->$userid) && (is_active($target_user))  && ($target_user->$user_type != 'g' );
	}



	public function can_view_professor_list()
	{
		return true;
	}
	public function can_view_classes_list()
	{
		return true;
	}
	public function can_view_user_associations($target_user)
	{
		return true;
	}
	public function can_edit_class_association_types()
	{
		return ($is_active && ( $user_type == 'a'));
	}
	public function can_edit_class_associations($class)
	{
		return ($is_active && ( $user_type == 'a'));
	}
	public function can_edit_titles()
	{
		return ($is_active && ( $user_type == 'a'));
	}

	public function can_view_announcements($class)
	{
		return ($is_active && ( $user_type != 'g'));
	}
	public function can_post_announcement($class)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_edit_announcement($announcement)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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




	public function can_read_folder($folder)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
						if(mysql_result($ret,0,0) && preg_match('~\bevaluate_labs\b~',$permissions))
							return true;
					}
				}
			}
		}
		return false;
	}
	public function can_upload_file($folder)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_download_file($file)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
				return true;

			$query = "SELECT file_folders.id AS folder,
					 file_folders.class AS class,
					 file_folders.public AS public 
					FROM file_folders,files WHERE file_folders.id=files.folder AND files.id='$file'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$public = $result['public'];
				$folder = $result['folder'];
				if($public)
					return true;

				$permissions = '';
				$class = $result['class'];
				$query = "SELECT class_association_types.permissions AS permissions FROM class_associations,class_association_types
						WHERE class_associations.class='$class' AND class_associations.user='$user' AND class_associations.type = class_association_types.id";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$permissions = mysql_result($ret,0,0);
					if(preg_match('~\bmanage_files\b~',$permissions))
						return true;

					$query = "SELECT COUNT(*) FROM labs WHERE labs.folder = '$folder' AND labs.class";
					$ret = mysql_query($query);
					if($ret && mysql_num_rows($ret))
					{
						if(mysql_result($ret,0,0) && preg_match('~\bevaluate_labs\b~',$permissions))
							return true;

						$query = "SELECT COUNT(*) FROM lab_teams WHERE FIND_IN_SET('$file',lab_teams.files) AND FIND_IN_SET('$user',lab_teams.students)";
						$ret = mysql_query($query);
						if($ret && mysql_num_rows($ret))
						{
							if(mysql_result($ret,0,0))
								return true;
						}
					}
				}
			}
		}
		return false;
	}
	public function can_edit_folder($folder)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_delete_folder($folder)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_create_folder($class)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
				return true;

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
		return false;
	}
	/* edit means can also delete */
	public function can_edit_file($file)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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




	public function can_create_lab($class)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_upload_lab_team_file($team)
	{
		if($is_active && $user_type == 's')
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
	public function can_view_lab_team_files($team)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
					if(preg_match('~\evaluate_labs\b~',$permissions))
						return true;
				}
			}
		}
		return false;
	}
	/* Must be in registration,team limit */
	public function can_create_lab_team($lab)
	{
		if($is_active && $user_type == 's')
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
	public function can_create_lab_teams_bulk($lab)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_view_lab_teams($lab)
	{
		return $is_active && $user_type != 'g';
	}
	public function can_view_lab_team($team)
	{
		return can_view_lab_teams($team);
	}
	public function can_edit_lab_team($team)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_delete_lab_team($team)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_join_lab_team($team)
	{
		if($is_active && $user_type == 's')
		{
			$query = "SELECT lab_teams.is_locked AS is_locked,
					labs.id AS lab,labs.team_limit AS team_limit,
					labs.register_expire AS register_expire,
					lab_teams.students AS students,
					labs.users_per_team_limit AS users_per_team_limit 
					FROM labs,lab_teams WHERE lab_teams.id='$team' AND labs.id = lab_teams.lab";
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
	public function can_leave_lab_team($team)
	{
		if($is_active && $user_type != 'g')
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
	public function can_kick_from_lab_team($target_user,$team)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
				return true;

			$query = "SELECT labs.class AS class,lab_teams.students AS students FROM labs,lab_teams WHERE lab_teams.id='$team' AND lab_teams.lab=labs.id";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
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
	public function can_edit_lab($lab)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	public function can_view_lab($lab)
	{
		return $is_active && $user_type != 'g';
	}
	public function can_delete_lab($lab)
	{
		if($is_active && $user_type != 'g')
		{
			if( $user_type == 'a')
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
	
	public function can_send_validation_email($target_user)
	{
		if($is_active)
		{
			$query = "SELECT is_email_validated AS valid FROM users WHERE id='$target_user'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$valid = mysql_result($ret,0,0);
				if(!$valid && ( $user_type == 'a' || ($target_user == $user)))
					return true;
			}
		}
		return false;
	}
	public function can_view_inactive_accounts()
	{
		return $is_active && $user_type == 'a';
	}
	public function can_view_statistics()
	{
		return $is_active && $user_type == 'a';
	}
	public function can_view_log()
	{
		return $is_active && $user_type == 'a';	
	}
	public function can_clean_log()
	{
		return $is_active && $user_type == 'a';	
	}
}

?>
