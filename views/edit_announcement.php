<h2><?php echo _('Edit announcement');?></h2>
<div class='editAnnouncementWrapper'>
<?php	
	include_once('../lib/access_rules.php');

        if(!isset($error))
                $error = '';

	$aid = isset($_GET['id'])?$_GET['id']:0;
	if(can_edit_announcement($logged_userid,$aid))
	{
		$query = "SELECT * FROM announcements WHERE id = '$aid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);?>
			<form action="edit_announcement.php" method="post">
				<fieldset>
					<legend><?php echo _('Announcement');?></legend>		
					<label><?php echo _('Title');?> </label>
					<input type='text' name='title' value="<?php echo $result['title'];?>" placeholder="<?php echo _('Class name');?>" />
					<label><?php echo _('Body');?> </label>
					<textarea class='announcementTextarea' name='text' placeholder="<?php echo _('Announcement body...');?>" ><?php echo $result['text'];?></textarea>
					<input type='hidden' name='id' value="<?php echo $result['id'];?>" />
					<input type="submit" value="<?php echo _('Submit');?>" />
				</fieldset>
			</form>
<?php
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
?>
</div>
