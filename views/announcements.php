<h2><?php echo _('Announcements');?></h2>
<div class='announcementsWrapper'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

	$allowed = false;
        if(!isset($error))
                $error = '';

	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		if(can_post_announcement($logged_userid,$cid))
		{
			$allowed = true;?>
			<div class='newAnnouncementWrapper'>
				<fieldset>
					<legend><?php echo _('New announcement');?></legend>
					<form action='new_announcement.php' method='post'>
						<label><?php echo _('Title');?></label>
						<input type='text' name='title' placeholder="<?php echo _('Announcement title here...');?>" />
						<label><?php echo _('Body');?></label>
						<textarea class='announcementTextarea' name='text' placeholder="<?php echo _('Announcement body here...');?>" ></textarea>
						<input type='hidden' name='class' value="<?php echo $cid;?>" />
						<input type='submit' value="<?php echo _('Post announcement');?>" />
					</form>
				</fieldset>
			</div>
	<?php	}?>
	<?php
		if(can_view_announcements($logged_userid,$cid))
		{
			$allowed = true;?>
			<div class='pastAnnouncementsWrapper'>
<?php
			$query = "SELECT announcements.id AS id,
					 announcements.text AS body,
					 announcements.title AS title,
					 announcements.update_time AS update_time,
					 announcements.post_time AS post_time,
					 users.last_name AS last_name,
					 users.id AS poster_id
					 FROM announcements,users
					WHERE announcements.class='$cid' AND users.id = announcements.poster ORDER BY update_time DESC,post_time DESC";
			$ret = mysql_query($query);
			if($ret && mysql_numrows($ret))
			{
				$first = true;
				while($row = mysql_fetch_array($ret))
				{
					if(!$first)
					{
						echo "<hr />\n";
					}
					$first = false;
					$title = $row['title'];
					$body = $row['body'];
					$id = $row['id'];
					$update_time = strftime($DATE_FORMAT,$row['update_time']);
					$post_time = strftime($DATE_FORMAT,$row['post_time']);
					$poster_id = $row['poster_id'];
					$poster = $row['last_name'];
					if(can_view_profile($logged_userid,$poster_id))
						$poster = "<a href='profile/$poster_id/'>$poster</a>";
?>
				<div class='pastAnnouncement' id="pastAnnouncement<?php echo $id;?>">
					<h3 class='announcementTitle'><?php echo $title;?></h3>
					<p class='announcementInfo'><?php echo sprintf(_('Posted on %s by %s. Last update on %s.'),$post_time,$poster,$update_time);?></p>
					<pre class='announcementBody'><?php echo $body;?></pre>

<?php
					if(can_edit_announcement($logged_userid,$id))
					{?>
					<div class='editOptionsWrapper'>
						<a class='editLink' id="editLink<?php echo $id;?>" href="edit_announcement/<?php echo $id;?>/"><img src='images/resource/edit-pencil.gif' class='editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<a class='deleteLink' id="deleteLink<?php echo $id;?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='deleteIcon' id="deleteIcon<?php echo $id;?>" alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<script type='text/javascript'>
							$(document).ready(function(){
								var classId = "<?php echo $cid;?>";
								$('.deleteLink').click(function(){
									var id = $(this).attr('id').replace('deleteLink','');
									var s = "<form style='display:none' action='delete_announcement.php' method='post'>";
									s += "<input type='hidden' name='aid' value='"+id+"' />";
									s += '</form>';
									var form = $(s).appendTo('body');
									form.submit(); 	
								});
							});
						</script>
					</div>
<?php					}?>
				</div>
<?php				}
			}
			else
			{
				echo _('No announcements yet.');
			}?>
			</div>
	<?php	}
	}
	else
	{
		$error .= $e;
	}

	if(!$allowed)
	{
		$error .= _('Access Denied.');
	}
	if($error)
	{?>
		<p class='error'><?php echo $error;?></p>
<?php	}?>
</div>
