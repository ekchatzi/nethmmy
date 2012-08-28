<?php
	include_once("../lib/connect_db.php");
	include_once("../lib/access_rules.php");
	include_once("../config/security.php");
	include_once("../lib/login.php");
	include_once("../lib/localization.php");
	include_once("../lib/validate.php");

	$lab = isset($_POST['lab'])?$_POST['lab']:0;
	$lab_team = 0;
        if(!isset($error)) 
                $error = '';
	/*Get data from form*/
	$name = (isset($_POST['name']) && strlen($_POST['name']))?$_POST['name']:'default';
	$team = isset($_POST['tid'])?$_POST['tid']:'';
	$file = isset($_FILES['file'])?$_FILES['file']:'';
	if(!(($e = lab_team_id_validation($team)) || ($e = name_validation($name)) || ($e = file_validation($_FILES['file']))))
	{
		if(can_upload_lab_team_file($logged_userid,$team))
		{
			$lab_team = $team;
			$query = "SELECT labs.id AS lab,
					 labs.folder AS folder,
					 lab_teams.files AS files
					 FROM labs,lab_teams WHERE labs.id = lab_teams.lab AND lab_teams.id = '$team'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				$lab = $result['lab'];
				$folder = $result['folder'];
				$files = $result['files']?explode(',',$result['files']):array();

				$uploaddir = "../file_store/";
				if($name == 'default')
					$name = pathinfo($file['name'],PATHINFO_BASENAME);
				//get the base name
				$uploadfilebase= $uploaddir .pathinfo($file['name'],PATHINFO_FILENAME); 
				$uploadfile = $uploadfilebase;
				//get extension				
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$uploadfile = $uploadfilebase .".$ext";
		
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
					mysql_query($query) || ($error .= mysql_error());
					$file = mysql_insert_id();
					if($file)
					{
						$files[] = $file;
						$files = implode(',',$files);

						$query = "UPDATE lab_teams SET
								files='".mysql_real_escape_string($files)."'
								WHERE id='$team' LIMIT 1";
						mysql_query($query) || ($error .= mysql_error());
					}					
				}
				else
				{
					$error .=  _("File move failed.");
				}
			}
			else
			{
				$error .= mysql_error();
			}
		}
		else
		{
			$error .= _('Access Denied.');
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


		$redirect = "lab/$lab/".(($lab_team)?"#labTeamContainer$lab_team":"");
		if(strlen($error))
			setcookie('notify',$error,time()+3600,$INDEX_ROOT);
		include('redirect.php');
	}
?>	
