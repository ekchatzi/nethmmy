<span id='classesHeader'><h2 id='classesTitle'> <?php echo _('Classes');?> </h2>
<p id='subscribeTitle'><?php echo _('Subscribe');?></p></span>
<div class='classesWrapper'>
<form action='classes_subscription.php' method='post'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	//add multiple values per key
		if (can_view_classes_list($logged_userid)) 
		{	
			for ($i=0;$i<=$SEMESTERS_COUNT;$i++) 
			{	
				$query = "SELECT * FROM classes WHERE FIND_IN_SET($i, semesters)";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret)) 
				{
					echo "<div class='semesterDiv'><p class='semesterTitle'>"._("Semester ".$i)."</p>";
					while($row = mysql_fetch_array($ret)) 
					{
						echo "<p class='classTitleField'>".$row['title'];
						if (can_edit_subscriptions($logged_userid)) 
						{						
							echo "<input class='classCheck' name='subscribe[]' value=".$row['id']." type='checkbox'/>";
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