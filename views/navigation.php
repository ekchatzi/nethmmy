<ul>
<?php
	$query = "SELECT * FROM classes";
	$ret = mysql_query($query);
	if($ret && mysql_num_rows($ret)) 
	{
		while($row = mysql_fetch_array($ret)) 
		{
			echo "<li><a href='class/".$row['id']."/'>".$row['title']."</a>";
			if (isset($_GET['id'])&&$_GET['id']>0) 
			{
				if ($_GET['id']==$row['id']) 
				{	
					$view_names = array('announcements' => _('Announcements'), 'class_files' => _('Files'));
					echo "<ul class='navigationClassDirectories'>";
					foreach ($view_names as $view => $view_title) 
					{
						echo "<li ";
						if (isset($_GET['v'])) 
						{
							if ($_GET['v']==$view) 
							{
								echo "id='navigationHl'";
							}
						}
						echo "><a href=".$view."/".$row['id']."/>".$view_title."</a></li>";	
					}	
					echo "</ul></li>";
				}
			}
		}
		echo "<li><a href='classes/'>"._("All Classes")."</a>";
	}
	else 
	{
		echo "<li>"._("No classes there are")."</li>";
	}
?>
</ul>
