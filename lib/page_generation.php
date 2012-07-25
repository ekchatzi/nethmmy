<?php
function actions_redirection_page($error,$warning,$redirect,$message="",$waittime=0)
{
	$ACTIONS_REDIRECT = true;
	$_GET['v'] = 'redirection';
	include('../../public_html/index.php');
}
?>
