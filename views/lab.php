<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$lid = isset($_GET['id'])?$_GET['id']:0;
	$lab_name = _('Lab');
	if(!($e = lab_id_validation($lid)))
	{
		$query = "SELECT * FROM labs WHERE id='$lid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{

			if(can_view_lab($logged_userid,$lid))
			{
				$result = mysql_fetch_array($ret);
				$show = true;
				$class = $result['class'];
				$lab_name = $result['title'];
				$upload_expire = $result['upload_expire'];
				$class_name = _('Class');
				$query = "SELECT title FROM classes WHERE id='$class'";
				$ret2 = mysql_query($query);
				if($ret2 && mysql_num_rows($ret2))
				{
					$class_name = mysql_result($ret2,0,0);
				}

				$description = ((strlen($result['description'])>0)?$result['description']:_('There is no description yet.'));
				$register_expire_message = sprintf(_('Registrations close on %s.'),strftime($DATE_FORMAT,$result['register_expire']));
				$upload_epire_message = ($result['folder'])?sprintf(_('File uploads deadline on %s.'),strftime($DATE_FORMAT,$result['upload_expire'])):'';
				$lab_info_message = sprintf(_('Created on %s for class %s and updated on %s.'),strftime($DATE_FORMAT,$result['creation_time']),"<a href='class/$class/'>$class_name</a>",strftime($DATE_FORMAT,$result['update_time']));
				$edit_link = (can_edit_lab($logged_userid,$lid))?"<a href='edit_lab/$lid/' id='editLabLink$lid' >". _('Edit')."</a>":''; 
			}
			else
			{
				$error .= _('Access denied.');
			}
		}
		else	
		{
			$error .= _('Database Error.');			
		}
	}
	else
	{
		$error .= $e;
	}
?>
<h2><?php echo $lab_name;?></a></h2>
<div class='labWrapper'>
<?php	if($show) {?>
		<p class='labInfo'><?php echo $lab_info_message;?></p>
		<p class='labInfo'><?php echo $register_expire_message.$upload_expire_message;?></p>
		<fieldset class='labDescriptionWrapper'>
			<legend><?php echo _('Description');?></legend>
		<p><?php echo $description?></p>
		</fieldset>
		<p><?php echo $edit_link;?></p>
<?php		if(can_view_lab_teams($logged_userid,$lid))
		{?>
		<div class='teamsWrapper'>
			<h3><?php echo _('Teams');?></h3>
		</div>
<?php		};?>
<?php	}?>
</div>
