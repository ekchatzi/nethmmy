<?php
	$v = isset($_GET['v'])?$_GET['v']:'home';

	$view['home'] = '../views/home.php';
	$view_title['home'] = _('eClasses');

	$view['classes'] = '../views/classes.php';
	$view_title['classes'] = _('Classes');

	$view['class'] = '../views/class.php';
	$view_title['class'] = _('Class');

	$view['edit_class'] = '../views/edit_class.php';
	$view_title['edit_class'] = _('Edit Class');

	$view['new_class'] = '../views/new_class.php';
	$view_title['new_class'] = _('New Class');

	$view['profile'] = '../views/profile.php';
	$view_title['profile'] = _('Profile');

	$view['edit_class_association_types'] = '../views/edit_class_association_types.php';
	$view_title['edit_class_association_types'] = _('Edit Class Association Types');

	$view['announcements'] = '../views/announcements.php';
	$view_title['announcements'] = _('Announcements');

	$view['edit_announcement'] = '../views/edit_announcement.php';
	$view_title['edit_announcement'] = _('Edit Announcement');

	$view['class_files'] = '../views/class_files.php';
	$view_title['class_files'] = _('Class Files');

	$view['files'] = '../views/files.php';
	$view_title['files'] = _('Folder contents');

	$view['file'] = '../views/file.php';
	$view_title['file'] = _('File information');

	$view['lab'] = '../views/lab.php';
	$view_title['lab'] = _('Lab');

	$view['edit_lab'] = '../views/edit_lab.php';
	$view_title['edit_lab'] = _('Edit Lab');

	$view['new_lab'] = '../views/new_lab.php';
	$view_title['new_lab'] = _('New Lab');

	$view['edit_profile'] = '../views/edit_profile.php';
	$view_title['edit_profile'] = _('Edit Profile');

	$view['edit_titles'] = '../views/edit_titles.php';
	$view_title['edit_titles'] = _('Edit Titles');

	$view['inactive'] = '../views/inactive.php';
	$view_title['inactive'] = _('Inactive accounts');

	$view['stats'] = '../views/stats.php';
	$view_title['stats'] = _('Statistics');

	$view['register'] = '../views/register.php';
	$view_title['register'] = _('Register Application');
	
	$view['professors'] = '../views/professors.php';
	$view_title['professors'] = _('Professors');
	
	$view['change_password'] = '../views/change_password.php';
	$view_title['change_password'] = _('Change your password');

	$view['log'] = '../views/log.php';
	$view_title['log'] = _('Log');

	/* if v is one of the above (so $view[$v] evaluates to true)
	we will include $view[$v] else we include by default home view*/	
	$VIEW = isset($view[$v])?$view[$v]:$view['home'];
	$TITLE = isset($view_title[$v])?$view_title[$v]:$view_title['home'];
?>
