<?php
	$LOGIN_DURATION = 1200;
	$HASH_ALGORITHM = 'sha256';

	$MAX_USERNAME_LENGTH = 32;
	$MIN_USERNAME_LENGTH = 4;

	$MIN_PASSWORD_LENGTH = 8;
	$MAX_PASSWORD_LENGTH = 16;

	$MIN_NAME_LENGTH = 1;
	$MAX_NAME_LENGTH = 32;

	$MAX_TELEPHONE_NUMBER_LENGTH = 15;
	$MIN_TELEPHONE_NUMBER_LENGTH = 7;

	$ALLOWED_HTML_TAGS = "<b><i><em><br><hr><a><img><table><td><th><tr><p><ul><ol><li>";
	$ALLOWED_HTML_ATTRIBUTES = array('href','src','title','alt','type','rowspan','colspan','lang');

	$USER_TYPES = array(0 => 'g', 1 => 's', 2 => 'p', 3 => 'a');
	$USER_TYPES_FULL = array(0 => _('Guest'), 1 => _('Student'), 2 => _('Professor'), 3 => _('Admin'));
	$DEFAULT_ACCOUNT_ACTIVE_STATE = '0';//written on the db during registration

	$CLASS_PERMISSIONS = array('announce','manage_announcements','create_lab','lab_evaluation','upload','manage_files','create_test','evaluate_tests')
?>
