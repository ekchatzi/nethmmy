<ul>
<?php
	include_once('../lib/access_rules.php');
	//special case for v=lab//
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		if($_GET['v']=='lab')
		{
			$query = "SELECT class FROM labs WHERE id = '$id'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret) && ($cid = mysql_result($ret,0,0)))
			{
				$id=$cid;
			}
			else 
			{
				$id = 0;
			}
		}
	}
	$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
	$ret = mysql_query($query);
	if($ret && mysql_num_rows($ret) && ($classes = mysql_result($ret,0,0)))
	{
		$query = "SELECT * FROM classes WHERE id IN($classes)";
		$ret = mysql_query($query);
		$view_accept = array('class'=>_('Class'), 'announcements' => _('Announcements'), 'class_files' => _('Files'), 'lab' => _('Lab'));
		if($ret && mysql_num_rows($ret)) 
		{
			while($row = mysql_fetch_array($ret)) 
			{	
				echo "<li><a href='class/".$row['id']."/'  class='navigationTitles'";
				if (isset($id)&&$id>0&&$id==$row['id']&&array_key_exists($_GET['v'], $view_accept)) 
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
					//labs navigation view//
					$query2="SELECT title,id FROM labs WHERE class = '$id'";
					$ret2 = mysql_query($query2);
					if($ret2 && mysql_num_rows($ret2)) 
					{
						while($row2 = mysql_fetch_array($ret2)) 
						{
							echo "<li><a href=lab/".$row2['id']."/ ";
							if (isset($_GET['v'])&&$_GET['v']=='lab'&&$_GET['id']==$row2['id']) 
							{
							echo "id='navigationHl'";
							}	
							echo " class='navigationClassDirectories'>".$row2['title']."</a></li>";
						}
					}
					if(can_create_lab($logged_userid,$id))
					{
						echo "<li><a href='new_lab/$id/'  class='navigationClassDirectories'>"._("New Lab")."</a></li>";
					}
					echo "</ul></li>";
				}
				else 
				{
					echo ">".$row['title']."</a>";
				}
			}
		}
	}
?>
<li><a href='classes/'  class='navigationTitles'><?php echo _("All Classes");?></a></li>
</ul>
