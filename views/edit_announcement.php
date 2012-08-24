<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$class_link = _('Some class');
	$announcements_link = _('Announcements');
	$aid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = announcement_id_validation($aid)))
	{
		if(can_edit_announcement($logged_userid,$aid))
		{
			$query = "SELECT * FROM announcements WHERE id = '$aid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$show = true;
				$result = mysql_fetch_array($ret);
				$title = $result['title'];
				$text = $result['text'];
				$id = $result['id'];
				$class = $result['class'];

				$query = "SELECT title FROM classes WHERE id='$class'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$class_title = mysql_result($ret,0,0);
					$class_link = "<a href='class/$class/'>$class_title</a>";
					$announcements_link = "<a href='announcements/$class/'>$announcements_link</a>";
				}
			}
			else
			{
				$error .= _('Announcement was not found');
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
?>
<h2><?php echo _('Edit announcement');?></h2>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . $announcements_link . " > " . _('Edit Announcement');?></p>
<div class='editAnnouncementWrapper'>
<?php	if($show) {?>
		<form action="edit_announcement.php" method="post">
			<fieldset>
				<legend><?php echo _('Announcement');?></legend>		
				<label><?php echo _('Title');?> </label>
				<input type='text' name='title' value="<?php echo $title;?>" placeholder="<?php echo _('Announcement title here...');?>" />
				<label><?php echo _('Body');?> </label>
				<textarea class='announcementTextarea' name='text' placeholder="<?php echo _('Announcement body...');?>" ><?php echo $text;?></textarea>
				<input type='hidden' name='id' value="<?php echo $id;?>" />
				<input type="submit" value="<?php echo _('Submit');?>" />
			</fieldset>
		</form>
<?php	}?>
</div>
