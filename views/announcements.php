<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

	$allowed = false;
        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$post = false;
	$edit = false;
	$class_link = _('some class');
	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		$query = "SELECT title FROM classes WHERE id='$cid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
			$class_link = "<a href='class/$cid/'>".mysql_result($ret,0,0)."</a>";

		if(can_post_announcement($logged_userid,$cid))
		{
			$show = true;
			$allowed = true;
			$post = true;
		}

		$id = array();
		$title = array();
		$body = array();
		$update_time = array();
		$post_time = array();
		$poster_id = array();
		$poster = array();
		if(can_view_announcements($logged_userid,$cid))
		{
			$show = true;
			$allowed = true;
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
					$title[] = $row['title'];
					$body[] = $row['body'];
					$id[] = $row['id'];
					$update_time[] = strftime($DATE_FORMAT,$row['update_time']);
					$post_time[] = strftime($DATE_FORMAT,$row['post_time']);
					$poster_id_t = $poster_id[] = $row['poster_id'];
					$poster_t = $row['last_name'];
					if(can_view_profile($logged_userid,$poster_id_t))
						$poster_t = "<a href='profile/$poster_id_t/'>$poster_t</a>";
					$poster[] = $poster_t;
				}
			}

		}
	}
	else
	{
		$error[] = $e;
	}

	if(!$allowed)
		$error[] = _('Access Denied.');
?>
<h2><?php echo _('Announcements');?></h2>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . _('Announcements');?></p>
<div class='announcementsWrapper'>
<?php	if($show) {?>
<?php		if($post) {?>
			<div class='newAnnouncementWrapper'>
				<fieldset>
					<legend><?php echo _('New announcement');?></legend>
					<form action='new_announcement.php' method='post' onsubmit="return validate(this)">
						<label><?php echo _('Title');?></label>
						<input class='newAnnouncementTitle' size='50' type='text' id='title' name='title' placeholder="<?php echo _('Announcement title here...');?>" />
						<div class='urgentDiv'>
						<?php echo _('Urgent');?>
						<input type='checkbox' name='urgent' value='1'/>
						</div>
						<label><?php echo _('Body');?></label>
						<textarea class='announcementTextarea' id='textEditor' name='text' placeholder="<?php echo _('Announcement body here...');?>" ></textarea>
						<input type='hidden' name='class' value="<?php echo $cid;?>" />
						<input type='submit' value="<?php echo _('Post announcement');?>" />
					</form>
				</fieldset>
			</div>
			<script type='text/javascript'>
				//check the title//
				function validate(form) {
					var title = form.title.value;
					if (title.length<1) {
						inlineMsg('title',"<?php echo _('You have to enter a title');?>", 2, 0);
						return false;
					}
					return true;
				}
			</script>
<?php		}?>
			<div class='pastAnnouncementsWrapper'>
<?php		for($i=0;$i<count($id);++$i) { ;?>
			<?php	if($i > 0) echo "<hr />\n";?>
				<div class='pastAnnouncement <?php if($i%2) echo " alternateAnnouncement";?>' id="pastAnnouncement<?php echo $id[$i];?>">
					<h3 class='announcementTitle'><?php echo $title[$i];?></h3>
					<p class='announcementInfo'><?php echo sprintf(_('Posted on %s by %s. Last update on %s.'),$post_time[$i],$poster[$i],$update_time[$i]);?></p>
					<div class='announcementBody'><?php echo $body[$i];?></div>
<?php				if(can_edit_announcement($logged_userid,$id[$i])) { $edit = true;?>
					<div class='editOptionsWrapper'>
						<a class='editLink' id="editLink<?php echo $id[$i];?>" href="edit_announcement/<?php echo $id[$i];?>/"><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<a class='deleteLink' id="deleteLink<?php echo $id[$i];?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id[$i];?>" alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
					</div>
<?php				}?>
				</div>
<?php		}?>
			</div>
<?php		if(count($id) == 0) {?>
			<p><?php echo _('No announcements.');?></p>
<?php		}?>
<?php		if($edit) {?>
			<script type='text/javascript'>
				$(document).ready(function(){
					
					var classId = "<?php echo $cid;?>";
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this announcement?');?>")) {
							var id = $(this).attr('id').replace('deleteLink','');
							var s = "<form style='display:none' action='delete_announcement.php' method='post'>";
							s += "<input type='hidden' name='aid' value='"+id+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
						}
					});
				});
			</script>
<?php		}?>
<?php	}?>
</div>
