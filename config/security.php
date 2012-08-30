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
	$ALLOWED_LOG_TYPES = array(100,101,102,103,104,105,106,107,108,109,110,111);
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
				 111 => _('New class'));
?>
