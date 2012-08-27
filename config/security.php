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

	$ALLOWED_HTML_TAGS = "<b><i><em><br><hr><a><img><table><td><th><tr><p><ul><ol><li><strong><sup><sub><u><strike>";
	$ALLOWED_HTML_ATTRIBUTES = array('href','src','title','alt','type','rowspan','colspan','lang');

	$USER_TYPES = array(0 => 'g', 1 => 's', 2 => 'p', 3 => 'a');
	$USER_TYPES_FULL = array(0 => _('Guest'), 1 => _('Student'), 2 => _('Professor'), 3 => _('Admin'));
	$DEFAULT_ACCOUNT_ACTIVE_STATE = '0';//written on the db during registration

	$CLASS_PERMISSIONS = array('announce','manage_announcements','create_lab','lab_evaluation','upload','manage_files','create_test','evaluate_tests');

	$MAX_FILESIZE = 8*1024*1024;//in bytes

	$MAX_LAB_TEAM_LIMIT = 200;
	$MAX_USERS_PER_LAB_TEAM_LIMIT = 20;
	$MAX_LAB_UPLOAD_LIMIT = 10;
?>
