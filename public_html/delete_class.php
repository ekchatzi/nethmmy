<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/login.php');
	include_once("../lib/access_rules.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");
	include_once("../config/general.php");

        if(!isset($error)) 
                $error = '';

	/* Data */
	$cid = isset($_POST['cid'])?$_POST['cid']:'';
	$class = '';
	if(!($e = class_id_validation($cid)))
	{
		$class = $cid;
		if(can_delete_class($logged_userid,$cid))
		{
			$query = "DELETE FROM classes WHERE id='$cid' LIMIT 1";
			mysql_query($query) || ($error .= mysql_error());		
		}
		else
		{
			$error .= _('Access denied.');
		}	
	}
	else
	{
		$error .= $e;
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : "'.$error.'"}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && strlen($message))
			setcookie('message',$message,time()+3600,$INDEX_ROOT);


		$redirect = ($class && $error)?"class/$class/":"home/";
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
	
?>
