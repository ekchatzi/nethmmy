<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	$show = false;
	$classesraw = '';
	if(!isset($error))
                $error = '';
	$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
	$ret = mysql_query($query);
	if($ret && mysql_num_rows($ret))
	{
		$show =true;
		$classesraw = mysql_result($ret,0,0);
		
		$id = array();
		$title = array();
		$body = array();
		$update_time = array();
		$post_time = array();
		$poster = array();
		$class = array();
		$e = '';
		if($classesraw && !($e = id_list_validation($classesraw)))
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
				while($row = mysql_fetch_array($ret))
				{
					$title[] = $row['title'];
					$body[] = $row['body'];
					$id[] = $row['id'];
					$update_time[] = strftime($DATE_FORMAT,$row['update_time']);
					$post_time[] = strftime($DATE_FORMAT,$row['post_time']);
					$poster_id = $row['poster_id'];
					$poster_last = $row['last_name'];
					$class_id = $row['class_id'];
					$class_title = $row['class_title'];				
					$poster[] = (can_view_profile($logged_userid,$poster_id))?"<a href='profile/$poster_id/'>$poster_last</a>":$poster_last;
					$class[] = (can_view_class($logged_userid,$class_id))?"<a href='class/$class_id/'>$class_title</a>":$class_title;
				}
			}
		}
		else
		{
			$error .= $e;
		}
	}
	else
	{
		$error .= mysql_error();
	}	
?>


<h2><?php echo _('Home');?> </h2>
<div class='studentHomeWrapper'>
<h3><?php echo _('Newest Announcements');?>
<div class='pastAnnouncementsWrapper'>
<?php	if ($show) {?>
<?php		for($i=0;$i<count($id);$i++) {?>
				<div class='pastAnnouncement' id="pastAnnouncement<?php echo $id[$i];?>">
				<p class='announcementClass'><?php echo $class[$i]?></p>
				<h3 class='announcementTitle'><?php echo $title[$i];?></h3>
				<p class='announcementInfo'><?php echo sprintf(_('Posted on %s by %s. Last update on %s.'),$post_time[$i],$poster[$i],$update_time[$i]);?></p>
				<pre class='announcementBody'><?php echo $body[$i];?></pre>
				</div>
<?php		}?>
<?php		if(!$classesraw) {?>
				<p><?php echo _('You don\'t have any class subscriptions.');?></p>
<?php		}
			elseif(count($id) == 0) {?>
				<p><?php echo _('No announcements yet.');?></p>
<?php		}?>		
<?php	}?>
</div>
</div>
