<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	/*Get data from form*/
	$name = (isset($_POST['name']) && strlen($_POST['name']))?$_POST['name']:'default';
	$folder = isset($_POST['folder'])?$_POST['folder']:'';
	$file = isset($_FILES['file'])?$_FILES['file']:'';
	if(!(($e = folder_id_validation($folder)) || ($e = name_validation($name)) || ($e = file_validation($_FILES['file']))))
	{
		if(can_upload_file($logged_userid,$folder))
		{
			$uploaddir = "../file_store/";
			
			//get the base name
			$uploadfilebase= $uploaddir .pathinfo($file['name'],PATHINFO_FILENAME); 
			$uploadfile = $uploadfilebase;
			//get extension				
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$uploadfile = $uploadfilebase .".$ext";
			if($name == 'default')
					$name = pathinfo($file['name'],PATHINFO_BASENAME);
			$suffixno = 0;
			//if file exists add a number at end of 
			//basename in iterative manner until we
			//are clear
			while(file_exists($uploadfile))
			{
				$uploadfile = $uploadfilebase . $suffixno . ".$ext";
				$suffixno++;
			}

			//now that we settled on target filename
			//make the move
			if(move_uploaded_file($file['tmp_name'],$uploadfile))
			{
				$query = "INSERT INTO files (folder,full_path,name,uploader,upload_time) VALUES 
							('$folder','".mysql_real_escape_string($uploadfile)."','"
							.mysql_real_escape_string($name)."','$logged_userid','"
							.time()."')";
				mysql_query($query) || ($error[] = mysql_error());
				$message[] = _('File uploaded successfully.');	
			}
			else
			{
				$error[] =  _("File move failed.");
			}
		}
		else
		{
			$error[] = _('Access Denied.');
		}
	}
	else
	{
		$error[] = $e;
	}

	if(isset($_GET['AJAX']))
	{ 
		echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').'],
			"message" : ['.(count($message)?('"'.implode('","',$message).'"'):'').']}';
	}
	elseif(!(isset($DONT_REDIRECT) && $DONT_REDIRECT))
	{
		if(isset($message) && count($message))
			setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

		if(isset($error) && count($error))
			setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

		$redirect = "files/$folder/";
		include('redirect.php');
	}
?>	
