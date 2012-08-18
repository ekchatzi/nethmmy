<h2><?php echo _('Most recent announcements');?> </h2>
<div class='studentHomeWrapper'>
<div class='pastAnnouncementsWrapper'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
	$ret = mysql_query($query);
	if($ret && mysql_num_rows($ret) && ($classesraw = mysql_result($ret,0,0)))
	{
		$query = "SELECT announcements.id AS id,
					 announcements.text AS body,
					 announcements.title AS title,
					 announcements.update_time AS update_time,
					 announcements.post_time AS post_time,
					 users.last_name AS last_name,
					 users.id AS poster_id,
					 classes.id AS class_id,
					 classes.title AS class_title
					 FROM announcements,users,classes
					WHERE announcements.class IN($classesraw) AND users.id = announcements.poster AND classes.id = announcements.class ORDER BY update_time DESC LIMIT $NEW_ANNOUNCEMENTS_SHOWN";
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
				$class_id = $row['class_id'];
				$class_title = $row['class_title'];
				if(can_view_profile($logged_userid,$poster_id))
				{				
					$poster = "<a href='profile/$poster_id/'>$poster</a>";
				}
				if(can_view_class($logged_userid,$class_id))
				{
					$class = "<a href='class/$class_id/'>$class_title</a>";
				}
				
?>
	<div class='pastAnnouncement' id="pastAnnouncement<?php echo $id;?>">
	<p class='announcementClass'><?php echo $class?></p>
	<h3 class='announcementTitle'><?php echo $title;?></h3>
	<p class='announcementInfo'><?php echo sprintf(_('Posted on %s by %s. Last update on %s.'),$post_time,$poster,$update_time);?></p>
	<pre class='announcementBody'><?php echo $body;?></pre>
	</div>
<?php		
			}
		}
		else
		{
			echo _('No announcements yet.');
		}?>
</div>
<?php	
	}
	else
	{
		echo _("Haven't subscribed to any classes yet.");
	}
?>	
</div>

