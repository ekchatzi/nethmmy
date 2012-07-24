<?php
	$v = isset($_GET['v'])?$_GET['v']:'home';

	$view['home'] = '../views/home.php';
	$view_title['home'] = _('eClasses');

	$view['classes'] = '../views/classes.php';
	$view_title['classes'] = _('Classes');

	$view['register'] = '../views/register.php';
	$view_title['register'] = _('Register Application');
	
	$view['redirection'] = '../views/redirection.php';
	$view_title['redirection']  = _('Redirection');

	/* if v is one of the above (so $view[$v] evaluates to true)
	we will include $view[$v] else we include by default home view*/	
	$VIEW = isset($view[$v])?$view[$v]:$view['home'];
	$TITLE = isset($view_title[$v])?$view_title[$v]:$view_title['home'];
?>
