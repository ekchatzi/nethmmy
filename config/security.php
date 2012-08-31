<?php
	$LOGIN_DURATION = 30000;
	$HASH_ALGORITHM = 'sha256';

	$MAX_USERNAME_LENGTH = 32;
	$MIN_USERNAME_LENGTH = 4;

	$MIN_PASSWORD_LENGTH = 8;
	$MAX_PASSWORD_LENGTH = 16;

	$MIN_NAME_LENGTH = 1;
	$MAX_NAME_LENGTH = 255;

	$MAX_TELEPHONE_NUMBER_LENGTH = 15;
	$MIN_TELEPHONE_NUMBER_LENGTH = 7;

	$ALLOWED_HTML_TAGS = "<b><i><em><br><hr><a><img><table><td><th><tr><p><ul><ol><li><strong><sup><sub><u><strike><div><span>";
	$ALLOWED_HTML_ATTRIBUTES = array('href','src','title','alt','type','rowspan','colspan','lang','style');

	$USER_TYPES = array(0 => 'g', 1 => 's', 2 => 'p', 3 => 'a');
	$USER_TYPES_FULL = array(0 => _('Guest'), 1 => _('Student'), 2 => _('Professor'), 3 => _('Admin'));
	$DEFAULT_ACCOUNT_ACTIVE_STATE = '0';//written on the db during registration

	$CLASS_PERMISSIONS = array('manage_info','announce','manage_announcements','create_labs','manage_labs','evaluate_labs','upload_files','manage_files','create_quizs','evaluate_quizs');
	$CLASS_PERMISSIONS_TEXT = array('manage_info' => _('Manage information'),
					'announce' => _('Make announcements'),
					'manage_announcements' => _('Manage announcements'),
					'create_labs' => _('Create labs/assignments'),
					'manage_labs' => _('Manage labs'),
					'evaluate_labs' => _('Lab/assignment evaluation'),
					'upload_files' => _('Upload files'),
					'manage_files' => _('Manage files'),
					'create_quizs' => _('Create quizs'),
					'evaluate_quizs' => _('Evaluate quizs'));
	
	$ALLOWED_TOKEN_CONTEXTS = array('password_reset','email_validation');
	$MAX_FILESIZE = 8*1024*1024;//in bytes

	$MAX_LAB_TEAM_LIMIT = 200;
	$MAX_USERS_PER_LAB_TEAM_LIMIT = 20;
	$MAX_LAB_UPLOAD_LIMIT = 10;

	//Logging
	$LOG_SIZE = 3000;
	$MIN_LOG_MESSAGES_KEPT = 200;
	$MIN_AGE_LOG_MESSAGES_KEPT = 5*24*60*60;//how old must a log message be to deleted
	$ALLOWED_LOG_TYPES = array(100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,200);
	$LOG_TYPES_TEXT = array( 100 => _('File download'),
				 101 => _('File upload'),
				 102 => _('Lab file upload'),
				 103 => _('Email validation'),
				 104 => _('Announcement'),
				 105 => _('New folder'),
				 106 => _('New lab'),
				 107 => _('New lab team'),
				 108 => _('New lab teams bulk'),
				 109 => _('Password change'),
				 110 => _('New user account'),
				 111 => _('New class'),
				 112 => _('Class deletion'),
				 113 => _('Class update'),
				 114 => _('Lab deletion'),
				 115 => _('Lab update'),
				 116 => _('Lab team deletion'),
				 117 => _('Lab team update'),
				 118 => _('Announcement deletion'),
				 119 => _('Announcement update'),
				 120 => _('File deletion'),
				 121 => _('File update'),
				 122 => _('Folder deletion'),
				 123 => _('Folder update'),
				 124 => _('User deletion'),
				 125 => _('User update'),
				 126 => _('Lab team join'),
				 127 => _('Lab team leave'),
				 128 => _('Lab team kick'),
				 129 => _('Titles deletion'),
				 130 => _('Title edit'),
				 131 => _('Association types deletion'),
				 132 => _('Association types update'),
			 	 133 => _('Association deletion'),
			 	 134 => _('Association creation'),
			 	 135 => _('Association creation'),
			 	 136 => _('User type update'),
			 	 137 => _('User active status update'),
			 	 138 => _('Login'),
			 	 139 => _('Logout'),
			 	 200 => _('Log cleanup'));
?>
