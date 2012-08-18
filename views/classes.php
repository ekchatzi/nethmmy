<span id='classesHeader'><h2 id='classesTitle'> <?php echo _('Classes');?> </h2>
<p id='subscribeTitle'><?php echo _('Subscribe');?></p></span>
<div class='classesWrapper'>
<form action='classes_subscription.php' method='post'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	if (can_view_classes_list($logged_userid)) 
	{	
		$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret) && ($classesraw = mysql_result($ret,0,0)))
		{
			$classes = explode(",", $classesraw);
		}
		for ($i=0;$i<=$SEMESTERS_COUNT;$i++) 
		{	
			$query = "SELECT * FROM classes WHERE FIND_IN_SET($i, semesters)";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret)) 
			{
				echo "<div class='semesterDiv'><p class='semesterTitle'>"._("Semester")." ".$i."</p>";
				while($row = mysql_fetch_array($ret)) 
				{
					echo "<p class='classTitleField'>";
					if(can_view_class($logged_userid,$row['id']))
					{
						echo "<a href='class/".$row['id']."/' class='classLink'>".$row['title']."</a>";
					}
					else
					{
						echo $row['title'];
					}
					if (can_change_class_subscriptions($logged_userid,$logged_userid )) 
					{						
						echo "<input class='classCheck' name='subscribe[]' value=".$row['id']." id=".$row['id']." type='checkbox' ";
						if (isset($classes)&&in_array($row['id'], $classes))
						{
							echo "checked='true'";
						}
						echo "/>";
					}
					echo "</p>";						
				}
				echo "</div>";
			}
		}
	}
	else 
	{
		echo _("Sorry you can't see or subscribe to any classes");
	}
?>
<input class='submit' id='button' type='submit' value='<?php echo _("Submit changes");?>'/> 
</form>
</div>
<script>
$(document).ready(function() 
{	
	//unchecks all classes with same id when one is unchecked//
	$('.classCheck').click(function() 
	{
		var thischeck=$(this);
		var checkid=$(this).attr('id');
		if (!thischeck.is(':checked')) 
		{
			$('#'+checkid).attr('checked', false);
		}
	});
});
</script>