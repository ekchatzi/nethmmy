<ul>
<?php
	$query = "SELECT * FROM classes";
	$ret = mysql_query($query);
	$view_accept = array('class'=>_('Class'), 'announcements' => _('Announcements'), 'class_files' => _('Files'));
	if($ret && mysql_num_rows($ret)) 
	{
		while($row = mysql_fetch_array($ret)) 
		{
			echo "<li><a href='class/".$row['id']."/'  class='navigationTitles'";
			if (isset($_GET['id'])&&$_GET['id']>0&&$_GET['id']==$row['id']&&array_key_exists($_GET['v'], $view_accept)) 
			{
				echo "id='navigationClassHl'>".$row['title']."</a>";
				echo "<ul>";
				$view_names = array('announcements' => _('Announcements'), 'class_files' => _('Files'));
				foreach ($view_names as $view => $view_title) 
				{
					echo "<li><a href=".$view."/".$row['id']."/ ";
					if (isset($_GET['v'])&&$_GET['v']==$view) 
					{
						echo "id='navigationHl'";
					}
					echo " class='navigationClassDirectories'>".$view_title."</a></li>";	
				}	
				echo "</ul></li>";
				
			}
			else 
			{
				echo ">".$row['title']."</a>";
			}
		}
		echo "<li><a href='classes/'  class='navigationTitles'>"._("All Classes")."</a>";
	}
	else 
	{
		echo "<li>"._("No classes there are")."</li>";
	}
?>
</ul>
