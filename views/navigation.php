<ul>
<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
      
	if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();
	
	$view_accept = array('class'=>_('Class'), 'edit_class'=>_('Edit Class'), 'announcements' => _('Announcements'), 'class_files' => _('Files'), 'lab' => _('Lab'), 'new_lab' => _('New Lab'), 'edit_lab' => _('Edit Lab'), 'files' =>_('Files'), 'edit_announcement' => _('Announcements'));
	$v = isset($_GET['v'])?$_GET['v']:'';
	
	//Get the class id according to view//
	if(isset($_GET['id']))
	{

		$id = $_GET['id'];
			
		if($v=='lab' || $v=='edit_lab')
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
		elseif($v=='files')
		{
			$query = "SELECT class FROM file_folders WHERE id = '$id'";
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
		elseif($v=='edit_announcement')
		{
			$query = "SELECT class FROM announcements WHERE id = '$id'";
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
		elseif(!($id>0&&array_key_exists($v, $view_accept)))
		{
			$id = 0;
		}
		
		if($id>0)
		{	
			$first = $id;
			$query = "SELECT * FROM classes WHERE id='$id' LIMIT 1";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$result = mysql_fetch_array($ret);
				
				echo "<div class='openClass'><li><a href='class/".$result['id']."/'  class='navigationTitles' id='navigationClassHl'>".$result['title']."</a>";
				if(can_view_class_directories($logged_userid,$logged_userid))
				{
					echo "<ul>";
					$view_names = array('announcements' => _('Announcements'), 'class_files' => _('Files'));
					foreach ($view_names as $view => $view_title) 
					{
						echo "<li><a href=".$view."/".$result['id']."/ ";
						if (isset($v)&&$v==$view) 
						{
							echo "id='navigationHl'";
						}
						elseif (isset($v)&&$view=='class_files'&&$v=='files')
						{
							echo "id='navigationHl'";
						}
						elseif (isset($v)&&$view=='announcements'&&$v=='edit_announcement')
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
							if (isset($v)&&$_GET['id']==$row2['id'])
							{
								if ($v=='lab' || $v=='edit_lab') 
								{
								echo "id='navigationHl'";
								}	
							}
							echo " class='navigationClassDirectories'>".$row2['title']."</a></li>";
						}
					}
					if(can_create_lab($logged_userid,$id))
					{
						echo "<li><a href='new_lab/$id/' ";
						if($v=='new_lab')
						{
							echo "id='navigationHl' ";
						}
						echo "class='navigationClassDirectories'>"._("New Lab")."</a></li>";
					}
					echo "</ul>";	
				}
				echo "</li></div>";
			}
		}
	}
	
	
	//select the subscribed classes and the associated ones//
	$classes = '';
	if(user_type($logged_userid)=='p'||user_type($logged_userid)=='s'||user_type($logged_userid)=='a')
	{
		$query = "SELECT class FROM class_associations WHERE user = '$logged_userid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret)) 
		{
			$classesar = array();
			while($row = mysql_fetch_array($ret)) 
			{
				$classesar[] = $row['class'];
			}
			$classes=implode(',',$classesar);
		}
		
		$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$classes = $classes.','.mysql_result($ret,0,0);
		}
	}
	
	
	if (!($e=id_list_validation($classes)))
	{
		$query = "SELECT * FROM classes WHERE id IN($classes) ORDER BY title ASC";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret)) 
		{	
			echo "<div class='navigationClasses'>";
			while($row = mysql_fetch_array($ret)) 
			{	
				if(isset($first))
				{
					if ($first!=$row['id'])
					{
						echo "<li><a href='class/".$row['id']."/'  class='navigationTitles'>".$row['title']."</a>";
					}
				}
				else
				{
					echo "<li><a href='class/".$row['id']."/'  class='navigationTitles'>".$row['title']."</a>";
				}
			}
			echo "</div>";
		}
	}
	else
	{
		$error[] = $e;
	}
	if(can_view_admin_panel($logged_userid))
	{	
		$admin_view_names =  array();
		if (can_create_class($logged_userid))
		{
			$admin_view_names['new_class'] =  _('New Class'); 
		}
		if (can_edit_titles($logged_userid))
		{
			$admin_view_names['edit_titles'] = _('Titles'); 
		}
		if (can_edit_class_association_types($logged_userid))
		{
			$admin_view_names['edit_class_association_types'] = _('Association Types'); 
		}
		if (can_view_statistics($logged_userid))
		{
			$admin_view_names['stats'] = _('Stats'); 
		}
	}
?>
<?php if(can_view_classes_list($logged_userid)) {?>
		<li><a href='classes/'  class='navigationTitles globalNav' <?php if($v=='classes') {echo "id='navigationClassHl'";}?>><?php echo _("Classes");?></a></li>
<?php }?>
<?php if(can_view_professor_list($logged_userid)) {?>
		<li><a href='professors/' class='navigationTitles globalNav' <?php if($v=='professors') {echo "id='navigationClassHl'";}?>><?php echo _("Professors");?></a></li>
<?php }?>
<?php if(can_view_admin_panel($logged_userid)) {?>
		<div class='adminPanel'><li><a href='home/'  class='navigationTitles globalNav' <?php if($v=='home'||array_key_exists($v, $admin_view_names)) {echo "id='navigationClassHl'";}?>><?php echo _('Administration');?></a>
		<ul>
<?php 	if($v=='home'||array_key_exists($v, $admin_view_names)) {?>		
<?php		foreach ($admin_view_names as $view => $view_title) {?>
				<li><a href="<?php echo $view;?>/" <?php if (isset($v)&&$v==$view) echo "id='navigationHl'";?> class='navigationClassDirectories'><?php echo $view_title;?></a></li>	
<?php		}?>
<?php 	}?>
		</ul></li></div>  
<?php }?>  

</ul>
